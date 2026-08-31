<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->admins()->first();
        $alumni = User::query()->alumni()->first();
        $employer = User::query()->employers()->first();
        $entries = [
            [$admin, 'login', 'authentication', 'Administrator signed in to the portal.', null, null],
            [$admin, 'approve', 'employer', 'Approved a demo employer account.', ['is_verified' => false], ['is_verified' => true]],
            [$admin, 'approve', 'job', 'Approved a demo job posting.', ['status' => 'pending'], ['status' => 'approved']],
            [$admin, 'export', 'report', 'Generated an employment rate report.', null, ['format' => 'pdf']],
            [$admin, 'update', 'settings', 'Updated portal matching settings.', ['minimum_score' => 40], ['minimum_score' => 45]],
            [$alumni, 'update', 'profile', 'Updated alumni employment information.', ['employment_status' => 'unemployed'], ['employment_status' => 'employed']],
            [$alumni, 'create', 'application', 'Submitted a demo job application.', null, ['status' => 'pending']],
            [$employer, 'create', 'job', 'Created a demo job posting.', null, ['status' => 'pending']],
            [$employer, 'update', 'application', 'Shortlisted a demo applicant.', ['status' => 'pending'], ['status' => 'shortlisted']],
            [null, 'calculate', 'matching', 'Scheduled job matching completed.', null, ['matches_created' => 50]],
        ];

        foreach ($entries as $offset => [$user, $action, $module, $description, $oldData, $newData]) {
            ActivityLog::query()->updateOrCreate(
                [
                    'user_id' => $user?->id,
                    'action' => $action,
                    'module' => $module,
                    'description' => $description,
                ],
                [
                    'ip_address' => $user ? '127.0.0.1' : null,
                    'user_agent' => 'Database Seeder',
                    'old_data' => $oldData,
                    'new_data' => $newData,
                    'created_at' => now()->subHours($offset * 4),
                ]
            );
        }
    }
}
