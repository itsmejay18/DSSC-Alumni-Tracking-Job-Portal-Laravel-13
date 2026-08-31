<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Support\Portal;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::query()
            ->whereIn('status', ['approved', 'closed', 'expired'])
            ->with('employer')
            ->orderBy('id')
            ->get();
        $alumni = User::query()
            ->alumni()
            ->approved()
            ->where('is_active', true)
            ->whereHas('alumniProfile')
            ->orderBy('id')
            ->get();

        if ($jobs->isEmpty() || $alumni->isEmpty()) {
            $this->command?->warn('JobApplicationSeeder skipped because eligible jobs or alumni are missing.');

            return;
        }

        $faker = fake('en_PH');
        $faker->seed(20260419);

        foreach ($jobs as $jobOffset => $job) {
            $applicantCount = 2 + ($jobOffset % 5);

            for ($offset = 0; $offset < $applicantCount; $offset++) {
                $alumnus = $alumni[(($jobOffset * 7) + $offset) % $alumni->count()];
                $status = Portal::APPLICATION_STATUSES[($jobOffset + $offset) % count(Portal::APPLICATION_STATUSES)];
                $candidateCreatedAt = $job->created_at->copy()->addDays($offset + 2);
                $createdAt = $candidateCreatedAt->isFuture()
                    ? now()->subDays($offset + 1)
                    : $candidateCreatedAt;
                $wasReviewed = $status !== 'pending';

                $application = JobApplication::query()->updateOrCreate(
                    [
                        'job_id' => $job->id,
                        'alumni_id' => $alumnus->id,
                    ],
                    [
                        'cover_letter' => $faker->paragraphs(2, true),
                        'resume_path' => "resumes/demo-alumni-{$alumnus->id}.pdf",
                        'portfolio_link' => $offset % 2 === 0 ? "https://portfolio.example.com/user-{$alumnus->id}" : null,
                        'availability_date' => now()->addDays(7 + (($jobOffset + $offset) % 30)),
                        'expected_salary' => 18000 + ((($jobOffset + $offset) % 15) * 2500),
                        'status' => $status,
                        'status_updated_by' => $wasReviewed ? $job->employer?->user_id : null,
                        'status_notes' => $wasReviewed ? "Demo application moved to {$status}." : null,
                        'reviewed_at' => $wasReviewed ? $createdAt->copy()->addDays(2) : null,
                    ]
                );

                $application->forceFill([
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addDays($wasReviewed ? 2 : 0),
                ])->saveQuietly();
            }
        }

        Job::query()->each(function (Job $job): void {
            $job->updateQuietly([
                'applications_count' => $job->applications()->count(),
            ]);
        });
    }
}
