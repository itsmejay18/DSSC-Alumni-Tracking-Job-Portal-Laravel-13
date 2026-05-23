<?php

namespace App\Http\Controllers\Admin;

use App\Events\JobApproved;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::query()
            ->with(['employer.user', 'jobCategory'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(config('settings.pagination.per_page', 20))
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        return view('admin.jobs.show', [
            'job' => $job->load(['employer.user', 'jobCategory', 'applications.job', 'applications.alumni.alumniProfile']),
        ]);
    }

    public function approve(Request $request, Job $job): RedirectResponse
    {
        $job->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        event(new JobApproved($job->fresh(['jobCategory', 'employer', 'approvedBy'])));

        return back()->with('success', 'Job approved successfully.');
    }

    public function reject(Request $request, Job $job): RedirectResponse
    {
        $job->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        ActivityLog::record($request->user(), 'update', 'job', 'Rejected a job posting.');

        return back()->with('success', 'Job has been rejected.');
    }

    public function destroy(Request $request, Job $job): RedirectResponse
    {
        $job->delete();

        ActivityLog::record($request->user(), 'delete', 'job', 'Deleted a job posting.');

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
