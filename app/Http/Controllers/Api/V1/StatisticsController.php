<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportService;

class StatisticsController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    public function employment()
    {
        return response()->json($this->reportService->buildData('employment_rate'));
    }
}
