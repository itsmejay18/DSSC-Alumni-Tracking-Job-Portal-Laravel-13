# DSSC Alumni Tracking & Job Portal

Production-ready Laravel 12 + MySQL alumni tracing and job portal for Davao del Sur State College. The system supports alumni profiling, employer verification, job posting, application management, scheduled matching, report generation, and CHED-friendly employment analytics.

## Highlights

- Role-based dashboards for `admin`, `alumni`, and `employer`
- Smart job matching with `60% skills + 30% course + 10% experience`
- Alumni employment tracking with history logs
- Employer verification and admin approval workflow
- JSON / PDF / Excel report generation with queued large exports
- Laravel Sanctum API endpoints for future mobile use
- Queue, scheduler, backup config, health endpoint, and activity auditing
- UI aligned to the DSSC kit in `public/uikit/`

## Architecture

- ERD document: [docs/ERD.md](docs/ERD.md)
- Core services:
  - `MatchingService`
  - `ReportService`
  - `NotificationService`
  - `SearchService`
- Queue jobs:
  - `ExportReportJob`
  - `SendBulkNotificationJob`
- Scheduled commands:
  - `portal:calculate-job-matches`
  - `portal:close-expired-jobs`
  - `portal:send-weekly-report`
  - `portal:archive-notifications`

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan queue:work --daemon
php artisan schedule:work
php artisan serve
```

Default admin login:

- Email: `admin@alumniportal.com`
- Password: `password`

## Key Routes

- Web home: `/`
- Health check: `/health`
- Admin dashboard: `/admin/dashboard`
- Alumni dashboard: `/alumni/dashboard`
- Employer dashboard: `/employer/dashboard`
- API base: `/api/v1`

## Scheduler

- Hourly: notify employers about jobs expiring within 7 days
- Daily 2:00 AM: close expired jobs
- Daily 3:00 AM: calculate matches
- Monday 8:00 AM: send weekly admin report
- Monthly: archive notifications older than 30 days

## Testing

```bash
php artisan test
```

Included tests cover:

- alumni job application flow
- employer job posting flow
- admin report generation
- matching algorithm scoring

## Storage

Run `php artisan storage:link` so these directories are publicly available:

- `storage/app/public/resumes`
- `storage/app/public/logos`
- `storage/app/public/exports`

## Notes

- Queue jobs use the `queued_jobs` table so the domain `jobs` table can stay dedicated to job postings.
- The interface loads the DSSC UI kit from `public/uikit/` and extends it with portal-specific CSS and JS.
- In production, HTTPS is forced automatically from `AppServiceProvider`.
