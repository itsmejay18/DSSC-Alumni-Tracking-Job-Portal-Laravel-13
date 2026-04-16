<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'birth_date',
        'gender',
        'contact_number',
        'course_id',
        'year_graduated',
        'graduation_date',
        'skills',
        'employment_status',
        'current_job_title',
        'current_company',
        'current_salary',
        'linkedin_url',
        'portfolio_url',
        'is_verified',
        'verification_date',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'graduation_date' => 'date',
            'skills' => 'array',
            'current_salary' => 'decimal:2',
            'is_verified' => 'boolean',
            'verification_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name])));
    }
}
