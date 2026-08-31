<?php

namespace Database\Seeders;

use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::withTrashed()->updateOrCreate(
            ['email' => 'admin@alumniportal.com'],
            [
                'name' => 'Portal Administrator',
                'role' => 'admin',
                'is_active' => true,
                'is_approved' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
            ]
        );

        if ($admin->trashed()) {
            $admin->restore();
        }

        $course = Course::query()->where('course_code', 'BSIT')->first() ?? Course::query()->first();
        $industry = Industry::query()->where('industry_code', 'IT')->first() ?? Industry::query()->first();

        $alumni = User::withTrashed()->updateOrCreate(
            ['email' => 'alumni@alumniportal.com'],
            [
                'name' => 'Juan Dela Cruz',
                'role' => 'alumni',
                'is_active' => true,
                'is_approved' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
            ]
        );

        if ($alumni->trashed()) {
            $alumni->restore();
        }

        if ($course) {
            $profile = AlumniProfile::withTrashed()->updateOrCreate(
                ['user_id' => $alumni->id],
                [
                    'student_id' => '2026-00001',
                    'first_name' => 'Juan',
                    'last_name' => 'Dela Cruz',
                    'middle_name' => 'Santos',
                    'contact_number' => '09171234567',
                    'course_id' => $course->id,
                    'year_graduated' => now()->year - 1,
                    'graduation_date' => now()->subYear(),
                    'skills' => ['Laravel', 'PHP', 'MySQL', 'JavaScript'],
                    'employment_status' => 'employed',
                    'current_job_title' => 'Junior Web Developer',
                    'current_company' => 'DSSC Tech Partners',
                    'current_salary' => 22000,
                    'linkedin_url' => 'https://linkedin.com/in/juan-delacruz',
                    'portfolio_url' => 'https://portfolio.example.com/juan-delacruz',
                    'is_verified' => true,
                    'verification_date' => now(),
                ]
            );

            if ($profile->trashed()) {
                $profile->restore();
            }
        }

        $employer = User::withTrashed()->updateOrCreate(
            ['email' => 'employer@alumniportal.com'],
            [
                'name' => 'DSSC Hiring Team',
                'role' => 'employer',
                'is_active' => true,
                'is_approved' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
            ]
        );

        if ($employer->trashed()) {
            $employer->restore();
        }

        if ($industry) {
            $employerProfile = Employer::withTrashed()->updateOrCreate(
                ['user_id' => $employer->id],
                [
                    'company_name' => 'DSSC Career Partners Inc.',
                    'company_registration_number' => 'DTI-00001',
                    'industry_id' => $industry->id,
                    'company_size' => '11-50',
                    'website' => 'https://careerpartners.example.com',
                    'phone' => '09179876543',
                    'address_line1' => 'Campus Recruitment Office',
                    'city' => 'Digos City',
                    'province' => 'Davao del Sur',
                    'country' => 'Philippines',
                    'postal_code' => '8002',
                    'verification_documents' => ['permits/default-employer.pdf'],
                    'is_verified' => true,
                    'verification_date' => now(),
                    'verified_by' => $admin->id,
                ]
            );

            if ($employerProfile->trashed()) {
                $employerProfile->restore();
            }
        }
    }
}
