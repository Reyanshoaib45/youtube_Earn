<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'video_limit',
        'reward_per_video',
        'min_withdrawal',
        'referral_bonus',
        'validity_days',
        'features',
        'is_active',
        'is_featured',
        'badge_text',
        'badge_color',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'reward_per_video' => 'decimal:2',
            'min_withdrawal' => 'decimal:2',
            'referral_bonus' => 'decimal:2',
            'features' => 'json',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function activeUserPackages()
    {
        return $this->hasMany(UserPackage::class)->where('is_active', true)->where('end_date', '>=', now());
    }

    public function getMaxEarnings()
    {
        return $this->video_limit * $this->reward_per_video;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }
}
