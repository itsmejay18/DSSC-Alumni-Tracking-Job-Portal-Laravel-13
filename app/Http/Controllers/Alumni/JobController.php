<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\SearchService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        return view('alumni.jobs.index', [
            'jobs' => $this->searchService->searchJobs($request->all()),
            'categories' => \App\Models\JobCategory::query()->where('is_active', true)->orderBy('category_name')->get(),
        ]);
    }

    public function matched()
    {
        $matches = auth()->user()
            ->jobMatches()
            ->with(['job.employer', 'job.jobCategory'])
            ->latest('match_score')
            ->paginate(config('settings.pagination.per_page', 20));

        return view('alumni.jobs.matched', compact('matches'));
    }

    public function show(Job $job)
    {
        $job->increment('views_count');

        return view('alumni.jobs.show', [
            'job' => $job->load(['employer.user', 'jobCategory']),
            'existingApplication' => auth()->user()->jobApplications()->where('job_id', $job->id)->first(),
            'match' => auth()->user()->jobMatches()->where('job_id', $job->id)->first(),
        ]);
    }
}
