<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreJobRequest;
use App\Models\ActivityLog;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;

class JobController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employerProfile;

        if (! $employer) {
            return redirect()
                ->route('employer.profile.edit')
                ->with('warning', 'Complete your company profile before managing jobs.');
        }

        $jobs = $employer
            ->jobs()
            ->with('jobCategory')
            ->latest()
            ->paginate(config('settings.pagination.per_page', 20));

        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        if (! auth()->user()->employerProfile) {
            return redirect()
                ->route('employer.profile.edit')
                ->with('warning', 'Complete your company profile before posting jobs.');
        }

        return view('employer.jobs.create', [
            'categories' => \App\Models\JobCategory::query()->where('is_active', true)->orderBy('category_name')->get(),
        ]);
    }

    public function store(StoreJobRequest $request): RedirectResponse
    {
        $employer = $request->user()->employerProfile;

        if (! $employer) {
            return redirect()
                ->route('employer.profile.edit')
                ->with('warning', 'Complete your company profile before posting jobs.');
        }

        $job = $employer->jobs()->create([
            ...$request->validated(),
            'description' => $this->sanitizeRichText($request->string('description')->toString()),
            'skills_required' => $this->normalizeSkills($request->input('skills_required', [])),
            'status' => 'pending',
            'is_featured' => $request->boolean('is_featured'),
            'is_remote' => $request->boolean('is_remote'),
        ]);

        ActivityLog::record($request->user(), 'create', 'job', 'Created a new job posting.', [], $job->toArray(), $request->ip(), $request->userAgent());

        return redirect()->route('employer.jobs.show', $job)->with('success', 'Job created and submitted for approval.');
    }

    public function show(Job $job)
    {
        abort_unless($job->employer_id === auth()->user()->employerProfile?->id, 403);

        return view('employer.jobs.show', [
            'job' => $job->load(['jobCategory', 'applications.alumni.alumniProfile']),
        ]);
    }

    public function edit(Job $job)
    {
        abort_unless($job->employer_id === auth()->user()->employerProfile?->id, 403);

        return view('employer.jobs.edit', [
            'job' => $job,
            'categories' => \App\Models\JobCategory::query()->where('is_active', true)->orderBy('category_name')->get(),
        ]);
    }

    public function update(StoreJobRequest $request, Job $job): RedirectResponse
    {
        abort_unless($job->employer_id === auth()->user()->employerProfile?->id, 403);

        $job->update([
            ...$request->validated(),
            'description' => $this->sanitizeRichText($request->string('description')->toString()),
            'skills_required' => $this->normalizeSkills($request->input('skills_required', [])),
            'is_featured' => $request->boolean('is_featured'),
            'is_remote' => $request->boolean('is_remote'),
            'status' => 'pending',
        ]);

        ActivityLog::record($request->user(), 'update', 'job', 'Updated a job posting.', [], $job->toArray(), $request->ip(), $request->userAgent());

        return redirect()->route('employer.jobs.show', $job)->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        abort_unless($job->employer_id === auth()->user()->employerProfile?->id, 403);

        $job->delete();

        return redirect()->route('employer.jobs.index')->with('success', 'Job removed successfully.');
    }

    public function close(Job $job): RedirectResponse
    {
        abort_unless($job->employer_id === auth()->user()->employerProfile?->id, 403);

        $job->update(['status' => 'closed']);

        return back()->with('success', 'Job has been closed.');
    }

    protected function normalizeSkills(array $skills): array
    {
        return collect($skills)
            ->flatMap(fn ($value) => explode(',', (string) $value))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    protected function sanitizeRichText(?string $content): ?string
    {
        if (blank($content)) {
            return null;
        }

        if (class_exists(\HTMLPurifier::class)) {
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'p,br,strong,b,em,i,ul,ol,li,blockquote,a[href|target|rel],code,pre,h1,h2,h3,h4');
            $config->set('Attr.EnableID', false);
            $config->set('HTML.TargetBlank', true);

            return (new \HTMLPurifier($config))->purify($content);
        }

        return strip_tags($content, '<p><br><strong><b><em><i><ul><ol><li><blockquote><a><code><pre><h1><h2><h3><h4>');
    }
}
