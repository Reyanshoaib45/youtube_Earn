<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'country',
        'city',
        'role',
        'is_active',
        'referral_code',
        'referred_by',
        'referral_earnings',
        'reward_balance',
        'last_login_at',
        'is_banned',
        'ban_reason',
        'banned_at',
        'banned_by',
        'registration_ip',
        'current_ip',
        'current_location',
        'location_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'banned_at' => 'datetime',
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
        'location_data' => 'array',
        'referral_earnings' => 'decimal:2',
        'reward_balance' => 'decimal:2',
    ];

    // Relationships
    public function activePackage()
    {
        return $this->hasOne(UserPackage::class)
            ->where('is_active', true)
            ->where('end_date', '>', now());
    }




    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function watchedVideos()
    {
        return $this->hasMany(WatchedVideo::class);
    }

    public function completedVideos()
    {
        return $this->hasMany(WatchedVideo::class)->where('is_completed', true);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function managerLogs()
    {
        return $this->hasMany(ManagerLog::class, 'manager_id');
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function locationLogs()
    {
        return $this->hasMany(LocationLog::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    // Boot method to generate referral code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = $user->generateReferralCode();
            }
        });
    }

    // Helper methods
    public function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function getAvatarUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getTotalEarnings()
    {
        return $this->watchedVideos()->where('reward_granted', true)->sum('reward_amount') + $this->referral_earnings;
    }

    public function getCompletedVideosCount()
    {
        return $this->watchedVideos()->where('is_completed', true)->count();
    }

    public function getPendingWithdrawals()
    {
        return $this->withdrawals()->where('status', 'pending')->sum('amount');
    }

    public function getSuccessfulReferrals()
    {
        return $this->referrals()->where('is_paid', true)->count();
    }

    public function getReferralLink()
    {
        return url('/register?ref=' . $this->referral_code);
    }

    public function getCurrentLocation()
    {
        return $this->current_location ?? 'Unknown Location';
    }

    public function getLatestLocationLog()
    {
        return $this->locationLogs()->latest()->first();
    }

    public function banUser($reason, $bannedBy)
    {
        $this->update([
            'is_banned' => true,
            'ban_reason' => $reason,
            'banned_at' => now(),
            'banned_by' => $bannedBy,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $this->id,
            'type' => 'account_banned',
            'title' => 'Account Banned',
            'message' => 'Your account has been banned. Reason: ' . $reason,
            'data' => [
                'ban_reason' => $reason,
                'banned_by' => $bannedBy,
                'banned_at' => now(),
            ],
            'priority' => 'high',
        ]);
    }

    public function unbanUser()
    {
        $this->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
            'banned_by' => null,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $this->id,
            'type' => 'account_unbanned',
            'title' => 'Account Unbanned',
            'message' => 'Your account has been unbanned. You can now access the platform.',
            'priority' => 'medium',
        ]);
    }

    public function isBanned()
    {
        return $this->is_banned;
    }

    public function canWithdraw()
    {
        return $this->activePackage && !$this->is_banned && $this->is_active;
    }

    public function hasActivePackage()
    {
        return $this->activePackage !== null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function userPackage()
    {
        return $this->hasOne(UserPackage::class);
    }

    public function scopeBanned($query)
    {
        return $query->where('is_banned', true);
    }

    public function scopeNotBanned($query)
    {
        return $query->where('is_banned', false);
    }

    public function scopeWithActivePackage($query)
    {
        return $query->whereHas('activePackage');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
    public function ipTrackings()
    {
        return $this->hasMany(IpTracking::class, 'ip_address', 'current_ip');
    }
}
