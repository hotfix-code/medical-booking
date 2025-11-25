<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(DashboardMetricsService $metricsService)
    {
        $user = auth()->user();
        $userRole = in_array($user->role, Role::values()) ? $user->role : Role::Admin->value;
        $metrics = $metricsService->getMetricsForRole($userRole);
        return view('pages.dashboard', compact('metrics'));
    }
}
