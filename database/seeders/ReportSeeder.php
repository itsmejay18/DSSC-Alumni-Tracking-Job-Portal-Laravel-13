<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use App\Support\Portal;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->admins()->first();

        if (! $admin) {
            $this->command?->warn('ReportSeeder skipped because no administrator exists.');

            return;
        }

        $reportService = app(ReportService::class);

        foreach (Portal::REPORT_TYPES as $offset => $reportType) {
            $generatedAt = now()->subDays($offset * 7);
            $parameters = $reportType === 'employment_rate' ? ['year' => now()->year] : [];
            $report = Report::query()->updateOrCreate(
                [
                    'report_type' => $reportType,
                    'generated_by' => $admin->id,
                ],
                [
                    'parameters' => $parameters,
                    'result_data' => $reportService->buildData($reportType, $parameters),
                    'file_path' => null,
                    'format' => Portal::REPORT_FORMATS[$offset % count(Portal::REPORT_FORMATS)],
                    'generated_at' => $generatedAt,
                ]
            );

            $report->forceFill([
                'created_at' => $generatedAt,
                'updated_at' => $generatedAt,
            ])->saveQuietly();
        }
    }
}
