<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'filters',
        'format',
        'status',
        'file_path',
        'generated_at',
        'generated_by',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'json',
            'generated_at' => 'datetime',
        ];
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
