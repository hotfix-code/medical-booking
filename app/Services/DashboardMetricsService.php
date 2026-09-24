<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\DashboardKpi;
use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardMetricsService
{
    public function getMetricsForRole(string $roleName): array
    {
        return match ($roleName) {
            Role::Doctor->value => $this->getDoctorMetrics(),
            Role::Patient->value => $this->getPatientMetrics(),
            default => $this->present($this->adminStats()),
        };
    }

    public function weekChartForRole(string $roleName): array
    {
        $query = $this->appointmentsQueryForRole($roleName);

        return [
            'period' => 'week',
            'subtitles' => [
                'week' => __('dashboard.charts.week_subtitle'),
                'month' => __('dashboard.charts.week_subtitle_month'),
            ],
            'periods' => [
                'week' => $this->weekDaysPeriod($query),
                'month' => $this->lastMonthsPeriod($query),
            ],
        ];
    }

    public function statusChartForRole(string $roleName): array
    {
        $query = $this->appointmentsQueryForRole($roleName);

        return [
            'period' => 'month',
            'subtitles' => [
                'month' => __('dashboard.charts.status_subtitle'),
                'week' => __('dashboard.charts.status_subtitle_week'),
            ],
            'periods' => [
                'month' => $this->statusPeriod(
                    $query,
                    now()->startOfMonth()->toDateString(),
                    now()->endOfMonth()->toDateString(),
                ),
                'week' => $this->statusPeriod(
                    $query,
                    now()->subDays(DashboardKpi::SERIES_DAYS - 1)->toDateString(),
                    now()->toDateString(),
                ),
            ],
        ];
    }

    public function specialtiesChartForRole(string $roleName): array
    {
        $query = $this->appointmentsQueryForRole($roleName);

        return [
            'period' => 'month',
            'empty' => __('dashboard.charts.specialties_empty'),
            'subtitles' => [
                'month' => __('dashboard.charts.specialties_subtitle'),
                'week' => __('dashboard.charts.specialties_subtitle_week'),
            ],
            'periods' => [
                'month' => $this->specialtyPeriod(
                    $query,
                    now()->startOfMonth()->toDateString(),
                    now()->endOfMonth()->toDateString(),
                ),
                'week' => $this->specialtyPeriod(
                    $query,
                    now()->subDays(DashboardKpi::SERIES_DAYS - 1)->toDateString(),
                    now()->toDateString(),
                ),
            ],
        ];
    }

    public function todayAppointmentsForRole(string $roleName): array
    {
        return $this->appointmentsQueryForRole($roleName)
            ->with(['patient.user', 'doctor.user', 'specialty'])
            ->whereDate('appointment_date', today())
            ->isActive()
            ->orderBy('appointment_time')
            ->limit(DashboardKpi::LIST_ROWS)
            ->get()
            ->map(fn (Appointment $appointment) => $this->presentAppointmentRow($appointment))
            ->all();
    }

    private function presentAppointmentRow(Appointment $appointment): array
    {
        $patientName = $appointment->patient?->user?->full_name ?? '';
        $doctorName = $appointment->doctor?->user?->full_name ?? '';

        return [
            'time' => $this->formatTime($appointment->appointment_time),
            'initials' => $this->initials($patientName),
            'patient' => $patientName,
            'specialty' => $appointment->specialty?->name ?? '',
            'doctor' => $doctorName,
            'status' => $appointment->status->value,
        ];
    }

    public function latestPatients(): array
    {
        return Patient::query()
            ->with(['user', 'documentType'])
            ->orderByDesc('created_at')
            ->limit(DashboardKpi::LIST_ROWS)
            ->get()
            ->map(fn (Patient $patient) => $this->presentPatientRow($patient))
            ->all();
    }

    private function presentPatientRow(Patient $patient): array
    {
        $name = $patient->user?->full_name ?? '';

        return [
            'name' => $name,
            'initials' => $this->initials($name),
            'document' => trim(sprintf('%s %s', $patient->documentType?->code ?? '', $patient->document_number)),
            'registered_date' => $patient->created_at->locale(app()->getLocale())->isoFormat('D MMM YYYY'),
            'registered_time' => $this->formatTime($patient->created_at->format('H:i:s')),
        ];
    }

    private function formatTime(string $time): string
    {
        $date = Carbon::parse($time);
        $hour = (int) $date->format('G');
        $hour12 = $hour % 12 ?: 12;
        $period = app()->getLocale() === 'es'
            ? ($hour >= 12 ? 'p. m.' : 'a. m.')
            : ($hour >= 12 ? 'PM' : 'AM');

        return sprintf('%02d:%02d %s', $hour12, (int) $date->format('i'), $period);
    }

    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return strtoupper(implode('', array_map(
            fn (string $word) => mb_substr($word, 0, 1),
            array_slice($words, 0, 2),
        )));
    }

    private function adminStats(): array
    {
        return [
            $this->countCard(DashboardKpi::Patients, Patient::query(), 'created_at', 'dashboard.subtitles.vs_previous_month'),
            $this->countCard(DashboardKpi::Doctors, Doctor::query(), 'created_at', 'dashboard.subtitles.vs_previous_month'),
            $this->appointmentsTodayCard(Appointment::query()),
            $this->countCard(DashboardKpi::Admins, $this->adminUsersQuery(), 'created_at', 'dashboard.subtitles.vs_previous_month'),
        ];
    }

    private function getDoctorMetrics(): array
    {
        $appointments = $this->appointmentsFor('doctor_id', auth()->user()?->doctor?->id);

        return $this->present([
            $this->countCard(DashboardKpi::MyAppointments, $appointments, 'created_at', 'dashboard.subtitles.vs_previous_month'),
            $this->countCard(DashboardKpi::MyPatients, $appointments, 'created_at', 'dashboard.subtitles.vs_previous_month', 'patient_id'),
            $this->appointmentsTodayCard($appointments),
        ]);
    }

    private function getPatientMetrics(): array
    {
        $appointments = $this->appointmentsFor('patient_id', auth()->user()?->patient?->id);

        return $this->present([
            $this->countCard(DashboardKpi::MyAppointments, $appointments, 'created_at', 'dashboard.subtitles.vs_previous_month'),
            $this->upcomingCard($appointments),
        ]);
    }

    private function appointmentsQueryForRole(string $roleName): Builder
    {
        return match ($roleName) {
            Role::Doctor->value => $this->appointmentsFor('doctor_id', auth()->user()?->doctor?->id),
            Role::Patient->value => $this->appointmentsFor('patient_id', auth()->user()?->patient?->id),
            default => Appointment::query(),
        };
    }

    private function appointmentsFor(string $column, ?string $id): Builder
    {
        $query = Appointment::query();

        if (! $id) {
            return $query->whereRaw('0 = 1');
        }

        return $query->where($column, $id);
    }

    private function weekDaysPeriod(Builder $query): array
    {
        $days = DashboardKpi::SERIES_DAYS;
        $start = now()->subDays($days - 1)->startOfDay();
        $end = now()->endOfDay();

        $counts = (clone $query)
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(appointment_date) as kpi_day, COUNT(*) as kpi_count')
            ->groupByRaw('DATE(appointment_date)')
            ->pluck('kpi_count', 'kpi_day');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->locale(app()->getLocale());
            $labels[] = ucfirst(rtrim($day->isoFormat('ddd'), '.')).' '.$day->day;
            $values[] = (int) ($counts[$day->toDateString()] ?? 0);
        }

        return $this->chartPeriod($labels, $values);
    }

    private function lastMonthsPeriod(Builder $query): array
    {
        $months = DashboardKpi::SERIES_MONTHS;
        $start = now()->copy()->subMonthsNoOverflow($months - 1)->startOfMonth();
        $end = now()->endOfMonth();

        $counts = (clone $query)
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("to_char(appointment_date, 'YYYY-MM') as kpi_month, COUNT(*) as kpi_count")
            ->groupByRaw("to_char(appointment_date, 'YYYY-MM')")
            ->pluck('kpi_count', 'kpi_month');

        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonthsNoOverflow($i)->locale(app()->getLocale());
            $labels[] = ucfirst(rtrim($month->isoFormat('MMM'), '.'));
            $values[] = (int) ($counts[$month->format('Y-m')] ?? 0);
        }

        return $this->chartPeriod($labels, $values);
    }

    private function statusPeriod(Builder $query, string $from, string $to): array
    {
        $counts = (clone $query)
            ->whereBetween('appointment_date', [$from, $to])
            ->selectRaw('status, COUNT(*) as kpi_count')
            ->groupBy('status')
            ->pluck('kpi_count', 'status');

        $slices = [];

        foreach ([
            AppointmentStatus::Confirmed,
            AppointmentStatus::Pending,
            AppointmentStatus::Completed,
            AppointmentStatus::Cancelled,
            AppointmentStatus::NoShow,
        ] as $status) {
            $slices[] = [
                'key' => $status->value,
                'value' => (int) ($counts[$status->value] ?? 0),
                'color' => $this->statusColor($status),
            ];
        }

        $total = array_sum(array_column($slices, 'value'));

        return [
            'labels' => array_map(fn (array $slice) => __('enums.appointment_status.'.$slice['key']), $slices),
            'values' => array_column($slices, 'value'),
            'colors' => array_column($slices, 'color'),
            'total' => $total,
            'unit' => __('dashboard.charts.appointments'),
            'legend' => array_map(fn (array $slice) => [
                'label' => __('enums.appointment_status.'.$slice['key']),
                'value' => $slice['value'],
                'percent' => $total > 0 ? (int) round(($slice['value'] / $total) * 100) : 0,
                'color' => $slice['color'],
            ], $slices),
        ];
    }

    private function specialtyPeriod(Builder $query, string $from, string $to): array
    {
        $rows = (clone $query)
            ->whereBetween('appointments.appointment_date', [$from, $to])
            ->join('specialties', 'specialties.id', '=', 'appointments.specialty_id')
            ->selectRaw('specialties.name as label, COUNT(*) as kpi_count')
            ->groupBy('specialties.id', 'specialties.name')
            ->orderByDesc('kpi_count')
            ->limit(DashboardKpi::TOP_SPECIALTIES)
            ->get();

        $max = (int) $rows->max('kpi_count') ?: 1;

        return $rows->map(fn ($row) => [
            'label' => $row->label,
            'count' => (int) $row->kpi_count,
            'percent' => (int) round(((int) $row->kpi_count / $max) * 100),
        ])->all();
    }

    private function statusColor(AppointmentStatus $status): string
    {
        return match ($status) {
            AppointmentStatus::Confirmed => '#22c55e',
            AppointmentStatus::Pending => '#f59e0b',
            AppointmentStatus::Completed => '#14b8a6',
            AppointmentStatus::Cancelled => '#ef4444',
            AppointmentStatus::NoShow => '#9ca3af',
        };
    }

    private function chartPeriod(array $labels, array $values): array
    {
        return [
            'labels' => $labels,
            'values' => $values,
            'unit' => __('dashboard.charts.appointments'),
            'color' => '#3b82f6',
        ];
    }

    private function countCard(
        DashboardKpi $kpi,
        Builder $query,
        string $dateColumn,
        string $comparisonKey,
        ?string $distinctColumn = null,
    ): array {
        $startOfMonth = now()->startOfMonth();
        $startOfLastMonth = now()->copy()->subMonthNoOverflow()->startOfMonth();

        $thisPeriod = $this->aggregate(
            (clone $query)->where($dateColumn, '>=', $startOfMonth),
            $distinctColumn,
        );

        $lastPeriod = $this->aggregate(
            (clone $query)
                ->where($dateColumn, '>=', $startOfLastMonth)
                ->where($dateColumn, '<', $startOfMonth),
            $distinctColumn,
        );

        return [
            'kpi' => $kpi,
            'value' => number_format($this->aggregate(clone $query, $distinctColumn)),
            'series' => $this->seriesByDay($query, $dateColumn, $distinctColumn),
            ...$this->periodDelta($lastPeriod, $thisPeriod, $comparisonKey),
        ];
    }

    private function appointmentsTodayCard(Builder $query): array
    {
        $today = (clone $query)->whereDate('appointment_date', today())->count();
        $yesterday = (clone $query)->whereDate('appointment_date', today()->subDay())->count();

        return [
            'kpi' => DashboardKpi::AppointmentsToday,
            'value' => number_format($today),
            'series' => $this->seriesByDay($query, 'appointment_date'),
            ...$this->periodDelta($yesterday, $today, 'dashboard.subtitles.vs_yesterday'),
        ];
    }

    private function periodDelta(int $previous, int $current, string $comparisonKey): array
    {
        if ($previous === 0 && $current === 0) {
            return [
                'trend' => 'flat',
                'change' => null,
                'comparison' => __('dashboard.subtitles.no_change'),
            ];
        }

        if ($previous === 0) {
            return [
                'trend' => 'up',
                'change' => '100%',
                'comparison' => __($comparisonKey),
            ];
        }

        $percentage = (int) round((($current - $previous) / $previous) * 100);

        if ($percentage === 0) {
            return [
                'trend' => 'flat',
                'change' => null,
                'comparison' => __('dashboard.subtitles.no_change'),
            ];
        }

        return [
            'trend' => $percentage > 0 ? 'up' : 'down',
            'change' => abs($percentage).'%',
            'comparison' => __($comparisonKey),
        ];
    }

    private function upcomingCard(Builder $query): array
    {
        $days = DashboardKpi::SERIES_DAYS;
        $today = today();

        $windowEnd = $today->copy()->addDays($days - 1);
        $previousStart = $today->copy()->subDays($days);
        $previousEnd = $today->copy()->subDay();

        $thisWindow = (clone $query)->whereBetween('appointment_date', [$today, $windowEnd])->count();
        $lastWindow = (clone $query)->whereBetween('appointment_date', [$previousStart, $previousEnd])->count();

        return [
            'kpi' => DashboardKpi::UpcomingAppointments,
            'value' => number_format((clone $query)->where('appointment_date', '>=', $today)->count()),
            'series' => $this->seriesByDay($query, 'appointment_date', forward: true),
            ...$this->periodDelta($lastWindow, $thisWindow, 'dashboard.subtitles.vs_last_week'),
        ];
    }

    private function aggregate(Builder $query, ?string $distinctColumn): int
    {
        if ($distinctColumn) {
            return (int) $query->selectRaw("COUNT(DISTINCT {$distinctColumn}) as kpi_count")->value('kpi_count');
        }

        return $query->count();
    }

    private function seriesByDay(Builder $query, string $column, ?string $distinctColumn = null, bool $forward = false): array
    {
        $days = DashboardKpi::SERIES_DAYS;
        $start = $forward ? now()->startOfDay() : now()->subDays($days - 1)->startOfDay();
        $end = $forward ? now()->addDays($days - 1)->endOfDay() : now()->endOfDay();

        $dateSql = "DATE({$column})";

        $countSql = $distinctColumn
            ? "COUNT(DISTINCT {$distinctColumn})"
            : 'COUNT(*)';

        $counts = (clone $query)
            ->whereBetween($column, [$start, $end])
            ->selectRaw("{$dateSql} as kpi_day, {$countSql} as kpi_count")
            ->groupByRaw($dateSql)
            ->pluck('kpi_count', 'kpi_day');

        $series = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $series[] = (int) ($counts[$day] ?? 0);
        }

        return $series;
    }

    private function adminUsersQuery(): Builder
    {
        return User::query()
            ->whereDoesntHave('patient')
            ->whereDoesntHave('doctor');
    }

    private function present(array $stats): array
    {
        return array_map(function (array $row) {
            return [
                ...$row['kpi']->presentation(),
                'value' => $row['value'],
                'series' => $row['series'],
                'trend' => $row['trend'],
                'change' => $row['change'],
                'comparison' => $row['comparison'],
            ];
        }, $stats);
    }
}
