<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'is_active',
        'amount_paid',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_screenshot',
        'payment_notes',
        'approved_at',
        'approved_by',
        'videos_watched',
        'total_earned',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
            'amount_paid' => 'decimal:2',
            'total_earned' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);

    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isActive()
    {
        return $this->is_active && $this->end_date >= now();
    }

    public function daysRemaining()
    {
        return $this->end_date->diffInDays(now());
    }

    public function getProgressPercentage()
    {
        $totalDays = $this->start_date->diffInDays($this->end_date);
        $usedDays = $this->start_date->diffInDays(now());

        return min(100, ($usedDays / $totalDays) * 100);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('end_date', '>=', now());
    }
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false; // or true, depending on your logic for null values
        }

        return $this->expires_at->isPast();
    }



    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('payment_status', 'approved');
    }
}
