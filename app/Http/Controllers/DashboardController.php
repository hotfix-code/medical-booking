<?php

namespace App\Http\Controllers;

use App\Services\DashboardMetricsService;

class DashboardController extends Controller
{
    public function __invoke(DashboardMetricsService $metricsService)
    {
        $metrics = $metricsService->getMetricsForRole(auth()->user()->role);
        return view('pages.dashboard', ['metrics' => $metrics]);
    }
}
