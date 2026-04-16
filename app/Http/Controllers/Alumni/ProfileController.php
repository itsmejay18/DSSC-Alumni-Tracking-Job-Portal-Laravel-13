<?php

namespace App\Http\Controllers\Alumni;

use App\Events\ProfileUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alumni\UpdateProfileRequest;
use App\Models\EmploymentTracking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    public function show()
    {
        return view('alumni.profile.show', [
            'user' => auth()->user()->load(['alumniProfile.course.college', 'employmentTrackings' => fn ($query) => $query->latest('change_date')->take(5)]),
        ]);
    }

    public function edit()
    {
        return view('alumni.profile.edit', [
            'user' => auth()->user()->load('alumniProfile.course'),
            'courses' => \App\Models\Course::query()->where('is_active', true)->orderBy('course_name')->get(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user()->load('alumniProfile');
        $profile = $user->alumniProfile;
        $before = $profile?->only(['employment_status', 'current_company', 'current_job_title']) ?? [];

        $payload = $request->validated();

        DB::transaction(function () use ($request, $user, $profile, $payload): void {
            $user->update([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'profile_photo_path' => $request->hasFile('profile_photo')
                    ? $this->storeOptimizedImage($request->file('profile_photo'), 'profiles')
                    : $user->profile_photo_path,
            ]);

            $profile?->update([
                'student_id' => $payload['student_id'],
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'middle_name' => $payload['middle_name'] ?? null,
                'birth_date' => $payload['birth_date'] ?? null,
                'gender' => $payload['gender'] ?? null,
                'contact_number' => $payload['contact_number'] ?? null,
                'course_id' => $payload['course_id'] ?? null,
                'year_graduated' => $payload['year_graduated'] ?? null,
                'graduation_date' => $payload['graduation_date'] ?? null,
                'skills' => $this->normalizeSkills($payload['skills'] ?? []),
                'employment_status' => $payload['employment_status'],
                'current_job_title' => $payload['current_job_title'] ?? null,
                'current_company' => $payload['current_company'] ?? null,
                'current_salary' => $payload['current_salary'] ?? null,
                'linkedin_url' => $payload['linkedin_url'] ?? null,
                'portfolio_url' => $payload['portfolio_url'] ?? null,
            ]);
        });

        $after = $profile?->fresh()?->only(['employment_status', 'current_company', 'current_job_title']) ?? [];

        if ($before !== $after) {
            // REAL-TIME SOLUTION: Employment status changes are captured instantly instead of waiting for manual surveys.
            EmploymentTracking::query()->create([
                'alumni_id' => $user->id,
                'previous_status' => $before['employment_status'] ?? null,
                'new_status' => $after['employment_status'] ?? null,
                'previous_company' => $before['current_company'] ?? null,
                'new_company' => $after['current_company'] ?? null,
                'previous_title' => $before['current_job_title'] ?? null,
                'new_title' => $after['current_job_title'] ?? null,
                'changed_by' => $user->id,
                'change_date' => now(),
                'notes' => 'Profile employment information updated.',
            ]);
        }

        event(new ProfileUpdated($profile->fresh(), $payload));

        return redirect()->route('alumni.profile.show')->with('success', 'Profile updated successfully.');
    }

    public function employmentHistory()
    {
        $history = auth()->user()->employmentTrackings()->latest('change_date')->paginate(config('settings.pagination.per_page', 20));

        return view('alumni.profile.employment-history', compact('history'));
    }

    public function exportData()
    {
        $user = auth()->user()->load(['alumniProfile.course.college', 'jobApplications.job.employer', 'jobMatches.job']);

        return response()->json($user->toArray());
    }

    public function destroy(): RedirectResponse
    {
        $user = auth()->user();
        $user->delete();
        Auth::logout();

        return redirect()->route('landing')->with('success', 'Your account has been scheduled for deletion.');
    }

    protected function storeOptimizedImage($file, string $directory): string
    {
        $manager = new ImageManager(new Driver());
        $path = "{$directory}/".uniqid().'.jpg';
        $image = $manager->read($file->getRealPath())->scaleDown(width: 600);

        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
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
}
