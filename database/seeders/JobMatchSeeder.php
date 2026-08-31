<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobMatch;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobMatchSeeder extends Seeder
{
    public function run(): void
    {
        $applications = JobApplication::query()
            ->with(['job.jobCategory', 'alumni.alumniProfile'])
            ->orderBy('id')
            ->get();

        foreach ($applications as $offset => $application) {
            if (! $application->job || ! $application->alumni?->alumniProfile) {
                continue;
            }

            $this->seedMatch(
                $application->job,
                $application->alumni,
                68 + ($offset % 30),
                true,
                1 + ($offset % 45),
            );
        }

        $jobs = Job::query()->approved()->open()->with('jobCategory')->orderBy('id')->get();
        $alumni = User::query()
            ->alumni()
            ->approved()
            ->where('is_active', true)
            ->whereHas('alumniProfile')
            ->with('alumniProfile')
            ->orderBy('id')
            ->get();

        if ($jobs->isEmpty() || $alumni->isEmpty()) {
            return;
        }

        foreach ($jobs as $jobOffset => $job) {
            for ($offset = 0; $offset < 6; $offset++) {
                $alumnus = $alumni[(($jobOffset * 11) + $offset) % $alumni->count()];
                $isApplied = $job->applications()->where('alumni_id', $alumnus->id)->exists();

                $this->seedMatch(
                    $job,
                    $alumnus,
                    45 + (($jobOffset * 7 + $offset * 5) % 51),
                    $isApplied,
                    1 + (($jobOffset + $offset) % 30),
                );
            }
        }
    }

    private function seedMatch(Job $job, User $alumnus, int $score, bool $isApplied, int $ageInDays): void
    {
        $jobSkills = collect($job->skills_required ?? []);
        $alumniSkills = collect($alumnus->alumniProfile?->skills ?? []);
        $matchedSkills = $jobSkills
            ->filter(fn ($skill) => $alumniSkills->contains(fn ($alumniSkill) => strcasecmp((string) $alumniSkill, (string) $skill) === 0))
            ->values();

        if ($matchedSkills->isEmpty()) {
            $matchedSkills = $jobSkills->take(2)->values();
        }

        JobMatch::query()->updateOrCreate(
            [
                'job_id' => $job->id,
                'alumni_id' => $alumnus->id,
            ],
            [
                'match_score' => min($score, 100),
                'match_reasons' => [
                    'skills' => [
                        'score' => min(60, max(20, $score - 35)),
                        'matched' => $matchedSkills->all(),
                        'weight' => 60,
                    ],
                    'course' => [
                        'score' => $score >= 65 ? 30 : 15,
                        'matched_keywords' => [$job->jobCategory?->category_code],
                        'weight' => 30,
                    ],
                    'experience' => [
                        'score' => $score >= 80 ? 10 : 6,
                        'years_since_graduation' => max(now()->year - (int) $alumnus->alumniProfile?->year_graduated, 0),
                        'weight' => 10,
                    ],
                ],
                'is_viewed' => $score % 2 === 0,
                'is_applied' => $isApplied,
                'created_at' => now()->subDays($ageInDays),
            ]
        );
    }
}
