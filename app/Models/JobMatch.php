<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMatch extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'job_id',
        'alumni_id',
        'match_score',
        'match_reasons',
        'is_viewed',
        'is_applied',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'decimal:2',
            'match_reasons' => 'array',
            'is_viewed' => 'boolean',
            'is_applied' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }
}
