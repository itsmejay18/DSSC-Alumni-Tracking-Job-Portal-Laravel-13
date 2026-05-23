<?php

namespace Tests\Feature;

use App\Models\AlumniProfile;
use App\Models\College;
use App\Models\Course;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_review_a_pending_job_with_applications(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $job = $this->jobWithApplication();

        $response = $this->actingAs($admin)->get(route('admin.jobs.show', $job));

        $response->assertOk();
        $response->assertSee('Job Review');
        $response->assertSee('Application for Junior Laravel Developer');
    }

    protected function jobWithApplication(): Job
    {
        $industry = Industry::query()->create([
            'industry_name' => 'Information Technology',
            'industry_code' => 'IT',
            'is_active' => true,
        ]);

        $category = JobCategory::query()->create([
            'category_name' => 'Software Development',
            'category_code' => 'SOFT',
            'is_active' => true,
        ]);

        $college = College::query()->create([
            'college_name' => 'College of Computing',
            'college_code' => 'CCS',
            'is_active' => true,
        ]);

        $course = Course::query()->create([
            'college_id' => $college->id,
            'course_code' => 'BSIT',
            'course_name' => 'Bachelor of Science in Information Technology',
            'duration' => 4,
            'is_active' => true,
        ]);

        $employerUser = User::factory()->employer()->create();
        $employer = Employer::query()->create([
            'user_id' => $employerUser->id,
            'company_name' => 'DSSC Career Partners',
            'industry_id' => $industry->id,
            'is_verified' => true,
        ]);

        $alumni = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        AlumniProfile::query()->create([
            'user_id' => $alumni->id,
            'student_id' => '2026-00001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'course_id' => $course->id,
            'employment_status' => 'unemployed',
        ]);

        $job = Job::query()->create([
            'employer_id' => $employer->id,
            'job_category_id' => $category->id,
            'title' => 'Junior Laravel Developer',
            'description' => 'Build and maintain Laravel applications for campus partners.',
            'salary_type' => 'monthly',
            'job_type' => 'full-time',
            'experience_level' => 'entry',
            'status' => 'pending',
        ]);

        JobApplication::query()->create([
            'job_id' => $job->id,
            'alumni_id' => $alumni->id,
            'cover_letter' => 'I am interested in this opportunity and ready to contribute.',
            'resume_path' => 'resumes/sample-resume.pdf',
            'status' => 'pending',
        ]);

        return $job;
    }
}
