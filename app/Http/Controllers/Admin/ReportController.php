<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateReportRequest;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    public function exportForm()
    {
        return view('admin.reports.export-form', [
            'courses' => \App\Models\Course::query()->where('is_active', true)->orderBy('course_name')->get(),
            'categories' => \App\Models\JobCategory::query()->where('is_active', true)->orderBy('category_name')->get(),
        ]);
    }

    public function generate(GenerateReportRequest $request)
    {
        $report = $this->reportService->generate(
            $request->string('report_type'),
            $request->user(),
            $request->safe()->except(['report_type', 'format']),
            $request->string('format')
        );

        return redirect()->route('admin.reports.download', $report)->with('success', 'Report generated successfully.');
    }

    public function employment()
    {
        return view('admin.reports.employment', [
            'reports' => Report::query()->where('report_type', 'employment_rate')->latest()->paginate(20),
        ]);
    }

    public function trends()
    {
        return view('admin.reports.trends', [
            'reports' => Report::query()->where('report_type', 'job_trends')->latest()->paginate(20),
        ]);
    }

    public function download(Report $report)
    {
        if (! $report->file_path) {
            $report->update(['file_path' => $this->reportService->generateFile($report)]);
        }

        return Storage::disk(config('settings.exports.disk', 'public'))->download($report->file_path);
    }
}
