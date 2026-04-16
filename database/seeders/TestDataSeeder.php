<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\AlumniProfile;
use App\Models\Employer;
use App\Models\EmploymentTracking;
use App\Models\Industry;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobMatch;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('en_PH');
        $courses = \App\Models\Course::query()->get();
        $industries = Industry::query()->get();
        $categories = JobCategory::query()->get();

        $skillPool = [
            'Laravel', 'PHP', 'MySQL', 'JavaScript', 'Vue', 'React', 'Python', 'Excel', 'Bookkeeping',
            'Networking', 'Cybersecurity', 'Teaching', 'Research', 'Data Analysis', 'Communication',
            'Project Management', 'SEO', 'Customer Service', 'Agritech', 'GIS', 'Canva', 'AutoCAD',
        ];

        $employers = collect();

        for ($index = 1; $index <= 20; $index++) {
            $user = User::factory()->employer()->create([
                'name' => $faker->company(),
                'email' => "employer{$index}@example.com",
                'password' => Hash::make('password'),
            ]);

            $employers->push(
                Employer::query()->create([
                    'user_id' => $user->id,
                    'company_name' => $faker->unique()->company(),
                    'company_registration_number' => 'DTI-'.str_pad((string) $index, 5, '0', STR_PAD_LEFT),
                    'industry_id' => $industries->random()->id,
                    'company_size' => collect(['1-10', '11-50', '51-200', '201-500', '500+'])->random(),
                    'website' => $faker->url(),
                    'phone' => $faker->phoneNumber(),
                    'address_line1' => $faker->streetAddress(),
                    'city' => $faker->city(),
                    'province' => 'Davao del Sur',
                    'country' => 'Philippines',
                    'postal_code' => $faker->postcode(),
                    'verification_documents' => ['permits/company-'.$index.'.pdf'],
                    'is_verified' => true,
                    'verification_date' => now()->subDays(rand(10, 100)),
                    'verified_by' => User::query()->admins()->value('id'),
                ])
            );
        }

        $alumniUsers = collect();

        for ($index = 1; $index <= 120; $index++) {
            $course = $courses->random();
            $employmentStatus = collect(['employed', 'employed', 'employed', 'unemployed', 'further_study', 'self-employed'])->random();
            $user = User::factory()->create([
                'name' => $faker->name(),
                'email' => "alumni{$index}@example.com",
                'role' => 'alumni',
                'is_approved' => true,
                'password' => Hash::make('password'),
            ]);

            AlumniProfile::query()->create([
                'user_id' => $user->id,
                'student_id' => '2026-'.str_pad((string) $index, 5, '0', STR_PAD_LEFT),
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'middle_name' => $faker->optional()->firstName(),
                'birth_date' => $faker->dateTimeBetween('-30 years', '-20 years'),
                'gender' => collect(['Male', 'Female', 'Non-binary'])->random(),
                'contact_number' => $faker->phoneNumber(),
                'course_id' => $course->id,
                'year_graduated' => rand(2018, now()->year),
                'graduation_date' => now()->subYears(rand(1, 8)),
                'skills' => collect($skillPool)->random(rand(4, 7))->values()->all(),
                'employment_status' => $employmentStatus,
                'current_job_title' => $employmentStatus === 'employed' ? $faker->jobTitle() : null,
                'current_company' => $employmentStatus === 'employed' ? $faker->company() : null,
                'current_salary' => $employmentStatus === 'employed' ? rand(18000, 65000) : null,
                'linkedin_url' => $faker->optional()->url(),
                'portfolio_url' => $faker->optional()->url(),
                'is_verified' => (bool) rand(0, 1),
                'verification_date' => now()->subDays(rand(1, 90)),
            ]);

            $alumniUsers->push($user);
        }

        $jobs = collect();

        for ($index = 1; $index <= 50; $index++) {
            $category = $categories->random();
            $title = $faker->jobTitle().' '.$index;

            $jobs->push(
                Job::query()->create([
                    'employer_id' => $employers->random()->id,
                    'job_category_id' => $category->id,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => $faker->paragraphs(3, true),
                    'requirements' => $faker->paragraphs(2, true),
                    'responsibilities' => $faker->paragraphs(2, true),
                    'qualifications' => $faker->paragraphs(2, true),
                    'salary_min' => rand(15000, 30000),
                    'salary_max' => rand(30001, 70000),
                    'salary_type' => collect(['monthly', 'yearly'])->random(),
                    'location' => $faker->city().', Davao del Sur',
                    'is_remote' => (bool) rand(0, 1),
                    'job_type' => collect(['full-time', 'part-time', 'contract', 'internship'])->random(),
                    'experience_level' => collect(['entry', 'junior', 'senior'])->random(),
                    'education_requirement' => $categories->random()->category_name,
                    'skills_required' => collect($skillPool)->random(rand(3, 6))->values()->all(),
                    'application_deadline' => now()->addDays(rand(7, 45)),
                    'max_applicants' => rand(20, 80),
                    'status' => collect(['approved', 'approved', 'approved', 'pending'])->random(),
                    'approved_by' => User::query()->admins()->value('id'),
                    'approved_at' => now()->subDays(rand(1, 10)),
                    'views_count' => rand(20, 250),
                    'applications_count' => 0,
                    'is_featured' => $index <= 8,
                ])
            );
        }

        $applicationCount = 0;

        foreach ($jobs as $job) {
            $applicants = $alumniUsers->random(rand(2, 6));

            foreach ($applicants as $alumni) {
                JobApplication::query()->firstOrCreate(
                    ['job_id' => $job->id, 'alumni_id' => $alumni->id],
                    [
                        'cover_letter' => $faker->paragraphs(2, true),
                        'resume_path' => 'resumes/sample-resume.pdf',
                        'portfolio_link' => $faker->optional()->url(),
                        'availability_date' => now()->addDays(rand(7, 30)),
                        'expected_salary' => rand(18000, 55000),
                        'status' => collect(['pending', 'shortlisted', 'interviewed', 'accepted', 'rejected'])->random(),
                        'status_updated_by' => $job->employer?->user_id,
                        'status_notes' => $faker->optional()->sentence(),
                        'reviewed_at' => now()->subDays(rand(0, 10)),
                    ]
                );

                JobMatch::query()->firstOrCreate(
                    ['job_id' => $job->id, 'alumni_id' => $alumni->id],
                    [
                        'match_score' => rand(45, 96),
                        'match_reasons' => ['skills' => ['matched' => collect($skillPool)->random(3)->values()->all()]],
                        'is_viewed' => (bool) rand(0, 1),
                        'is_applied' => true,
                        'created_at' => now()->subDays(rand(0, 20)),
                    ]
                );

                $applicationCount++;
            }

            $job->update(['applications_count' => $job->applications()->count()]);
        }

        $employedAlumni = $alumniUsers->filter(fn (User $user) => $user->alumniProfile?->employment_status === 'employed')->take(40);

        foreach ($employedAlumni as $alumni) {
            EmploymentTracking::query()->create([
                'alumni_id' => $alumni->id,
                'previous_status' => 'unemployed',
                'new_status' => 'employed',
                'new_company' => $alumni->alumniProfile?->current_company,
                'new_title' => $alumni->alumniProfile?->current_job_title,
                'changed_by' => $alumni->id,
                'change_date' => now()->subDays(rand(1, 60)),
                'notes' => 'Employment status updated from alumni profile form.',
            ]);

            Notification::query()->create([
                'user_id' => $alumni->id,
                'type' => 'job_matched',
                'title' => 'Recommended job available',
                'message' => 'A job aligned with your profile has been posted.',
                'data' => ['path' => '/alumni/jobs'],
                'is_read' => (bool) rand(0, 1),
                'sent_at' => now()->subDays(rand(0, 12)),
                'email_sent' => true,
            ]);
        }

        ActivityLog::query()->create([
            'user_id' => User::query()->admins()->value('id'),
            'action' => 'export',
            'module' => 'report',
            'description' => "Seeded demo data including {$applicationCount} applications.",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder',
            'created_at' => now(),
        ]);
    }
}
