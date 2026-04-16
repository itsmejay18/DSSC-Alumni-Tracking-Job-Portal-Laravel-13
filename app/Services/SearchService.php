<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    public function searchJobs(array $filters): LengthAwarePaginator
    {
        $query = Job::query()
            ->with(['employer', 'jobCategory'])
            ->approved()
            ->open()
            ->latest();

        if (! empty($filters['q'])) {
            try {
                $ids = Job::search($filters['q'])->keys();
                $query->whereIn('id', $ids);
            } catch (\Throwable $exception) {
                $query->where(function (Builder $builder) use ($filters): void {
                    $builder
                        ->where('title', 'like', '%'.$filters['q'].'%')
                        ->orWhere('description', 'like', '%'.$filters['q'].'%')
                        ->orWhere('requirements', 'like', '%'.$filters['q'].'%');
                });
            }
        }

        $query
            ->when($filters['job_type'] ?? null, fn (Builder $builder, $value) => $builder->where('job_type', $value))
            ->when($filters['experience_level'] ?? null, fn (Builder $builder, $value) => $builder->where('experience_level', $value))
            ->when($filters['location'] ?? null, fn (Builder $builder, $value) => $builder->where('location', 'like', '%'.$value.'%'))
            ->when($filters['min_salary'] ?? null, fn (Builder $builder, $value) => $builder->where('salary_min', '>=', $value))
            ->when($filters['max_salary'] ?? null, fn (Builder $builder, $value) => $builder->where('salary_max', '<=', $value))
            ->when($filters['job_category_id'] ?? null, fn (Builder $builder, $value) => $builder->where('job_category_id', $value));

        return $query->paginate(config('settings.pagination.per_page', 20))->withQueryString();
    }

    public function searchAlumni(array $filters): LengthAwarePaginator
    {
        $query = User::query()
            ->alumni()
            ->with(['alumniProfile.course.college'])
            ->latest();

        $query
            ->when($filters['name'] ?? null, function (Builder $builder, $value): void {
                $builder->where(function (Builder $inner) use ($value): void {
                    $inner
                        ->where('name', 'like', '%'.$value.'%')
                        ->orWhereHas('alumniProfile', function (Builder $profileQuery) use ($value): void {
                            $profileQuery
                                ->where('first_name', 'like', '%'.$value.'%')
                                ->orWhere('last_name', 'like', '%'.$value.'%');
                        });
                });
            })
            ->when($filters['student_id'] ?? null, fn (Builder $builder, $value) => $builder->whereHas('alumniProfile', fn (Builder $query) => $query->where('student_id', 'like', '%'.$value.'%')))
            ->when($filters['course_id'] ?? null, fn (Builder $builder, $value) => $builder->whereHas('alumniProfile', fn (Builder $query) => $query->where('course_id', $value)))
            ->when($filters['year_graduated'] ?? null, fn (Builder $builder, $value) => $builder->whereHas('alumniProfile', fn (Builder $query) => $query->where('year_graduated', $value)))
            ->when($filters['employment_status'] ?? null, fn (Builder $builder, $value) => $builder->whereHas('alumniProfile', fn (Builder $query) => $query->where('employment_status', $value)));

        return $query->paginate(config('settings.pagination.per_page', 20))->withQueryString();
    }
}
