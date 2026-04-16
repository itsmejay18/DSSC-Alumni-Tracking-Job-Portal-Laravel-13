<?php

namespace App\Http\Controllers\Alumni;

use App\Events\JobApplied;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alumni\ApplyJobRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index()
    {
        $applications = auth()->user()
            ->jobApplications()
            ->with(['job.employer', 'statusUpdater'])
            ->latest()
            ->paginate(config('settings.pagination.per_page', 20));

        return view('alumni.applications.index', compact('applications'));
    }

    public function store(ApplyJobRequest $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'approved', 403, 'Only approved jobs can be applied to.');

        if ($job->max_applicants && $job->applications()->count() >= $job->max_applicants) {
            return back()->withErrors(['resume' => 'This job has already reached the maximum number of applicants.']);
        }

        if ($request->user()->jobApplications()->where('job_id', $job->id)->exists()) {
            return back()->withErrors(['resume' => 'You have already applied to this job.']);
        }

        $application = DB::transaction(function () use ($request, $job) {
            $resumePath = $request->file('resume')->store('resumes', 'public');

            $application = JobApplication::query()->create([
                'job_id' => $job->id,
                'alumni_id' => $request->user()->id,
                'cover_letter' => $request->string('cover_letter'),
                'resume_path' => $resumePath,
                'portfolio_link' => $request->string('portfolio_link'),
                'availability_date' => $request->date('availability_date'),
                'expected_salary' => $request->input('expected_salary'),
                'status' => 'pending',
            ]);

            $job->update(['applications_count' => $job->applications()->count()]);
            $request->user()->jobMatches()->where('job_id', $job->id)->update(['is_applied' => true]);

            return $application;
        });

        event(new JobApplied($application->load(['job.employer.user', 'alumni'])));

        if ($job->employer?->user) {
            $this->notificationService->createNotification(
                $job->employer->user,
                'system_alert',
                'New job application received',
                auth()->user()->name." applied for {$job->title}.",
                ['route' => route('employer.applications.show', $application)]
            );
        }

        return redirect()->route('alumni.applications.index')->with('success', 'Application submitted successfully.');
    }
}
