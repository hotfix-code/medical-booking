<?php

namespace App\Enums;

enum DashboardKpi: string
{
    case Patients = 'patients';
    case Doctors = 'doctors';
    case AppointmentsToday = 'appointments_today';
    case Admins = 'admins';
    case MyAppointments = 'my_appointments';
    case MyPatients = 'my_patients';
    case UpcomingAppointments = 'upcoming_appointments';

    public const int SERIES_DAYS = 7;

    public const int SERIES_MONTHS = 6;

    public const int TOP_SPECIALTIES = 6;

    public const int LIST_ROWS = 5;

    public function presentation(): array
    {
        [$title, $icon, $tone, $color] = match ($this) {
            self::Patients => ['dashboard.metrics.total_patients', 'bxs-group', 'info', '#3b82f6'],
            self::Doctors => ['dashboard.metrics.total_doctors', 'bxs-first-aid', 'success', '#22c55e'],
            self::AppointmentsToday => ['dashboard.metrics.appointments_today', 'bxs-calendar', 'warning', '#f59e0b'],
            self::Admins => ['dashboard.metrics.total_admins', 'bxs-user-account', 'purple', '#8b5cf6'],
            self::MyAppointments => ['dashboard.metrics.my_appointments', 'bxs-calendar', 'warning', '#f59e0b'],
            self::MyPatients => ['dashboard.metrics.my_patients', 'bxs-group', 'info', '#3b82f6'],
            self::UpcomingAppointments => ['dashboard.metrics.upcoming_appointments', 'bxs-calendar', 'success', '#22c55e'],
        };

        return [
            'id' => $this->value,
            'title' => __($title),
            'icon' => $icon,
            'tone' => $tone,
            'color' => $color,
        ];
    }
}
