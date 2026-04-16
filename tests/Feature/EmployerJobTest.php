<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\Industry;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_create_a_job(): void
    {
        $industry = Industry::query()->create(['industry_name' => 'IT', 'industry_code' => 'IT', 'is_active' => true]);
        $category = JobCategory::query()->create(['category_name' => 'Software Development', 'category_code' => 'SOFT', 'is_active' => true]);
        $user = User::factory()->employer()->create();
        $employer = Employer::query()->create(['user_id' => $user->id, 'company_name' => 'Acme Inc', 'industry_id' => $industry->id, 'is_verified' => true]);

        $response = $this->actingAs($user)->post(route('employer.jobs.store'), [
            'job_category_id' => $category->id,
            'title' => 'Backend Developer',
            'description' => str_repeat('Build and maintain systems. ', 4),
            'requirements' => 'PHP experience',
            'salary_type' => 'monthly',
            'location' => 'Digos City',
            'job_type' => 'full-time',
            'experience_level' => 'junior',
            'application_deadline' => now()->addDays(15)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'employer_id' => $employer->id,
            'title' => 'Backend Developer',
            'status' => 'pending',
        ]);
    }
}
