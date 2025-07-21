<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'daily_video_limit',
        'total_reward',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_reward' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Calculate profit amount
    public function getProfitAttribute()
    {
        return $this->total_reward - $this->price;
    }

    // Calculate ROI percentage
    public function getRoiPercentageAttribute()
    {
        return round((($this->total_reward - $this->price) / $this->price) * 100, 1);
    }

    // Calculate daily earning potential
    public function getDailyEarningAttribute()
    {
        return $this->daily_video_limit * 60; // Rs.60 per video
    }

    // Get total videos in package
    public function getTotalVideosAttribute()
    {
        return $this->daily_video_limit * $this->duration_days;
    }

    // Validate package calculations
    public function isCalculationValid()
    {
        $expectedReward = $this->daily_video_limit * $this->duration_days * 60;
        return abs($this->total_reward - $expectedReward) < 0.01; // Allow small floating point differences
    }

    // Get package tier based on price
    public function getTierAttribute()
    {
        if ($this->price <= 1500) return 'Beginner';
        if ($this->price <= 3500) return 'Intermediate';
        if ($this->price <= 6000) return 'Advanced';
        return 'Premium';
    }
}
