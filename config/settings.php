<?php

return [
    'brand' => [
        'name' => env('PORTAL_BRAND_NAME', 'DSSC Alumni Tracking & Job Portal'),
        'short_name' => env('PORTAL_BRAND_SHORT_NAME', 'DSSC Alumni Portal'),
        'school_name' => env('PORTAL_SCHOOL_NAME', 'Davao del Sur State College'),
        'logo' => env('PORTAL_LOGO', '/uikit/assets/images/dssc-logo-circle.png'),
    ],

    'pagination' => [
        'per_page' => (int) env('PORTAL_PER_PAGE', 20),
    ],

    'matching' => [
        'minimum_score' => (int) env('MATCHING_MINIMUM_SCORE', 45),
        'skill_weight' => 60,
        'course_weight' => 30,
        'experience_weight' => 10,
    ],

    'exports' => [
        'queue_threshold' => (int) env('EXPORT_QUEUE_THRESHOLD', 200),
        'disk' => env('EXPORT_DISK', 'public'),
        'path' => env('EXPORT_PATH', 'exports'),
    ],

    'notifications' => [
        'archive_after_days' => (int) env('NOTIFICATION_ARCHIVE_DAYS', 30),
        'weekly_summary_email' => env('WEEKLY_SUMMARY_EMAIL', 'admin@alumniportal.com'),
    ],
];
