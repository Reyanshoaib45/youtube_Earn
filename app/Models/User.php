<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
        'referred_by',
        'total_earnings',
        'is_banned',
        'ban_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'total_earnings' => 'decimal:2',
        'is_banned' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });
    }

    // Generate unique referral code
    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());
        
        return $code;
    }

    // Relationships
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function userVideos()
    {
        return $this->hasMany(UserVideo::class);
    }

    // Helper Methods
    public function currentPackage()
    {
        return $this->purchases()
            ->with('package')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first()
            ?->package;
    }

    public function todayWatchedVideos()
    {
        return $this->userVideos()
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function canWithdraw()
    {
        return $this->referrals()->count() >= 3;
    }

    public function getReferralLinkAttribute()
    {
        return url('/register?referral_code=' . $this->referral_code);
    }

    // Earnings calculations
    public function getTodayEarningsAttribute()
    {
        return $this->userVideos()
            ->whereDate('created_at', Carbon::today())
            ->sum('reward_earned') ?? 0;
    }

    public function getThisWeekEarningsAttribute()
    {
        return $this->userVideos()
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('reward_earned') ?? 0;
    }

    public function getThisMonthEarningsAttribute()
    {
        return $this->userVideos()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('reward_earned') ?? 0;
    }

    public function getReferralEarningsAttribute()
    {
        return $this->referrals()->sum('reward') ?? 0;
    }

    public function getTotalWithdrawnAttribute()
    {
        return $this->withdrawalRequests()->where('status', 'approved')->sum('amount') ?? 0;
    }

    public function getAvailableBalanceAttribute()
    {
        return $this->total_earnings - $this->total_withdrawn;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_banned', false);
    }

    public function scopeWithActivePackage($query)
    {
        return $query->whereHas('purchases', function($q) {
            $q->where('expires_at', '>', Carbon::now());
        });
    }

    public function scopeTopEarners($query, $limit = 10)
    {
        return $query->orderBy('total_earnings', 'desc')->limit($limit);
    }

    public function scopeTopReferrers($query, $limit = 10)
    {
        return $query->withCount('referrals')
                    ->orderBy('referrals_count', 'desc')
                    ->limit($limit);
    }
}