<?php

namespace Database\Seeders;

use App\Models\AlumniProfile;
use App\Models\EmploymentTracking;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmploymentTrackingSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->admins()->value('id');
        $profiles = AlumniProfile::query()->with('user')->orderBy('id')->get();

        foreach ($profiles as $offset => $profile) {
            if (! $profile->user) {
                continue;
            }

            [$previousStatus, $previousCompany, $previousTitle] = match ($profile->employment_status) {
                'employed' => ['unemployed', null, null],
                'self-employed' => ['employed', 'Previous Demo Employer', 'Project Assistant'],
                'further_study' => ['unemployed', null, null],
                'not_looking' => ['employed', 'Previous Demo Employer', 'Staff Member'],
                default => ['employed', 'Previous Demo Employer', 'Junior Associate'],
            };

            EmploymentTracking::query()->updateOrCreate(
                [
                    'alumni_id' => $profile->user_id,
                    'notes' => 'Demo employment history entry.',
                ],
                [
                    'previous_status' => $previousStatus,
                    'new_status' => $profile->employment_status,
                    'previous_company' => $previousCompany,
                    'new_company' => $profile->current_company,
                    'previous_title' => $previousTitle,
                    'new_title' => $profile->current_job_title,
                    'changed_by' => $offset % 4 === 0 ? $adminId : $profile->user_id,
                    'change_date' => now()->subMonths($offset % 12)->subDays($offset % 20),
                ]
            );
        }
    }
}
