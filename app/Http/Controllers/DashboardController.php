<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Services\DashboardMetricsService;

class DashboardController extends Controller
{
    public function __invoke(DashboardMetricsService $metrics)
    {
        $user = auth()->user();
        $role = $user?->role ?? '';

        $welcomeKey = match ($role) {
            Role::Doctor->value => 'dashboard.welcome.doctor',
            Role::Patient->value => 'dashboard.welcome.patient',
            default => 'dashboard.welcome.admin',
        };

        $illustrationBodyKey = match ($role) {
            Role::Doctor->value => 'dashboard.illustration.body.doctor',
            Role::Patient->value => 'dashboard.illustration.body.patient',
            default => 'dashboard.illustration.body.admin',
        };

        $showTodayAppointments = $role !== Role::Patient->value;
        $isAdmin = ! in_array($role, [Role::Doctor->value, Role::Patient->value], true);
        $showLatestPatients = $isAdmin;
        $showSpecialties = $isAdmin;

        return view('pages.dashboard', [
            'kpis' => $metrics->getMetricsForRole($role),
            'welcome' => __($welcomeKey, ['name' => $user?->firstname ?? '']),
            'now' => now()->locale(app()->getLocale()),
            'timezone' => config('app.timezone'),
            'weekChart' => $metrics->weekChartForRole($role),
            'statusChart' => $metrics->statusChartForRole($role),
            'topSpecialties' => $showSpecialties ? $metrics->specialtiesChartForRole($role) : [],
            'todayAppointments' => $showTodayAppointments ? $metrics->todayAppointmentsForRole($role) : [],
            'latestPatients' => $showLatestPatients ? $metrics->latestPatients() : [],
            'showTodayAppointments' => $showTodayAppointments,
            'showLatestPatients' => $showLatestPatients,
            'showSpecialties' => $showSpecialties,
            'illustrationBody' => __($illustrationBodyKey),
        ]);
    }
}
