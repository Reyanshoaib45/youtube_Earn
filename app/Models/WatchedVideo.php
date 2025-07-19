<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchedVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'watched_seconds',
        'watch_percentage',
        'is_completed',
        'reward_granted',
        'reward_amount',
        'started_at',
        'completed_at',
        'ip_address',
        'device_type',
        'browser',
    ];

    protected function casts(): array
    {
        return [
            'watch_percentage' => 'decimal:2',
            'is_completed' => 'boolean',
            'reward_granted' => 'boolean',
            'reward_amount' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
