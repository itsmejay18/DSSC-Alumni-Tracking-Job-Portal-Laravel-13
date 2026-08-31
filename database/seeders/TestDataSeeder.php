<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Seed all transactional demo data after the reference tables and core
     * login accounts have been created by DatabaseSeeder.
     */
    public function run(): void
    {
        $this->call([
            AlumniSeeder::class,
            EmployerSeeder::class,
            JobSeeder::class,
            JobApplicationSeeder::class,
            JobMatchSeeder::class,
            EmploymentTrackingSeeder::class,
            NotificationSeeder::class,
            ReportSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
