<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AlumniController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        return view('admin.alumni.index', [
            'alumni' => $this->searchService->searchAlumni($request->all()),
        ]);
    }

    public function show(User $alumni)
    {
        abort_unless($alumni->isAlumni(), 404);

        return view('admin.alumni.show', [
            'alumni' => $alumni->load(['alumniProfile.course.college', 'jobApplications.job', 'jobMatches.job']),
        ]);
    }

    public function edit(User $alumni)
    {
        abort_unless($alumni->isAlumni(), 404);

        return view('admin.alumni.edit', [
            'alumni' => $alumni->load('alumniProfile.course'),
            'courses' => \App\Models\Course::query()->where('is_active', true)->orderBy('course_name')->get(),
        ]);
    }

    public function update(Request $request, User $alumni): RedirectResponse
    {
        abort_unless($alumni->isAlumni(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($alumni->id)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'year_graduated' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'employment_status' => ['required', Rule::in(['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'])],
            'contact_number' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($alumni, $validated): void {
            $alumni->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $alumni->alumniProfile?->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'course_id' => $validated['course_id'] ?? null,
                'year_graduated' => $validated['year_graduated'] ?? null,
                'employment_status' => $validated['employment_status'],
                'contact_number' => $validated['contact_number'] ?? null,
            ]);
        });

        ActivityLog::record($request->user(), 'update', 'alumni', 'Updated alumni record.', [], $validated, $request->ip(), $request->userAgent());

        return redirect()->route('admin.alumni.show', $alumni)->with('success', 'Alumni record updated successfully.');
    }

    public function verify(User $alumni)
    {
        abort_unless($alumni->isAlumni(), 404);

        return view('admin.alumni.verify', ['alumni' => $alumni->load('alumniProfile.course')]);
    }

    public function storeVerification(Request $request, User $alumni): RedirectResponse
    {
        abort_unless($alumni->isAlumni(), 404);

        $validated = $request->validate([
            'is_verified' => ['required', 'boolean'],
        ]);

        $alumni->alumniProfile?->update([
            'is_verified' => (bool) $validated['is_verified'],
            'verification_date' => now(),
        ]);

        return redirect()->route('admin.alumni.show', $alumni)->with('success', 'Alumni verification status updated.');
    }
}
