<?php

namespace Tests\Feature;

use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\CollegeSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\IndustrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_lists_seeded_courses(): void
    {
        $this->withoutVite();
        $this->seedReferenceData();

        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('Bachelor of Science in Information Technology');
    }

    public function test_alumni_can_register_with_a_seeded_course(): void
    {
        $this->seedReferenceData();

        $course = Course::query()->where('course_code', 'BSIT')->firstOrFail();

        $response = $this->post(route('register'), [
            'role' => 'alumni',
            'name' => 'Jay Abarbon',
            'email' => 'jay.abarbon@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'student_id' => '2023-20097',
            'course_id' => $course->id,
            'first_name' => 'Jay',
            'last_name' => 'Abarbon',
            'middle_name' => 'J.',
            'year_graduated' => '2027',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas(User::class, [
            'email' => 'jay.abarbon@example.com',
            'role' => 'alumni',
        ]);

        $this->assertDatabaseHas(AlumniProfile::class, [
            'student_id' => '2023-20097',
            'course_id' => $course->id,
        ]);
    }

    protected function seedReferenceData(): void
    {
        $this->seed([
            CollegeSeeder::class,
            CourseSeeder::class,
            IndustrySeeder::class,
        ]);
    }
}
