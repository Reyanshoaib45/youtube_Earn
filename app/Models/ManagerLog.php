<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'action_type',
        'model_type',
        'model_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
        'severity',
    ];

    protected function casts(): array
    {
        return [
            'old_data' => 'json',
            'new_data' => 'json',
        ];
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function getSeverityColor()
    {
        return match($this->severity) {
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'critical' => 'purple',
            default => 'gray',
        };
    }

    public function getSeverityIcon()
    {
        return match($this->severity) {
            'info' => 'fa-info-circle',
            'warning' => 'fa-exclamation-triangle',
            'error' => 'fa-times-circle',
            'critical' => 'fa-exclamation-circle',
            default => 'fa-circle',
        };
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    public function scopeByAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }
}
