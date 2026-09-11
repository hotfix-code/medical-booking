<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

class DashboardMetricsService
{
    public function getMetricsForRole(string $roleName): array
    {
        return match ($roleName) {
            Role::Doctor->value => $this->getDoctorMetrics(),
            Role::Patient->value => $this->getPatientMetrics(),
            default => $this->getAdminMetrics(),
        };
    }

    private function getAdminMetrics(): array
    {
        return [
            'users' => $this->getUsersMetric(),
            'admins' => $this->getAdminsMetric(),
            'doctors' => $this->getDoctorsMetric(),
            'patients' => $this->getPatientsMetric(),
        ];
    }

    private function getDoctorMetrics(): array
    {
        $doctorId = auth()->user()->doctor?->id;

        return [
            'my_appointments' => $this->getMyAppointmentsMetric($doctorId),
            'my_patients' => $this->getMyPatientsMetric($doctorId),
            'today_appointments' => $this->getTodayAppointmentsMetric($doctorId),
        ];
    }

    private function getPatientMetrics(): array
    {
        $patientId = auth()->user()->patient?->id;

        return [
            'my_appointments' => $this->getPatientAppointmentsMetric($patientId),
            'upcoming_appointments' => $this->getUpcomingAppointmentsMetric($patientId),
        ];
    }

    private function getUsersMetric(): array
    {
        $currentCount = User::count();
        $previousCount = User::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.total_users'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.last_week'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'usersChart',
        ];
    }

    private function getAdminsMetric(): array
    {
        $currentCount = User::whereDoesntHave('patient')
            ->whereDoesntHave('doctor')
            ->count();

        $newAdminsThisWeek = User::whereDoesntHave('patient')
            ->whereDoesntHave('doctor')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $previousCount = $currentCount - $newAdminsThisWeek;
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.total_admins'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.last_week'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'adminsChart',
        ];
    }

    private function getPatientsMetric(): array
    {
        $currentCount = Patient::count();
        $previousCount = Patient::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.total_patients'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.last_week'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'patientsChart',
        ];
    }

    private function getDoctorsMetric(): array
    {
        $currentCount = Doctor::count();
        $previousCount = Doctor::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.total_doctors'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.last_week'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'doctorsChart',
        ];
    }

    private function getAppointmentsMetric(): array
    {
        $currentCount = Appointment::count();
        $previousCount = Appointment::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.total_appointments'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.last_week'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'appointmentsChart',
        ];
    }

    private function getMyAppointmentsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('dashboard.metrics.my_appointments', 'appointmentsChart');
        }

        $currentCount = Appointment::where('doctor_id', $doctorId)->count();
        $previousCount = Appointment::where('doctor_id', $doctorId)
            ->where('created_at', '<', now()->subWeek())
            ->count();

        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => __('dashboard.metrics.my_appointments'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.total'),
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'myAppointmentsChart',
        ];
    }

    private function getMyPatientsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('dashboard.metrics.my_patients', 'patientsChart');
        }

        $currentCount = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');

        return [
            'title' => __('dashboard.metrics.my_patients'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.unique_patients'),
            'change' => null,
            'changeIcon' => 'fe fe-users',
            'changeClass' => 'text-info',
            'chartId' => 'myPatientsChart',
        ];
    }

    private function getTodayAppointmentsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('dashboard.metrics.appointments_today', 'todayChart');
        }

        $todayCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();

        return [
            'title' => __('dashboard.metrics.appointments_today'),
            'value' => number_format($todayCount),
            'subtitle' => today()->format('d/m/Y'),
            'change' => null,
            'changeIcon' => 'fe fe-calendar',
            'changeClass' => 'text-primary',
            'chartId' => 'todayAppointmentsChart',
        ];
    }

    private function getPatientAppointmentsMetric(?string $patientId): array
    {
        if (!$patientId) {
            return $this->getEmptyMetric('dashboard.metrics.my_appointments', 'appointmentsChart');
        }

        $currentCount = Appointment::where('patient_id', $patientId)->count();

        return [
            'title' => __('dashboard.metrics.my_appointments'),
            'value' => number_format($currentCount),
            'subtitle' => __('dashboard.subtitles.total'),
            'change' => null,
            'changeIcon' => 'fe fe-calendar',
            'changeClass' => 'text-info',
            'chartId' => 'myAppointmentsChart',
        ];
    }

    private function getUpcomingAppointmentsMetric(?string $patientId): array
    {
        if (!$patientId) {
            return $this->getEmptyMetric('dashboard.metrics.upcoming_appointments', 'upcomingChart');
        }

        $upcomingCount = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '>=', today())
            ->count();

        return [
            'title' => __('dashboard.metrics.upcoming_appointments'),
            'value' => number_format($upcomingCount),
            'subtitle' => __('dashboard.subtitles.scheduled'),
            'change' => null,
            'changeIcon' => 'fe fe-clock',
            'changeClass' => 'text-success',
            'chartId' => 'upcomingAppointmentsChart',
        ];
    }

    private function calculatePercentageChange(int $previous, int $current): array
    {
        if ($previous === 0) {
            return [
                'formatted' => $current > 0 ? '100%' : '0%',
                'icon' => $current > 0 ? 'fe fe-arrow-up-circle' : 'fe fe-minus-circle',
                'class' => $current > 0 ? 'text-success' : 'text-muted',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;
        $isPositive = $percentage >= 0;

        return [
            'formatted' => ($isPositive ? '+' : '').number_format($percentage, 1).'%',
            'icon' => $isPositive ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle',
            'class' => $isPositive ? 'text-success' : 'text-danger',
        ];
    }

    private function getEmptyMetric(string $titleKey, string $chartId): array
    {
        return [
            'title' => __($titleKey),
            'value' => '0',
            'subtitle' => __('dashboard.subtitles.no_data'),
            'change' => null,
            'changeIcon' => 'fe fe-info',
            'changeClass' => 'text-muted',
            'chartId' => $chartId,
        ];
    }
}
