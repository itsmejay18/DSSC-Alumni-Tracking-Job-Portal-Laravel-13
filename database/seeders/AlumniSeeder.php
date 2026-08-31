<?php

namespace Database\Seeders;

use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\User;
use App\Support\Portal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::query()->orderBy('id')->get();

        if ($courses->isEmpty()) {
            $this->command?->warn('AlumniSeeder skipped because no courses exist.');

            return;
        }

        $faker = fake('en_PH');
        $faker->seed(20260416);
        $password = Hash::make('password');
        $skillPool = [
            'Laravel', 'PHP', 'MySQL', 'JavaScript', 'Vue', 'React', 'Python', 'Excel',
            'Bookkeeping', 'Networking', 'Cybersecurity', 'Teaching', 'Research',
            'Data Analysis', 'Communication', 'Project Management', 'SEO',
            'Customer Service', 'Agritech', 'GIS', 'Canva', 'AutoCAD',
        ];

        for ($index = 1; $index <= 120; $index++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $middleName = $index % 3 === 0 ? null : $faker->firstName();
            $course = $courses[($index - 1) % $courses->count()];
            $employmentStatus = Portal::EMPLOYMENT_STATUSES[($index - 1) % count(Portal::EMPLOYMENT_STATUSES)];
            $yearGraduated = now()->year - (($index - 1) % 9);
            $isApproved = $index % 12 !== 0;
            $isActive = $index % 25 !== 0;
            $isVerified = $isApproved && $index % 5 !== 0;
            $isEmployed = $employmentStatus === 'employed';
            $isSelfEmployed = $employmentStatus === 'self-employed';
            $skills = $faker->randomElements($skillPool, 4 + ($index % 4));
            $createdAt = now()->subMonths(($index - 1) % 12)->subDays($index % 20);

            $user = User::withTrashed()->updateOrCreate(
                ['email' => "alumni{$index}@example.com"],
                [
                    'name' => "{$firstName} {$lastName}",
                    'role' => 'alumni',
                    'is_active' => $isActive,
                    'is_approved' => $isApproved,
                    'email_verified_at' => $isApproved ? $createdAt->copy()->addDay() : null,
                    'last_login_at' => $isActive ? now()->subDays($index % 30) : null,
                    'last_login_ip' => $isActive ? '192.168.10.'.(($index % 200) + 1) : null,
                    'password' => $password,
                ]
            );

            if ($user->trashed()) {
                $user->restore();
            }

            $user->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($index % 10),
            ])->saveQuietly();

            $profile = AlumniProfile::withTrashed()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => 'DSSC-'.str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_name' => $middleName,
                    'birth_date' => now()->subYears(21 + ($index % 12))->subDays($index * 3),
                    'gender' => $index % 2 === 0 ? 'Female' : 'Male',
                    'contact_number' => '09'.str_pad((string) (170000000 + $index), 9, '0', STR_PAD_LEFT),
                    'course_id' => $course->id,
                    'year_graduated' => $yearGraduated,
                    'graduation_date' => now()->setYear($yearGraduated)->setMonth(5)->setDay(30),
                    'skills' => array_values($skills),
                    'employment_status' => $employmentStatus,
                    'current_job_title' => match (true) {
                        $isEmployed => $faker->jobTitle(),
                        $isSelfEmployed => 'Owner and Manager',
                        default => null,
                    },
                    'current_company' => match (true) {
                        $isEmployed => $faker->company(),
                        $isSelfEmployed => "{$lastName} Enterprise",
                        default => null,
                    },
                    'current_salary' => ($isEmployed || $isSelfEmployed) ? 18000 + (($index % 12) * 3500) : null,
                    'linkedin_url' => "https://www.linkedin.com/in/demo-alumni-{$index}",
                    'portfolio_url' => $index % 3 === 0 ? "https://portfolio.example.com/alumni-{$index}" : null,
                    'is_verified' => $isVerified,
                    'verification_date' => $isVerified ? $createdAt->copy()->addDays(3) : null,
                ]
            );

            if ($profile->trashed()) {
                $profile->restore();
            }

            $profile->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($index % 10),
            ])->saveQuietly();
        }
    }
}
