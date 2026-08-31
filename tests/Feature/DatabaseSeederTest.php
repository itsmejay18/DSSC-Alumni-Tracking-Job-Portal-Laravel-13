<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\AlumniProfile;
use App\Models\Employer;
use App\Models\EmploymentTracking;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobMatch;
use App\Models\Notification;
use App\Models\Report;
use App\Models\Setting;
use App\Models\User;
use App\Support\Portal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_every_portal_data_domain(): void
    {
        $this->seed();

        $this->assertSame(143, User::query()->count());
        $this->assertSame(121, AlumniProfile::query()->count());
        $this->assertSame(21, Employer::query()->count());
        $this->assertSame(50, Job::query()->count());
        $this->assertGreaterThan(0, JobApplication::query()->count());
        $this->assertGreaterThan(0, JobMatch::query()->count());
        $this->assertSame(121, EmploymentTracking::query()->count());
        $this->assertSame(count(Portal::REPORT_TYPES), Report::query()->count());
        $this->assertGreaterThan(0, Notification::query()->count());
        $this->assertGreaterThan(0, ActivityLog::query()->count());
        $this->assertGreaterThan(0, Setting::query()->count());

        foreach (Portal::JOB_STATUSES as $status) {
            $this->assertDatabaseHas('jobs', ['status' => $status]);
        }

        foreach (Portal::APPLICATION_STATUSES as $status) {
            $this->assertDatabaseHas('job_applications', ['status' => $status]);
        }

        foreach (Portal::NOTIFICATION_TYPES as $type) {
            $this->assertDatabaseHas('notifications', ['type' => $type]);
        }

        foreach (Portal::REPORT_TYPES as $type) {
            $this->assertDatabaseHas('reports', ['report_type' => $type]);
        }
    }
}
