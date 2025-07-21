<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'reward_earned',
        'watch_duration',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'reward_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Check if video was watched completely (at least 80% of duration)
    public function isCompleteWatch()
    {
        return $this->watch_duration >= ($this->video->duration * 0.8);
    }

    // Get watch completion percentage
    public function getWatchPercentageAttribute()
    {
        if (!$this->video) return 0;
        return min(100, ($this->watch_duration / $this->video->duration) * 100);
    }
}
