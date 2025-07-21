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

    public static function generateUniqueReferralCode()
{
    do {
        $code = strtoupper(Str::random(8));
    } while (self::where('referral_code', $code)->exists());

    return $code;
}

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
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
}