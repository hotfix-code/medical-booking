<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardMetricsService
{
    /**
     * Get metrics based on user role
     */
    public function getMetricsForRole(string $roleName): array
    {
        return match ($roleName) {
            Role::SuperAdmin->value, Role::Admin->value => $this->getAdminMetrics(),
            Role::Doctor->value => $this->getDoctorMetrics(),
            Role::Patient->value => $this->getPatientMetrics(),
            default => [],
        };
    }

    /**
     * Get admin dashboard metrics
     */
    private function getAdminMetrics(): array
    {
        return [
            'users' => $this->getUsersMetric(),
            'admins' => $this->getAdminsMetric(),
            'doctors' => $this->getDoctorsMetric(),
            'patients' => $this->getPatientsMetric(),
        ];
    }

    /**
     * Get doctor dashboard metrics
     */
    private function getDoctorMetrics(): array
    {
        $doctorId = auth()->user()->doctor?->id;

        return [
            'my_appointments' => $this->getMyAppointmentsMetric($doctorId),
            'my_patients' => $this->getMyPatientsMetric($doctorId),
            'today_appointments' => $this->getTodayAppointmentsMetric($doctorId),
        ];
    }

    /**
     * Get patient dashboard metrics
     */
    private function getPatientMetrics(): array
    {
        $patientId = auth()->user()->patient?->id;

        return [
            'my_appointments' => $this->getPatientAppointmentsMetric($patientId),
            'upcoming_appointments' => $this->getUpcomingAppointmentsMetric($patientId),
        ];
    }

    /**
     * Users metric
     */
    private function getUsersMetric(): array
    {
        $currentCount = User::count();
        $previousCount = User::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => 'Total Users',
            'value' => number_format($currentCount),
            'subtitle' => 'Last week',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'usersChart',
        ];
    }

    /**
     * Admins metric
     */
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
            'title' => 'Total Admins',
            'value' => number_format($currentCount),
            'subtitle' => 'Last week',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'adminsChart',
        ];
    }

    /**
     * Patients metric
     */
    private function getPatientsMetric(): array
    {
        $currentCount = Patient::count();
        $previousCount = Patient::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => 'Total Patients',
            'value' => number_format($currentCount),
            'subtitle' => 'Last week',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'patientsChart',
        ];
    }

    /**
     * Doctors metric
     */
    private function getDoctorsMetric(): array
    {
        $currentCount = Doctor::count();
        $previousCount = Doctor::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => 'Total Doctors',
            'value' => number_format($currentCount),
            'subtitle' => 'Last week',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'doctorsChart',
        ];
    }

    /**
     * Appointments metric
     */
    private function getAppointmentsMetric(): array
    {
        $currentCount = Appointment::count();
        $previousCount = Appointment::where('created_at', '<', now()->subWeek())->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => 'Total Appointments',
            'value' => number_format($currentCount),
            'subtitle' => 'Last week',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'appointmentsChart',
        ];
    }

    /**
     * My appointment metric for doctors
     */
    private function getMyAppointmentsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('Mis Citas', 'appointmentsChart');
        }

        $currentCount = Appointment::where('doctor_id', $doctorId)->count();
        $previousCount = Appointment::where('doctor_id', $doctorId)
            ->where('created_at', '<', now()->subWeek())
            ->count();
        $change = $this->calculatePercentageChange($previousCount, $currentCount);

        return [
            'title' => 'My Appointments',
            'value' => number_format($currentCount),
            'subtitle' => 'Total',
            'change' => $change['formatted'],
            'changeIcon' => $change['icon'],
            'changeClass' => $change['class'],
            'chartId' => 'myAppointmentsChart',
        ];
    }

    /**
     * My patients metric for doctors
     */
    private function getMyPatientsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('My Patients', 'patientsChart');
        }

        $currentCount = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');

        return [
            'title' => 'My Patients',
            'value' => number_format($currentCount),
            'subtitle' => 'Unique patients',
            'change' => null,
            'changeIcon' => 'fe fe-users',
            'changeClass' => 'text-info',
            'chartId' => 'myPatientsChart',
        ];
    }

    /**
     * Today appointments metric for doctors
     */
    private function getTodayAppointmentsMetric(?string $doctorId): array
    {
        if (!$doctorId) {
            return $this->getEmptyMetric('Appointments Today', 'todayChart');
        }

        $todayCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();

        return [
            'title' => 'Appointments Today',
            'value' => number_format($todayCount),
            'subtitle' => today()->format('d/m/Y'),
            'change' => null,
            'changeIcon' => 'fe fe-calendar',
            'changeClass' => 'text-primary',
            'chartId' => 'todayAppointmentsChart',
        ];
    }

    /**
     * Patient appointments metric
     */
    private function getPatientAppointmentsMetric(?string $patientId): array
    {
        if (!$patientId) {
            return $this->getEmptyMetric('My Appointments', 'appointmentsChart');
        }

        $currentCount = Appointment::where('patient_id', $patientId)->count();

        return [
            'title' => 'My Appointments',
            'value' => number_format($currentCount),
            'subtitle' => 'Total',
            'change' => null,
            'changeIcon' => 'fe fe-calendar',
            'changeClass' => 'text-info',
            'chartId' => 'myAppointmentsChart',
        ];
    }

    /**
     * Upcoming appointments metric for patients
     */
    private function getUpcomingAppointmentsMetric(?string $patientId): array
    {
        if (!$patientId) {
            return $this->getEmptyMetric('Upcoming Appointments', 'upcomingChart');
        }

        $upcomingCount = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '>=', today())
            ->count();

        return [
            'title' => 'Upcoming Appointments',
            'value' => number_format($upcomingCount),
            'subtitle' => 'Scheduled',
            'change' => null,
            'changeIcon' => 'fe fe-clock',
            'changeClass' => 'text-success',
            'chartId' => 'upcomingAppointmentsChart',
        ];
    }

    /**
     * Calculate percentage change between two values
     */
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
            'formatted' => ($isPositive ? '+' : '') . number_format($percentage, 1) . '%',
            'icon' => $isPositive ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle',
            'class' => $isPositive ? 'text-success' : 'text-danger',
        ];
    }

    /**
     * Get empty metric structure
     */
    private function getEmptyMetric(string $title, string $chartId): array
    {
        return [
            'title' => $title,
            'value' => '0',
            'subtitle' => 'Sin datos',
            'change' => null,
            'changeIcon' => 'fe fe-info',
            'changeClass' => 'text-muted',
            'chartId' => $chartId,
        ];
    }
}
