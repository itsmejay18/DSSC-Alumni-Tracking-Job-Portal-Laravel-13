<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $alumni = User::query()
            ->alumni()
            ->with(['jobMatches' => fn ($query) => $query->with('job')->orderByDesc('match_score'), 'jobApplications.job'])
            ->orderBy('id')
            ->get();

        foreach ($alumni as $offset => $alumnus) {
            $match = $alumnus->jobMatches->first();

            if ($match?->job) {
                $this->upsert(
                    $alumnus,
                    'job_matched',
                    "Recommended job: {$match->job->title}",
                    "{$match->job->title} is a {$match->match_score}% match for your profile.",
                    ['job_id' => $match->job_id, 'path' => "/alumni/jobs/{$match->job_id}"],
                    $offset,
                );
            }

            $application = $alumnus->jobApplications->sortByDesc('updated_at')->first();

            if ($application?->job && $application->status !== 'pending') {
                $this->upsert(
                    $alumnus,
                    'application_status',
                    "Application update: {$application->job->title}",
                    "Your application status is now {$application->status}.",
                    ['application_id' => $application->id, 'path' => '/alumni/applications'],
                    $offset + 2,
                );
            }
        }

        $employers = User::query()->employers()->with('employerProfile.jobs')->orderBy('id')->get();

        foreach ($employers as $offset => $employerUser) {
            $profile = $employerUser->employerProfile;

            if (! $profile) {
                continue;
            }

            if ($profile->is_verified) {
                $this->upsert(
                    $employerUser,
                    'employer_approved',
                    'Employer account approved',
                    'Your company account is verified and ready to publish job opportunities.',
                    ['path' => '/employer/dashboard'],
                    $offset + 1,
                );
            } else {
                $this->upsert(
                    $employerUser,
                    'system_alert',
                    'Verification is under review',
                    'Your submitted company documents are awaiting administrator review.',
                    ['path' => '/employer/profile/verification'],
                    $offset + 1,
                );
            }

            $expiringJob = $profile->jobs
                ->where('status', 'approved')
                ->sortBy('application_deadline')
                ->first();

            if ($expiringJob) {
                $this->upsert(
                    $employerUser,
                    'job_expiring',
                    "Job deadline reminder: {$expiringJob->title}",
                    "The application deadline is {$expiringJob->application_deadline?->format('M d, Y')}.",
                    ['job_id' => $expiringJob->id, 'path' => "/employer/jobs/{$expiringJob->id}"],
                    $offset + 3,
                );
            }
        }

        $admin = User::query()->admins()->first();

        if ($admin) {
            $this->upsert(
                $admin,
                'system_alert',
                'Demo portal data is ready',
                'All portal modules now contain representative seed data.',
                ['path' => '/admin/dashboard'],
                0,
            );
        }
    }

    private function upsert(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data,
        int $ageInDays,
    ): void {
        $sentAt = now()->subDays($ageInDays);
        $notification = Notification::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
            ],
            [
                'message' => $message,
                'data' => $data,
                'is_read' => $ageInDays % 3 === 0,
                'sent_at' => $sentAt,
                'email_sent' => $ageInDays % 2 === 0,
            ]
        );

        $notification->forceFill([
            'created_at' => $sentAt,
            'updated_at' => $sentAt,
        ])->saveQuietly();
    }
}
