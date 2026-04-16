<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employerProfile;

        return view('employer.dashboard', [
            'employer' => $employer?->load('jobs'),
            'stats' => [
                'jobs' => $employer?->jobs()->count() ?? 0,
                'approved_jobs' => $employer?->jobs()->where('status', 'approved')->count() ?? 0,
                'applications' => $employer?->jobs()->withCount('applications')->get()->sum('applications_count') ?? 0,
                'pending_jobs' => $employer?->jobs()->where('status', 'pending')->count() ?? 0,
            ],
            'latestJobs' => $employer?->jobs()->latest()->take(5)->get() ?? collect(),
            'latestApplications' => \App\Models\JobApplication::query()
                ->whereHas('job', fn ($query) => $query->where('employer_id', $employer?->id))
                ->with(['job', 'alumni.alumniProfile'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
