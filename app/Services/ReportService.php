<?php

namespace App\Services;

use App\Jobs\ExportReportJob;
use App\Models\Employer;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Report;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ReportService
{
    public function generate(string $type, User $generatedBy, array $parameters = [], string $format = 'json'): Report
    {
        // CHED ACCREDITATION: Reports are structured for graduate outcome and employment summaries.
        $data = $this->buildData($type, $parameters);

        $report = Report::query()->create([
            'report_type' => $type,
            'generated_by' => $generatedBy->id,
            'parameters' => $parameters,
            'result_data' => $data,
            'format' => $format,
            'generated_at' => now(),
        ]);

        $rows = $this->flattenRows($data);

        if ($format !== 'json' && count($rows) >= config('settings.exports.queue_threshold', 200)) {
            ExportReportJob::dispatch($report->id);

            return $report;
        }

        $report->update(['file_path' => $this->generateFile($report)]);

        return $report->fresh();
    }

    public function generateFile(Report $report): string
    {
        $disk = config('settings.exports.disk', 'public');
        $basePath = trim(config('settings.exports.path', 'exports'), '/');

        if ($report->format === 'json') {
            $path = "{$basePath}/report-{$report->id}.json";
            Storage::disk($disk)->put($path, json_encode($report->result_data, JSON_PRETTY_PRINT));

            return $path;
        }

        if ($report->format === 'pdf') {
            $path = "{$basePath}/report-{$report->id}.pdf";
            $output = Pdf::loadView('reports.pdf.generic', ['report' => $report])->output();
            Storage::disk($disk)->put($path, $output);

            return $path;
        }

        $path = "{$basePath}/report-{$report->id}.xlsx";
        $rows = $this->flattenRows($report->result_data ?? []);
        $headings = array_keys($rows[0] ?? ['label' => '', 'value' => '']);

        Excel::store(
            new class($rows, $headings) implements FromArray, WithHeadings, ShouldAutoSize {
                public function __construct(protected array $rows, protected array $headings)
                {
                }

                public function array(): array
                {
                    return $this->rows;
                }

                public function headings(): array
                {
                    return $this->headings;
                }
            },
            $path,
            $disk
        );

        return $path;
    }

    public function buildData(string $type, array $parameters = []): array
    {
        return match ($type) {
            'employment_rate' => $this->employmentRate($parameters),
            'job_trends' => $this->jobTrends(),
            'alumni_distribution' => $this->alumniDistribution(),
            'employer_activity' => $this->employerActivity(),
            default => [],
        };
    }

    protected function employmentRate(array $parameters): array
    {
        $year = $parameters['year'] ?? now()->year;
        $alumni = User::query()->alumni()->with('alumniProfile')->get();
        $employed = $alumni->filter(fn (User $user) => $user->alumniProfile?->employment_status === 'employed')->count();

        return [
            'year' => $year,
            'total_alumni' => $alumni->count(),
            'employed' => $employed,
            'rate' => $alumni->count() > 0 ? round(($employed / $alumni->count()) * 100, 2) : 0,
        ];
    }

    protected function jobTrends(): array
    {
        return collect(range(11, 0))->map(function (int $offset) {
            $date = now()->subMonths($offset);

            return [
                'month' => $date->format('M Y'),
                'jobs' => Job::query()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'applications' => JobApplication::query()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        })->all();
    }

    protected function alumniDistribution(): array
    {
        return User::query()
            ->alumni()
            ->with('alumniProfile.course.college')
            ->get()
            ->groupBy(fn (User $user) => $user->alumniProfile?->course?->college?->college_name ?? 'Unassigned')
            ->map(fn (Collection $group, string $college) => ['college' => $college, 'count' => $group->count()])
            ->values()
            ->all();
    }

    protected function employerActivity(): array
    {
        return Employer::query()
            ->withCount(['jobs', 'jobs as approved_jobs_count' => fn ($query) => $query->where('status', 'approved')])
            ->get()
            ->map(fn (Employer $employer) => [
                'company_name' => $employer->company_name,
                'jobs_posted' => $employer->jobs_count,
                'approved_jobs' => $employer->approved_jobs_count,
            ])
            ->all();
    }

    protected function flattenRows(array $data): array
    {
        if (array_is_list($data)) {
            return $data;
        }

        return collect($data)->map(fn ($value, $key) => ['label' => $key, 'value' => is_array($value) ? json_encode($value) : $value])->values()->all();
    }
}
