<?php

namespace Tests\Unit;

use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use App\Services\MatchingService;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_matching_service_combines_skills_course_and_experience(): void
    {
        $college = \App\Models\College::query()->create(['college_name' => 'CCS', 'college_code' => 'CCS', 'is_active' => true]);
        $course = Course::query()->create(['college_id' => $college->id, 'course_code' => 'BSIT', 'course_name' => 'Bachelor of Science in Information Technology', 'duration' => 4, 'is_active' => true]);
        $industry = Industry::query()->create(['industry_name' => 'Information Technology', 'industry_code' => 'IT', 'is_active' => true]);
        $category = JobCategory::query()->create(['category_name' => 'Information Technology', 'category_code' => 'ITJOB', 'description' => 'Information technology roles', 'is_active' => true]);

        $employerUser = User::factory()->employer()->create();
        $employer = Employer::query()->create(['user_id' => $employerUser->id, 'company_name' => 'Tech Corp', 'industry_id' => $industry->id, 'is_verified' => true]);
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true, 'email_verified_at' => now()]);

        $profile = AlumniProfile::query()->create([
            'user_id' => $alumni->id,
            'student_id' => '2026-00001',
            'first_name' => 'A',
            'last_name' => 'B',
            'course_id' => $course->id,
            'year_graduated' => now()->year - 2,
            'skills' => ['Laravel', 'PHP'],
            'employment_status' => 'unemployed',
        ]);

        $job = Job::query()->create([
            'employer_id' => $employer->id,
            'job_category_id' => $category->id,
            'title' => 'Junior Laravel Developer',
            'slug' => 'junior-laravel-developer',
            'description' => 'Build web applications',
            'salary_type' => 'monthly',
            'job_type' => 'full-time',
            'experience_level' => 'junior',
            'skills_required' => ['Laravel', 'PHP'],
            'status' => 'approved',
        ]);

        $service = new MatchingService(app(NotificationService::class));
        [$score] = $service->calculateScore($profile, $job);

        $this->assertGreaterThanOrEqual(90, $score);
    }
}
