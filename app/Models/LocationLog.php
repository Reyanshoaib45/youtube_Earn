<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
        'timezone',
        'isp',
        'action_type',
        'user_agent',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLocationStringAttribute()
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return implode(', ', $parts) ?: 'Unknown Location';
    }

    public function getFormattedLocationAttribute()
    {
        return [
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'coordinates' => $this->latitude && $this->longitude ? 
                ['lat' => $this->latitude, 'lng' => $this->longitude] : null,
            'timezone' => $this->timezone,
            'isp' => $this->isp,
        ];
    }
}
