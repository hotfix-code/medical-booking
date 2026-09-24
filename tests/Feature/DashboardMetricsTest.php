<?php

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\ConsultingRoom;
use App\Models\Doctor;
use App\Models\DocumentType;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\User;
use App\Services\DashboardMetricsService;
use Illuminate\Support\Carbon;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-09-20 12:00:00'));
    $this->service = app(DashboardMetricsService::class);
});

afterEach(function () {
    Carbon::setTestNow();
});

function adminKpis(): array
{
    return test()->service->getMetricsForRole(Role::SuperAdmin->value);
}

function kpiById(array $kpis, string $id): array
{
    $match = collect($kpis)->firstWhere('id', $id);
    expect($match)->not->toBeNull();

    return $match;
}

function stampCreatedAt($model, Carbon $at): void
{
    $model->created_at = $at;
    $model->save();
}

function makeAppointment(string $date, string $time = '10:00:00', ?Doctor $doctor = null, ?Patient $patient = null, string $status = 'pending', ?Specialty $specialty = null): Appointment
{
    $doctor ??= Doctor::factory()->create();
    $patient ??= Patient::factory()->create();
    $specialty ??= $doctor->specialties()->first() ?? Specialty::factory()->create();
    $doctor->specialties()->syncWithoutDetaching([$specialty->id]);
    $room = ConsultingRoom::factory()->create();
    $schedule = Schedule::factory()->create([
        'doctor_id' => $doctor->id,
        'specialty_id' => $specialty->id,
        'consulting_room_id' => $room->id,
    ]);

    return Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'specialty_id' => $specialty->id,
        'schedule_id' => $schedule->id,
        'consulting_room_id' => $room->id,
        'appointment_date' => $date,
        'appointment_time' => $time,
        'status' => $status,
        'doctor_slot_key' => hash('sha256', $doctor->id.$date.$time),
        'room_slot_key' => hash('sha256', $room->id.$date.$time),
    ]);
}

it('returns the four admin cards with catalog presentation', function () {
    $kpis = adminKpis();

    expect($kpis)->toHaveCount(4)
        ->and(array_column($kpis, 'id'))->toBe([
            'patients',
            'doctors',
            'appointments_today',
            'admins',
        ]);

    expect(kpiById($kpis, 'patients'))
        ->toMatchArray([
            'icon' => 'bxs-group',
            'tone' => 'info',
            'color' => '#3b82f6',
        ]);

    expect(kpiById($kpis, 'doctors'))
        ->toMatchArray([
            'icon' => 'bxs-first-aid',
            'tone' => 'success',
            'color' => '#22c55e',
        ]);

    expect(kpiById($kpis, 'appointments_today'))
        ->toMatchArray([
            'icon' => 'bxs-calendar',
            'tone' => 'warning',
            'color' => '#f59e0b',
        ]);

    expect(kpiById($kpis, 'admins'))
        ->toMatchArray([
            'icon' => 'bxs-user-account',
            'tone' => 'purple',
            'color' => '#8b5cf6',
        ]);
});

it('counts patients and fills a 7-day series with zeros', function () {
    stampCreatedAt(Patient::factory()->create(), now()->subDays(2));
    stampCreatedAt(Patient::factory()->create(), now());
    stampCreatedAt(Patient::factory()->create(), now()->subMonthNoOverflow()->startOfMonth()->addDays(3));

    $patients = kpiById(adminKpis(), 'patients');

    expect($patients['value'])->toBe('3')
        ->and($patients['series'])->toHaveCount(7)
        ->and($patients['series'][4])->toBe(1)
        ->and($patients['series'][6])->toBe(1)
        ->and($patients['trend'])->toBe('up')
        ->and($patients['change'])->toBe('100%')
        ->and($patients['comparison'])->toBe(__('dashboard.subtitles.vs_previous_month'));
});

it('marks admin cards flat when this month and last month are empty', function () {
    $patients = kpiById(adminKpis(), 'patients');

    expect($patients['value'])->toBe('0')
        ->and($patients['series'])->toBe([0, 0, 0, 0, 0, 0, 0])
        ->and($patients['trend'])->toBe('flat')
        ->and($patients['change'])->toBeNull()
        ->and($patients['comparison'])->toBe(__('dashboard.subtitles.no_change'));
});

it('counts appointments today against yesterday by appointment_date', function () {
    makeAppointment(now()->toDateString(), '09:00:00');
    makeAppointment(now()->toDateString(), '11:00:00');
    makeAppointment(now()->subDay()->toDateString(), '09:00:00');

    $card = kpiById(adminKpis(), 'appointments_today');

    expect($card['value'])->toBe('2')
        ->and($card['series'][5])->toBe(1)
        ->and($card['series'][6])->toBe(2)
        ->and($card['trend'])->toBe('up')
        ->and($card['change'])->toBe('100%')
        ->and($card['comparison'])->toBe(__('dashboard.subtitles.vs_yesterday'));
});

it('counts users without patient or doctor as admins', function () {
    stampCreatedAt(User::factory()->create(), now()->subMonthNoOverflow()->addDays(2));
    stampCreatedAt(User::factory()->create(), now());
    Patient::factory()->create();
    Doctor::factory()->create();

    $admins = kpiById(adminKpis(), 'admins');

    expect($admins['value'])->toBe('2')
        ->and($admins['trend'])->toBe('flat')
        ->and($admins['change'])->toBeNull();
});

it('scopes doctor kpis with series and monthly delta', function () {
    $doctor = Doctor::factory()->create();
    $this->actingAs($doctor->user);

    stampCreatedAt(makeAppointment(now()->toDateString(), '09:00:00', $doctor), now());
    stampCreatedAt(makeAppointment(now()->subDay()->toDateString(), '09:00:00', $doctor), now()->subDay());
    stampCreatedAt(
        makeAppointment(now()->subMonthNoOverflow()->toDateString(), '09:00:00', $doctor),
        now()->subMonthNoOverflow()->startOfMonth()->addDays(2),
    );
    stampCreatedAt(makeAppointment(now()->toDateString(), '10:00:00'), now());

    $kpis = $this->service->getMetricsForRole(Role::Doctor->value);

    expect(array_column($kpis, 'id'))->toBe([
        'my_appointments',
        'my_patients',
        'appointments_today',
    ]);

    $appointments = kpiById($kpis, 'my_appointments');
    expect($appointments['value'])->toBe('3')
        ->and($appointments['series'][5])->toBe(1)
        ->and($appointments['series'][6])->toBe(1)
        ->and($appointments['trend'])->toBe('up')
        ->and($appointments['change'])->toBe('100%');

    $today = kpiById($kpis, 'appointments_today');
    expect($today['value'])->toBe('1')
        ->and($today['series'][5])->toBe(1)
        ->and($today['series'][6])->toBe(1)
        ->and($today['trend'])->toBe('flat');

    expect(kpiById($kpis, 'my_patients')['value'])->toBe('3');
});

it('scopes patient kpis and builds a forward upcoming series', function () {
    $patient = Patient::factory()->create();
    $this->actingAs($patient->user);

    stampCreatedAt(makeAppointment(now()->toDateString(), '09:00:00', patient: $patient), now());
    makeAppointment(now()->addDay()->toDateString(), '09:00:00', patient: $patient);
    makeAppointment(now()->subDays(3)->toDateString(), '09:00:00', patient: $patient);
    makeAppointment(now()->addDay()->toDateString(), '10:00:00');

    $kpis = $this->service->getMetricsForRole(Role::Patient->value);

    expect(array_column($kpis, 'id'))->toBe([
        'my_appointments',
        'upcoming_appointments',
    ]);

    $mine = kpiById($kpis, 'my_appointments');
    expect($mine['value'])->toBe('3')
        ->and($mine['trend'])->toBe('up');

    $upcoming = kpiById($kpis, 'upcoming_appointments');
    expect($upcoming['value'])->toBe('2')
        ->and($upcoming['series'][0])->toBe(1)
        ->and($upcoming['series'][1])->toBe(1)
        ->and($upcoming['trend'])->toBe('up')
        ->and($upcoming['comparison'])->toBe(__('dashboard.subtitles.vs_last_week'));
});

it('builds the week chart with zeros when there are no appointments', function () {
    $chart = $this->service->weekChartForRole(Role::SuperAdmin->value);

    expect($chart['period'])->toBe('week')
        ->and($chart['periods']['week']['values'])->toBe([0, 0, 0, 0, 0, 0, 0])
        ->and($chart['periods']['month']['values'])->toBe([0, 0, 0, 0, 0, 0]);
});

it('counts appointments per day for the last 7 days', function () {
    makeAppointment(now()->toDateString(), '09:00:00');
    makeAppointment(now()->toDateString(), '11:00:00');
    makeAppointment(now()->subDay()->toDateString(), '09:00:00');
    makeAppointment(now()->subDays(8)->toDateString(), '09:00:00');

    $week = $this->service->weekChartForRole(Role::SuperAdmin->value)['periods']['week'];

    expect($week['values'])->toHaveCount(7)
        ->and($week['values'][5])->toBe(1)
        ->and($week['values'][6])->toBe(2)
        ->and(array_sum($week['values']))->toBe(3);
});

it('counts appointments per month for the last 6 months', function () {
    makeAppointment(now()->toDateString(), '09:00:00');
    makeAppointment(now()->toDateString(), '10:00:00');
    makeAppointment(now()->subMonthsNoOverflow(2)->startOfMonth()->toDateString(), '09:00:00');
    makeAppointment(now()->subMonthsNoOverflow(8)->toDateString(), '09:00:00');

    $month = $this->service->weekChartForRole(Role::SuperAdmin->value)['periods']['month'];

    expect($month['values'])->toHaveCount(6)
        ->and($month['values'][3])->toBe(1)
        ->and($month['values'][5])->toBe(2)
        ->and(array_sum($month['values']))->toBe(3);
});

it('scopes the week chart to the authenticated doctor', function () {
    $doctor = Doctor::factory()->create();
    $this->actingAs($doctor->user);

    makeAppointment(now()->toDateString(), '09:00:00', $doctor);
    makeAppointment(now()->toDateString(), '10:00:00');

    $week = $this->service->weekChartForRole(Role::Doctor->value)['periods']['week'];

    expect($week['values'][6])->toBe(1)
        ->and(array_sum($week['values']))->toBe(1);
});

it('builds the status chart with zero slices when empty', function () {
    $chart = $this->service->statusChartForRole(Role::SuperAdmin->value);
    $month = $chart['periods']['month'];

    expect($chart['period'])->toBe('month')
        ->and($month['total'])->toBe(0)
        ->and($month['values'])->toBe([0, 0, 0, 0, 0]);
});

it('counts appointment statuses for the current month', function () {
    makeAppointment(now()->toDateString(), '09:00:00', status: 'confirmed');
    makeAppointment(now()->toDateString(), '10:00:00', status: 'confirmed');
    makeAppointment(now()->toDateString(), '11:00:00', status: 'pending');
    makeAppointment(now()->subMonthNoOverflow()->toDateString(), '09:00:00', status: 'completed');

    $month = $this->service->statusChartForRole(Role::SuperAdmin->value)['periods']['month'];

    expect($month['total'])->toBe(3)
        ->and($month['values'][0])->toBe(2)
        ->and($month['values'][1])->toBe(1)
        ->and($month['legend'][0]['percent'])->toBe(67)
        ->and($month['legend'][1]['percent'])->toBe(33);
});

it('excludes appointments outside the last 7 days from the week status chart', function () {
    makeAppointment(now()->toDateString(), '09:00:00', status: 'confirmed');
    makeAppointment(now()->subDays(8)->toDateString(), '09:00:00', status: 'pending');

    $week = $this->service->statusChartForRole(Role::SuperAdmin->value)['periods']['week'];

    expect($week['total'])->toBe(1)
        ->and($week['values'][0])->toBe(1)
        ->and($week['values'][1])->toBe(0);
});

it('scopes the status chart to the authenticated doctor', function () {
    $doctor = Doctor::factory()->create();
    $this->actingAs($doctor->user);

    makeAppointment(now()->toDateString(), '09:00:00', $doctor, status: 'confirmed');
    makeAppointment(now()->toDateString(), '10:00:00', status: 'pending');

    $month = $this->service->statusChartForRole(Role::Doctor->value)['periods']['month'];

    expect($month['total'])->toBe(1)
        ->and($month['values'][0])->toBe(1)
        ->and($month['values'][1])->toBe(0);
});

it('builds empty specialty periods when there are no appointments', function () {
    $chart = $this->service->specialtiesChartForRole(Role::SuperAdmin->value);

    expect($chart['periods']['month'])->toBe([])
        ->and($chart['periods']['week'])->toBe([]);
});

it('ranks specialties by appointment count in the current month', function () {
    $alpha = Specialty::factory()->create(['name' => 'Alpha']);
    $beta = Specialty::factory()->create(['name' => 'Beta']);

    makeAppointment(now()->toDateString(), '09:00:00', specialty: $alpha);
    makeAppointment(now()->toDateString(), '10:00:00', specialty: $alpha);
    makeAppointment(now()->toDateString(), '11:00:00', specialty: $beta);
    makeAppointment(now()->subMonthNoOverflow()->toDateString(), '09:00:00', specialty: $alpha);

    $month = $this->service->specialtiesChartForRole(Role::SuperAdmin->value)['periods']['month'];

    expect($month)->toHaveCount(2)
        ->and($month[0]['label'])->toBe('Alpha')
        ->and($month[0]['count'])->toBe(2)
        ->and($month[0]['percent'])->toBe(100)
        ->and($month[1]['label'])->toBe('Beta')
        ->and($month[1]['count'])->toBe(1)
        ->and($month[1]['percent'])->toBe(50);
});

it('excludes old appointments from the week specialty chart', function () {
    $alpha = Specialty::factory()->create(['name' => 'Alpha']);

    makeAppointment(now()->toDateString(), '09:00:00', specialty: $alpha);
    makeAppointment(now()->subDays(8)->toDateString(), '09:00:00', specialty: $alpha);

    $week = $this->service->specialtiesChartForRole(Role::SuperAdmin->value)['periods']['week'];

    expect($week)->toHaveCount(1)
        ->and($week[0]['count'])->toBe(1);
});

it('scopes the specialty chart to the authenticated doctor', function () {
    $doctor = Doctor::factory()->create();
    $this->actingAs($doctor->user);

    $alpha = Specialty::factory()->create(['name' => 'Alpha']);
    $beta = Specialty::factory()->create(['name' => 'Beta']);

    makeAppointment(now()->toDateString(), '09:00:00', $doctor, specialty: $alpha);
    makeAppointment(now()->toDateString(), '10:00:00', specialty: $beta);

    $month = $this->service->specialtiesChartForRole(Role::Doctor->value)['periods']['month'];

    expect($month)->toHaveCount(1)
        ->and($month[0]['label'])->toBe('Alpha')
        ->and($month[0]['count'])->toBe(1);
});

it('lists today active appointments ordered by time with mapped rows', function () {
    $patientUser = User::factory()->create(['firstname' => 'María', 'lastname' => 'Torres']);
    $patient = Patient::factory()->for($patientUser)->create();
    $doctorUser = User::factory()->create(['firstname' => 'Carlos', 'lastname' => 'López']);
    $doctor = Doctor::factory()->for($doctorUser)->create();
    $specialty = Specialty::factory()->create(['name' => 'Cardiology']);

    makeAppointment(now()->toDateString(), '11:00:00', $doctor, $patient, 'confirmed', $specialty);
    makeAppointment(now()->toDateString(), '09:00:00', $doctor, $patient, 'pending', $specialty);
    makeAppointment(now()->subDay()->toDateString(), '08:00:00', $doctor, $patient, 'confirmed', $specialty);

    app()->setLocale('en');

    $rows = $this->service->todayAppointmentsForRole(Role::SuperAdmin->value);

    expect($rows)->toHaveCount(2)
        ->and(array_column($rows, 'time'))->toBe(['09:00 AM', '11:00 AM'])
        ->and($rows[0])->toMatchArray([
            'patient' => 'María Torres',
            'initials' => 'MT',
            'specialty' => 'Cardiology',
            'doctor' => 'Carlos López',
            'status' => 'pending',
        ])
        ->and($rows[1]['status'])->toBe('confirmed');
});

it('excludes cancelled and no-show appointments from the today list', function () {
    makeAppointment(now()->toDateString(), '09:00:00', status: 'confirmed');
    makeAppointment(now()->toDateString(), '10:00:00', status: 'cancelled');
    makeAppointment(now()->toDateString(), '11:00:00', status: 'no_show');

    $rows = $this->service->todayAppointmentsForRole(Role::SuperAdmin->value);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['status'])->toBe('confirmed');
});

it('caps the today list at five rows', function () {
    app()->setLocale('en');

    foreach (range(8, 14) as $hour) {
        makeAppointment(now()->toDateString(), sprintf('%02d:00:00', $hour), status: 'confirmed');
    }

    $rows = $this->service->todayAppointmentsForRole(Role::SuperAdmin->value);

    expect($rows)->toHaveCount(5)
        ->and(array_column($rows, 'time'))->toBe(['08:00 AM', '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM']);
});

it('formats appointment times in 12-hour locale format', function () {
    makeAppointment(now()->toDateString(), '13:05:00', status: 'confirmed');
    makeAppointment(now()->toDateString(), '00:30:00', status: 'confirmed');

    app()->setLocale('en');
    $english = array_column($this->service->todayAppointmentsForRole(Role::SuperAdmin->value), 'time');
    expect($english)->toBe(['12:30 AM', '01:05 PM']);

    app()->setLocale('es');
    $spanish = array_column($this->service->todayAppointmentsForRole(Role::SuperAdmin->value), 'time');
    expect($spanish)->toBe(['12:30 a. m.', '01:05 p. m.']);
});

it('scopes the today list to the authenticated doctor', function () {
    $doctor = Doctor::factory()->create();
    $this->actingAs($doctor->user);

    makeAppointment(now()->toDateString(), '09:00:00', $doctor, status: 'confirmed');
    makeAppointment(now()->toDateString(), '10:00:00', status: 'confirmed');

    expect($this->service->todayAppointmentsForRole(Role::Doctor->value))->toHaveCount(1);
});

it('scopes the today list to the authenticated patient', function () {
    $patient = Patient::factory()->create();
    $this->actingAs($patient->user);

    makeAppointment(now()->toDateString(), '09:00:00', patient: $patient, status: 'pending');
    makeAppointment(now()->toDateString(), '10:00:00', status: 'confirmed');

    $rows = $this->service->todayAppointmentsForRole(Role::Patient->value);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['status'])->toBe('pending');
});

it('returns an empty today list when the role has no related row', function () {
    $this->actingAs(User::factory()->create());

    expect($this->service->todayAppointmentsForRole(Role::Doctor->value))->toBe([]);
});

it('lists the latest registered patients ordered by creation date', function () {
    $documentType = DocumentType::factory()->create(['code' => 'DNI']);

    $newestUser = User::factory()->create(['firstname' => 'María', 'lastname' => 'Torres']);
    $newest = Patient::factory()->for($newestUser)->create([
        'document_type_id' => $documentType->id,
        'document_number' => '45678912',
    ]);
    stampCreatedAt($newest, now()->subDay());

    $olderUser = User::factory()->create(['firstname' => 'Juan', 'lastname' => 'Pérez']);
    $older = Patient::factory()->for($olderUser)->create([
        'document_type_id' => $documentType->id,
        'document_number' => '12345678',
    ]);
    stampCreatedAt($older, now()->subDays(3));

    app()->setLocale('es');

    $rows = $this->service->latestPatients();

    expect($rows)->toHaveCount(2)
        ->and($rows[0])->toMatchArray([
            'name' => 'María Torres',
            'initials' => 'MT',
            'document' => 'DNI 45678912',
        ])
        ->and($rows[1]['name'])->toBe('Juan Pérez')
        ->and($rows[0]['registered_date'])->toBe($newest->created_at->locale('es')->isoFormat('D MMM YYYY'))
        ->and($rows[0]['registered_time'])->toBe('12:00 p. m.');
});

it('caps the latest patients list at five rows', function () {
    foreach (range(1, 7) as $daysAgo) {
        stampCreatedAt(Patient::factory()->create(), now()->subDays($daysAgo));
    }

    expect($this->service->latestPatients())->toHaveCount(5);
});

it('returns an empty latest patients list when there are none', function () {
    expect($this->service->latestPatients())->toBe([]);
});
