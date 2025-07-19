<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'youtube_link',
        'min_watch_minutes',
        'reward_amount',
        'category_id',
        'difficulty_level',
        'tags',
        'thumbnail',
        'view_count',
        'completion_count',
        'total_rewards_paid',
        'is_active',
        'is_featured',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'reward_amount' => 'decimal:2',
            'total_rewards_paid' => 'decimal:2',
            'tags' => 'json',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function watchedVideos()
    {
        return $this->hasMany(WatchedVideo::class);
    }

    public function completedWatches()
    {
        return $this->hasMany(WatchedVideo::class)->where('is_completed', true);
    }

    public function getCompletionRate()
    {
        if ($this->view_count == 0) return 0;
        return ($this->completion_count / $this->view_count) * 100;
    }

    public function getAverageWatchTime()
    {
        return $this->watchedVideos()->avg('watched_seconds') ?? 0;
    }

    public function getYoutubeId()
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $this->youtube_link, $matches);
        return $matches[1] ?? null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
    }
}
