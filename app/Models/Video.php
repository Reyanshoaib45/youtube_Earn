<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'youtube_url',
        'reward',
        'duration',
        'is_active',
    ];

    protected $casts = [
        'reward' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function userVideos()
    {
        return $this->hasMany(UserVideo::class);
    }

    public function getYoutubeIdAttribute()
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $this->youtube_url, $matches);
        return $matches[1] ?? null;
    }
}
