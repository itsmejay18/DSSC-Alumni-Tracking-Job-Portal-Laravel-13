<?php

namespace App\Services;

use App\Models\AlumniProfile;
use App\Models\Job;
use App\Models\JobMatch;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MatchingService
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function recalculateAll(): int
    {
        $count = 0;
        $jobs = Job::query()->approved()->open()->with(['jobCategory', 'employer'])->get();
        $alumni = User::query()->alumni()->approved()->with(['alumniProfile.course'])->get();

        foreach ($jobs as $job) {
            $count += $this->calculateForJob($job, $alumni);
        }

        return $count;
    }

    public function calculateForJob(Job $job, ?Collection $alumniCollection = null): int
    {
        $alumniCollection ??= User::query()->alumni()->approved()->with(['alumniProfile.course'])->get();
        $processed = 0;

        foreach ($alumniCollection as $alumni) {
            if ($this->createOrUpdateMatch($alumni, $job)) {
                $processed++;
            }
        }

        return $processed;
    }

    public function calculateForAlumni(User $alumni, ?Collection $jobsCollection = null): int
    {
        $jobsCollection ??= Job::query()->approved()->open()->with(['jobCategory', 'employer'])->get();
        $processed = 0;

        foreach ($jobsCollection as $job) {
            if ($this->createOrUpdateMatch($alumni, $job)) {
                $processed++;
            }
        }

        return $processed;
    }

    public function createOrUpdateMatch(User $alumni, Job $job): ?JobMatch
    {
        $profile = $alumni->alumniProfile;

        if (! $profile) {
            return null;
        }

        [$score, $reasons] = $this->calculateScore($profile, $job);

        if ($score <= 0) {
            return null;
        }

        $existing = JobMatch::query()
            ->where('job_id', $job->id)
            ->where('alumni_id', $alumni->id)
            ->first();

        $shouldNotify = ! $existing && $score >= config('settings.matching.minimum_score', 45);

        if ($existing) {
            $shouldNotify = $existing->match_score < config('settings.matching.minimum_score', 45)
                && $score >= config('settings.matching.minimum_score', 45);

            $existing->update([
                'match_score' => $score,
                'match_reasons' => $reasons,
                'is_applied' => $job->applications()->where('alumni_id', $alumni->id)->exists(),
            ]);

            $match = $existing->fresh();
        } else {
            $match = JobMatch::query()->create([
                'job_id' => $job->id,
                'alumni_id' => $alumni->id,
                'match_score' => $score,
                'match_reasons' => $reasons,
                'is_applied' => $job->applications()->where('alumni_id', $alumni->id)->exists(),
                'created_at' => now(),
            ]);
        }

        if ($shouldNotify) {
            $this->notificationService->sendJobMatchAlert($alumni, $match);
        }

        return $match;
    }

    public function calculateScore(AlumniProfile $profile, Job $job): array
    {
        $skillResult = $this->calculateSkillScore($profile, $job);
        $courseResult = $this->calculateCourseScore($profile, $job);
        $experienceResult = $this->calculateExperienceScore($profile, $job);

        $score = round($skillResult['score'] + $courseResult['score'] + $experienceResult['score'], 2);

        return [
            min(100, $score),
            [
                'skills' => $skillResult,
                'course' => $courseResult,
                'experience' => $experienceResult,
            ],
        ];
    }

    protected function calculateSkillScore(AlumniProfile $profile, Job $job): array
    {
        $alumniSkills = $this->normalizeSkills($profile->skills ?? []);
        $jobSkills = $this->normalizeSkills($job->skills_required ?? []);

        if (blank($jobSkills)) {
            return ['score' => 0, 'matched' => [], 'weight' => 60];
        }

        $matched = array_values(array_intersect($alumniSkills, $jobSkills));
        $score = (count($matched) / max(count($jobSkills), 1)) * config('settings.matching.skill_weight', 60);

        return [
            'score' => round($score, 2),
            'matched' => $matched,
            'weight' => config('settings.matching.skill_weight', 60),
        ];
    }

    protected function calculateCourseScore(AlumniProfile $profile, Job $job): array
    {
        $courseText = Str::lower(trim(($profile->course?->course_code ?? '').' '.($profile->course?->course_name ?? '')));
        $categoryText = Str::lower(trim(($job->jobCategory?->category_code ?? '').' '.($job->jobCategory?->category_name ?? '').' '.($job->jobCategory?->description ?? '')));

        $keywords = collect(explode(' ', preg_replace('/[^a-z0-9 ]/i', ' ', $categoryText)))
            ->filter(fn ($value) => strlen($value) >= 4)
            ->unique()
            ->values()
            ->all();

        $matched = collect($keywords)->filter(fn ($keyword) => Str::contains($courseText, $keyword))->values()->all();
        $score = count($matched) > 0 ? config('settings.matching.course_weight', 30) : 0;

        return [
            'score' => $score,
            'matched_keywords' => $matched,
            'weight' => config('settings.matching.course_weight', 30),
        ];
    }

    protected function calculateExperienceScore(AlumniProfile $profile, Job $job): array
    {
        $yearsSinceGraduation = $profile->year_graduated ? max(now()->year - $profile->year_graduated, 0) : 0;

        $score = match ($job->experience_level) {
            'entry' => 10,
            'junior' => $yearsSinceGraduation >= 1 ? 10 : 6,
            'senior' => $yearsSinceGraduation >= 3 ? 10 : 4,
            'lead' => $yearsSinceGraduation >= 5 ? 10 : 2,
            default => 0,
        };

        return [
            'score' => $score,
            'years_since_graduation' => $yearsSinceGraduation,
            'weight' => config('settings.matching.experience_weight', 10),
        ];
    }

    protected function normalizeSkills(array $skills): array
    {
        return collect($skills)
            ->map(function ($skill) {
                if (is_array($skill)) {
                    $skill = $skill['name'] ?? '';
                }

                return Str::lower(trim((string) $skill));
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
