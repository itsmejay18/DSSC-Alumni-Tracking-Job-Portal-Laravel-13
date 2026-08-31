<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use App\Support\Portal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $employers = Employer::query()
            ->where('is_verified', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true)->where('is_approved', true))
            ->orderBy('id')
            ->get();
        $categories = JobCategory::query()->orderBy('id')->get();
        $adminId = User::query()->admins()->value('id');

        if ($employers->isEmpty() || $categories->isEmpty() || ! $adminId) {
            $this->command?->warn('JobSeeder skipped because verified employers, categories, or an administrator are missing.');

            return;
        }

        $faker = fake('en_PH');
        $faker->seed(20260418);
        $titles = [
            'SOFTDEV' => ['Junior Laravel Developer', 'Frontend Developer', 'Mobile Application Developer'],
            'DATASCI' => ['Junior Data Analyst', 'Business Intelligence Associate', 'Data Support Specialist'],
            'NET' => ['Network Support Engineer', 'Systems Administrator', 'IT Support Specialist'],
            'EDU' => ['Learning Support Associate', 'Junior Curriculum Assistant', 'Training Coordinator'],
            'FINACC' => ['Accounting Associate', 'Payroll Assistant', 'Junior Finance Analyst'],
            'AGRIBIZ' => ['Agribusiness Field Officer', 'Farm Data Assistant', 'Agricultural Project Associate'],
        ];
        $skillsByCategory = [
            'SOFTDEV' => ['Laravel', 'PHP', 'MySQL', 'JavaScript', 'Git'],
            'DATASCI' => ['Python', 'Excel', 'Data Analysis', 'MySQL', 'Communication'],
            'NET' => ['Networking', 'Cybersecurity', 'Linux', 'Troubleshooting', 'Communication'],
            'EDU' => ['Teaching', 'Research', 'Communication', 'Canva', 'Project Management'],
            'FINACC' => ['Bookkeeping', 'Excel', 'Data Analysis', 'Communication', 'Customer Service'],
            'AGRIBIZ' => ['Agritech', 'GIS', 'Excel', 'Project Management', 'Communication'],
        ];

        for ($index = 1; $index <= 50; $index++) {
            $category = $categories[($index - 1) % $categories->count()];
            $categoryTitles = $titles[$category->category_code] ?? ['Career Opportunity'];
            $title = $categoryTitles[($index - 1) % count($categoryTitles)]." {$index}";
            $slug = Str::slug($title);
            $status = $this->statusFor($index);
            $salaryType = Portal::SALARY_TYPES[($index - 1) % count(Portal::SALARY_TYPES)];
            [$salaryMin, $salaryMax] = $this->salaryRange($salaryType, $index);
            $createdAt = now()->subMonths(($index - 1) % 12)->subDays($index % 18);
            $wasApproved = in_array($status, ['approved', 'closed', 'expired'], true);
            $deadline = $status === 'expired'
                ? now()->subDays(1 + ($index % 30))
                : now()->addDays(10 + ($index % 45));

            $job = Job::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                [
                    'employer_id' => $employers[($index - 1) % $employers->count()]->id,
                    'job_category_id' => $category->id,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => $faker->paragraphs(3, true),
                    'requirements' => $faker->paragraphs(2, true),
                    'responsibilities' => $faker->paragraphs(2, true),
                    'qualifications' => "Bachelor's degree in a related field. Fresh graduates are welcome to apply.",
                    'salary_min' => $salaryMin,
                    'salary_max' => $salaryMax,
                    'salary_type' => $salaryType,
                    'location' => ['Digos City', 'Davao City', 'Bansalan', 'Remote', 'General Santos City'][($index - 1) % 5],
                    'is_remote' => $index % 5 === 4,
                    'job_type' => Portal::JOB_TYPES[($index - 1) % count(Portal::JOB_TYPES)],
                    'experience_level' => Portal::EXPERIENCE_LEVELS[($index - 1) % count(Portal::EXPERIENCE_LEVELS)],
                    'education_requirement' => "Bachelor's degree or equivalent experience",
                    'skills_required' => $skillsByCategory[$category->category_code] ?? ['Communication', 'Project Management'],
                    'application_deadline' => $deadline,
                    'max_applicants' => 20 + (($index % 7) * 10),
                    'status' => $status,
                    'approved_by' => $wasApproved ? $adminId : null,
                    'approved_at' => $wasApproved ? $createdAt->copy()->addDays(2) : null,
                    'views_count' => 25 + ($index * 9),
                    'applications_count' => 0,
                    'is_featured' => $status === 'approved' && $index <= 10,
                ]
            );

            if ($job->trashed()) {
                $job->restore();
            }

            $job->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($index % 12),
            ])->saveQuietly();
        }
    }

    private function statusFor(int $index): string
    {
        return match (($index - 1) % 10) {
            5 => 'pending',
            6 => 'rejected',
            7 => 'closed',
            8 => 'expired',
            default => 'approved',
        };
    }

    /** @return array{0: int, 1: int} */
    private function salaryRange(string $salaryType, int $index): array
    {
        return match ($salaryType) {
            'yearly' => [300000 + ($index * 5000), 480000 + ($index * 6000)],
            'hourly' => [150 + ($index * 2), 300 + ($index * 3)],
            'project' => [25000 + ($index * 500), 70000 + ($index * 1000)],
            default => [18000 + ($index * 250), 35000 + ($index * 400)],
        };
    }
}
