<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
        'priority',
        'action_url',
        'action_text',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'json',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'video_watched' => 'fa-play-circle',
            'withdrawal_requested' => 'fa-money-bill',
            'withdrawal_approved' => 'fa-check-circle',
            'withdrawal_rejected' => 'fa-times-circle',
            'referral_bonus' => 'fa-user-plus',
            'package_purchased' => 'fa-box',
            'package_expired' => 'fa-exclamation-triangle',
            default => 'fa-bell',
        };
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }
}
