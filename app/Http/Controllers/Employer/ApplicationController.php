<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\UpdateApplicationRequest;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;

class ApplicationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index()
    {
        $applications = JobApplication::query()
            ->whereHas('job', fn ($query) => $query->where('employer_id', auth()->user()->employerProfile?->id))
            ->with(['job', 'alumni.alumniProfile'])
            ->latest()
            ->paginate(config('settings.pagination.per_page', 20));

        return view('employer.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        abort_unless($application->job?->employer_id === auth()->user()->employerProfile?->id, 403);

        return view('employer.applications.show', [
            'application' => $application->load(['job', 'alumni.alumniProfile']),
        ]);
    }

    public function update(UpdateApplicationRequest $request, JobApplication $application): RedirectResponse
    {
        abort_unless($application->job?->employer_id === auth()->user()->employerProfile?->id, 403);

        $application->update([
            'status' => $request->string('status'),
            'status_notes' => $request->string('status_notes'),
            'status_updated_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $this->notificationService->sendApplicationStatus($application->fresh(['job', 'alumni']));

        return back()->with('success', 'Application status updated successfully.');
    }
}
