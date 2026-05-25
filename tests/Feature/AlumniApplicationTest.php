<?php

namespace Tests\Feature;

use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_alumni_can_apply_to_an_approved_job(): void
    {
        Storage::fake('public');

        [$alumni, $job] = $this->seedScenario();

        $response = $this->actingAs($alumni)->post(route('alumni.jobs.apply', $job), [
            'cover_letter' => str_repeat('Experienced and eager to contribute. ', 3),
            'resume' => UploadedFile::fake()->create('resume.pdf', 120),
            'portfolio_link' => 'https://example.com/portfolio',
            'availability_date' => now()->addDays(10)->format('Y-m-d'),
            'expected_salary' => 25000,
        ]);

        $response->assertRedirect(route('alumni.applications.index'));
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'alumni_id' => $alumni->id,
            'status' => 'pending',
        ]);
    }

    public function test_employer_can_view_resume_for_their_job_application(): void
    {
        Storage::fake('public');

        [$alumni, $job, $employerUser] = $this->seedScenario();

        $this->actingAs($alumni)->post(route('alumni.jobs.apply', $job), [
            'cover_letter' => str_repeat('Experienced and eager to contribute. ', 3),
            'resume' => UploadedFile::fake()->create('resume.pdf', 120),
        ]);

        $application = JobApplication::query()->firstOrFail();

        $response = $this->actingAs($employerUser)->get(route('employer.applications.resume', $application));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_employer_cannot_view_resume_for_another_employers_application(): void
    {
        Storage::fake('public');

        [$alumni, $job] = $this->seedScenario();
        $otherEmployer = User::factory()->employer()->create();
        Employer::query()->create([
            'user_id' => $otherEmployer->id,
            'company_name' => 'Other Tech Corp',
            'is_verified' => true,
        ]);

        $this->actingAs($alumni)->post(route('alumni.jobs.apply', $job), [
            'cover_letter' => str_repeat('Experienced and eager to contribute. ', 3),
            'resume' => UploadedFile::fake()->create('resume.pdf', 120),
        ]);

        $application = JobApplication::query()->firstOrFail();

        $this->actingAs($otherEmployer)
            ->get(route('employer.applications.resume', $application))
            ->assertForbidden();
    }

    protected function seedScenario(): array
    {
        $industry = Industry::query()->create(['industry_name' => 'Information Technology', 'industry_code' => 'IT', 'is_active' => true]);
        $category = JobCategory::query()->create(['category_name' => 'Information Technology', 'category_code' => 'ITCAT', 'is_active' => true]);
        $college = \App\Models\College::query()->create(['college_name' => 'CCS', 'college_code' => 'CCS', 'is_active' => true]);
        $course = Course::query()->create(['college_id' => $college->id, 'course_code' => 'BSIT', 'course_name' => 'Bachelor of Science in Information Technology', 'duration' => 4, 'is_active' => true]);

        $employerUser = User::factory()->employer()->create();
        $employer = Employer::query()->create(['user_id' => $employerUser->id, 'company_name' => 'Tech Corp', 'industry_id' => $industry->id, 'is_verified' => true]);

        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true, 'email_verified_at' => now()]);
        AlumniProfile::query()->create([
            'user_id' => $alumni->id,
            'student_id' => '2026-00001',
            'first_name' => 'Al',
            'last_name' => 'Umni',
            'course_id' => $course->id,
            'skills' => ['Laravel', 'PHP'],
            'employment_status' => 'unemployed',
        ]);

        $job = Job::query()->create([
            'employer_id' => $employer->id,
            'job_category_id' => $category->id,
            'title' => 'Junior Laravel Developer',
            'slug' => 'junior-laravel-developer',
            'description' => 'Build applications.',
            'salary_type' => 'monthly',
            'job_type' => 'full-time',
            'experience_level' => 'entry',
            'skills_required' => ['Laravel', 'PHP'],
            'status' => 'approved',
            'application_deadline' => now()->addDays(20),
        ]);

        return [$alumni, $job, $employerUser];
    }
}
