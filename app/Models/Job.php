<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Job extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'employer_id',
        'job_category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'qualifications',
        'salary_min',
        'salary_max',
        'salary_type',
        'location',
        'is_remote',
        'job_type',
        'experience_level',
        'education_requirement',
        'skills_required',
        'application_deadline',
        'max_applicants',
        'status',
        'approved_by',
        'approved_at',
        'views_count',
        'applications_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'is_remote' => 'boolean',
            'skills_required' => 'array',
            'application_deadline' => 'date',
            'approved_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Job $job): void {
            $job->slug = $job->slug ?: Str::slug($job->title);

            $original = $job->slug;
            $counter = 1;

            while (
                static::query()
                    ->where('slug', $job->slug)
                    ->when($job->exists, fn ($query) => $query->whereKeyNot($job->getKey()))
                    ->exists()
            ) {
                $job->slug = "{$original}-{$counter}";
                $counter++;
            }
        });
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(JobMatch::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['approved'])->where(function ($inner) {
            $inner->whereNull('application_deadline')->orWhereDate('application_deadline', '>=', now());
        });
    }

    public function getSalaryRangeAttribute(): string
    {
        if (! $this->salary_min && ! $this->salary_max) {
            return 'Salary negotiable';
        }

        $min = $this->salary_min ? number_format((float) $this->salary_min, 2) : '0.00';
        $max = $this->salary_max ? number_format((float) $this->salary_max, 2) : $min;

        return "PHP {$min} - PHP {$max} / {$this->salary_type}";
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'qualifications' => $this->qualifications,
            'location' => $this->location,
            'job_type' => $this->job_type,
        ];
    }
}
