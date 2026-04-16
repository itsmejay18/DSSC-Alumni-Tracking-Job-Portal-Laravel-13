<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $reportId)
    {
    }

    public function handle(ReportService $reportService): void
    {
        $report = Report::query()->find($this->reportId);

        if (! $report) {
            return;
        }

        $report->update([
            'file_path' => $reportService->generateFile($report),
        ]);
    }
}
