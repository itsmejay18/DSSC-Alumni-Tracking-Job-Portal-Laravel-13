<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\EmploymentTracking;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', $this->payload());
    }

    public function data(): JsonResponse
    {
        return response()->json($this->payload());
    }

    protected function payload(): array
    {
        return Cache::remember('admin.dashboard.payload.v3', now()->addMinutes(5), function (): array {
            return [
                'stats' => [
                    'alumni' => User::query()->alumni()->count(),
                    'employers' => User::query()->employers()->count(),
                    'active_jobs' => Job::query()->where('status', 'approved')->count(),
                    'applications' => JobApplication::query()->count(),
                ],
                'employmentTrend' => collect(range(11, 0))->map(function (int $offset) {
                    $date = now()->subMonths($offset);

                    return [
                        'label' => $date->format('M Y'),
                        'value' => EmploymentTracking::query()
                            ->where('new_status', 'employed')
                            ->whereYear('change_date', $date->year)
                            ->whereMonth('change_date', $date->month)
                            ->count(),
                    ];
                })->all(),
                'jobDistribution' => Job::query()
                    ->with('jobCategory')
                    ->get()
                    ->groupBy(fn (Job $job) => $job->jobCategory?->category_name ?? 'Uncategorized')
                    ->map(fn ($jobs, $category) => ['label' => $category, 'value' => $jobs->count()])
                    ->values()
                    ->all(),
                'alumniGrowth' => collect(range(11, 0))->map(function (int $offset) {
                    $date = now()->subMonths($offset);

                    return [
                        'label' => $date->format('M Y'),
                        'value' => User::query()->alumni()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                    ];
                })->all(),
                'topEmployers' => Employer::query()
                    ->withCount('jobs')
                    ->orderByDesc('jobs_count')
                    ->take(5)
                    ->get()
                    ->map(fn (Employer $employer) => ['label' => $employer->company_name, 'value' => $employer->jobs_count])
                    ->all(),
                'recentJobs' => Job::query()->with(['employer', 'jobCategory'])->latest()->take(5)->get(),
                'recentApplications' => JobApplication::query()->with(['job', 'alumni.alumniProfile'])->latest()->take(5)->get(),
                'pendingEmployers' => Employer::query()
                    ->with('user')
                    ->where('is_verified', false)
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(fn (Employer $employer) => [
                        'id' => $employer->id,
                        'company_name' => $employer->company_name,
                        'email' => $employer->user?->email,
                    ])
                    ->all(),
            ];
        });
    }
}
