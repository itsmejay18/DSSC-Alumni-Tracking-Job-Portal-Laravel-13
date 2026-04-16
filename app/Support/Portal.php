<?php

namespace App\Support;

class Portal
{
    public const USER_ROLES = ['admin', 'alumni', 'employer', 'career_advisor'];

    public const EMPLOYMENT_STATUSES = [
        'employed',
        'unemployed',
        'self-employed',
        'further_study',
        'not_looking',
    ];

    public const COMPANY_SIZES = ['1-10', '11-50', '51-200', '201-500', '500+'];

    public const JOB_STATUSES = ['pending', 'approved', 'rejected', 'closed', 'expired'];

    public const JOB_TYPES = ['full-time', 'part-time', 'contract', 'freelance', 'internship'];

    public const EXPERIENCE_LEVELS = ['entry', 'junior', 'senior', 'lead'];

    public const SALARY_TYPES = ['monthly', 'yearly', 'hourly', 'project'];

    public const APPLICATION_STATUSES = [
        'pending',
        'shortlisted',
        'interviewed',
        'accepted',
        'rejected',
        'hired',
    ];

    public const REPORT_TYPES = [
        'employment_rate',
        'job_trends',
        'alumni_distribution',
        'employer_activity',
    ];

    public const REPORT_FORMATS = ['pdf', 'excel', 'json'];

    public const NOTIFICATION_TYPES = [
        'job_matched',
        'application_status',
        'employer_approved',
        'job_expiring',
        'system_alert',
    ];
}
