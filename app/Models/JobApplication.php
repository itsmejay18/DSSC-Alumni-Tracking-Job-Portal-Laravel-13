<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'alumni_id',
        'cover_letter',
        'resume_path',
        'portfolio_link',
        'availability_date',
        'expected_salary',
        'status',
        'status_updated_by',
        'status_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'availability_date' => 'date',
            'expected_salary' => 'decimal:2',
            'reviewed_at' => 'datetime',
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

    public function statusUpdater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }
}
