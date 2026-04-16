<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['setting_key' => 'site_name', 'setting_value' => 'DSSC Alumni Tracking & Job Portal', 'setting_type' => 'string', 'group' => 'general', 'description' => 'Portal display name', 'is_public' => true],
            ['setting_key' => 'contact_email', 'setting_value' => 'careers@dssc.edu.ph', 'setting_type' => 'string', 'group' => 'general', 'description' => 'Public contact email', 'is_public' => true],
            ['setting_key' => 'matching_minimum_score', 'setting_value' => '45', 'setting_type' => 'integer', 'group' => 'matching', 'description' => 'Minimum score for notifications', 'is_public' => false],
            ['setting_key' => 'weekly_report_recipient', 'setting_value' => 'admin@alumniportal.com', 'setting_type' => 'string', 'group' => 'report', 'description' => 'Weekly report target', 'is_public' => false],
            ['setting_key' => 'mail_from_name', 'setting_value' => 'DSSC Alumni Portal', 'setting_type' => 'string', 'group' => 'email', 'description' => 'Outgoing mail name', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(['setting_key' => $setting['setting_key']], $setting);
        }
    }
}
