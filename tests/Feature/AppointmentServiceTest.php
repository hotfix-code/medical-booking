<?php

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\ConsultingRoom;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->specialty = Specialty::factory()->create();
    $this->doctor = Doctor::factory()->create();
    $this->doctor->specialties()->attach($this->specialty);
    $this->room = ConsultingRoom::factory()->create();
    $this->schedule = Schedule::factory()->create([
        'doctor_id' => $this->doctor->id,
        'specialty_id' => $this->specialty->id,
        'consulting_room_id' => $this->room->id,
    ]);
    $this->service = app(AppointmentService::class);
});

function buildAppointmentPayload(Doctor $doctor, Specialty $specialty, Schedule $schedule, ConsultingRoom $room, Patient $patient, array $overrides = []): array
{
    return array_merge([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'specialty_id' => $specialty->id,
        'schedule_id' => $schedule->id,
        'consulting_room_id' => $room->id,
        'appointment_date' => now()->addWeek()->toDateString(),
        'appointment_time' => '10:00:00',
        'status' => 'pending',
        'notes' => null,
    ], $overrides);
}

function makeSecondaryDoctor(Specialty $specialty, ConsultingRoom $room): array
{
    $doctor = Doctor::factory()->create();
    $doctor->specialties()->attach($specialty);

    $schedule = Schedule::factory()->create([
        'doctor_id' => $doctor->id,
        'specialty_id' => $specialty->id,
        'consulting_room_id' => $room->id,
    ]);

    return [$doctor, $specialty, $schedule, $room];
}

it('creates an appointment and hashes the doctor and room slot keys', function () {
    $patient = Patient::factory()->create();

    $response = $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patient)
    );

    expect($response->getData(true)['status'])->toBeTrue();

    $appointment = Appointment::firstOrFail();
    $expectedDoctorKey = hash('sha256', sprintf(
        '%s-%s-%s',
        $this->doctor->id,
        $appointment->appointment_date,
        $appointment->appointment_time
    ));

    expect($appointment->doctor_slot_key)->toBe($expectedDoctorKey)
        ->and($appointment->room_slot_key)->not->toBeNull();
});

it('confirms a pending appointment when only the status is sent', function () {
    $patient = Patient::factory()->create();

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patient)
    );

    $appointment = Appointment::firstOrFail();
    $response = $this->service->update($appointment, ['status' => 'confirmed']);

    expect($response->getData(true)['status'])->toBeTrue();

    $appointment->refresh();
    $expectedDoctorKey = hash('sha256', sprintf(
        '%s-%s-%s',
        $this->doctor->id,
        $appointment->appointment_date,
        $appointment->appointment_time
    ));

    expect($appointment->status)->toBe(AppointmentStatus::Confirmed)
        ->and($appointment->doctor_slot_key)->toBe($expectedDoctorKey);
});

it('rejects the same doctor at the same time even in another specialty', function () {
    $patientA = Patient::factory()->create();
    $patientB = Patient::factory()->create();

    $otherSpecialty = Specialty::factory()->create();
    $otherRoom = ConsultingRoom::factory()->create();
    $otherSchedule = Schedule::factory()->create([
        'doctor_id' => $this->doctor->id,
        'specialty_id' => $otherSpecialty->id,
        'consulting_room_id' => $otherRoom->id,
    ]);

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientA)
    );

    $response = $this->service->create(
        buildAppointmentPayload($this->doctor, $otherSpecialty, $otherSchedule, $otherRoom, $patientB)
    );

    expect($response->getData(true)['status'])->toBeFalse()
        ->and($response->getData(true)['errors'])->toHaveKey('slot')
        ->and(Appointment::count())->toBe(1);
});

it('rejects the same room at the same time across doctors', function () {
    $patientA = Patient::factory()->create();
    $patientB = Patient::factory()->create();

    $otherSpecialty = Specialty::factory()->create();
    [$otherDoctor, , $otherSchedule] = makeSecondaryDoctor($otherSpecialty, $this->room);

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientA)
    );

    $response = $this->service->create(
        buildAppointmentPayload($otherDoctor, $otherSpecialty, $otherSchedule, $this->room, $patientB)
    );

    expect($response->getData(true)['status'])->toBeFalse()
        ->and($response->getData(true)['errors'])->toHaveKey('slot')
        ->and(Appointment::count())->toBe(1);
});

it('frees the slot when the appointment is cancelled', function () {
    $patientA = Patient::factory()->create();
    $patientB = Patient::factory()->create();

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientA)
    );

    $appointment = Appointment::firstOrFail();
    $this->service->update($appointment, ['status' => 'cancelled']);

    expect($appointment->fresh()->doctor_slot_key)->toBeNull()
        ->and($appointment->fresh()->room_slot_key)->toBeNull();

    $response = $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientB)
    );

    expect($response->getData(true)['status'])->toBeTrue()
        ->and(Appointment::count())->toBe(2);
});

it('does not free the slot when the appointment is completed', function () {
    $patientA = Patient::factory()->create();
    $patientB = Patient::factory()->create();

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientA)
    );

    $appointment = Appointment::firstOrFail();
    $this->service->update($appointment, ['status' => 'completed']);

    expect($appointment->fresh()->doctor_slot_key)->not->toBeNull()
        ->and($appointment->fresh()->room_slot_key)->not->toBeNull();

    $response = $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientB)
    );

    expect($response->getData(true)['status'])->toBeFalse()
        ->and($response->getData(true)['errors'])->toHaveKey('slot')
        ->and(Appointment::count())->toBe(1);
});

it('does not free the slot when the appointment is a no-show', function () {
    $patientA = Patient::factory()->create();
    $patientB = Patient::factory()->create();

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientA)
    );

    $appointment = Appointment::firstOrFail();
    $this->service->update($appointment, ['status' => 'no_show']);

    expect($appointment->fresh()->doctor_slot_key)->not->toBeNull()
        ->and($appointment->fresh()->room_slot_key)->not->toBeNull();

    $response = $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patientB)
    );

    expect($response->getData(true)['status'])->toBeFalse()
        ->and($response->getData(true)['errors'])->toHaveKey('slot')
        ->and(Appointment::count())->toBe(1);
});

it('blocks a second active appointment for the same patient on the same day by default', function () {
    $patient = Patient::factory()->create();

    $otherSpecialty = Specialty::factory()->create();
    $otherRoom = ConsultingRoom::factory()->create();
    [$otherDoctor, , $otherSchedule] = makeSecondaryDoctor($otherSpecialty, $otherRoom);

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patient)
    );

    $response = $this->service->create(
        buildAppointmentPayload($otherDoctor, $otherSpecialty, $otherSchedule, $otherRoom, $patient, [
            'appointment_time' => '11:00:00',
        ])
    );

    expect($response->getData(true)['status'])->toBeFalse()
        ->and($response->getData(true)['errors'])->toHaveKey('conflict')
        ->and(Appointment::count())->toBe(1);
});

it('allows multiple active appointments per day when the setting is enabled', function () {
    DB::table('settings')->insert([
        'key' => 'appointments.allow_multiple_per_day',
        'value' => 'true',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $patient = Patient::factory()->create();

    $otherSpecialty = Specialty::factory()->create();
    $otherRoom = ConsultingRoom::factory()->create();
    [$otherDoctor, , $otherSchedule] = makeSecondaryDoctor($otherSpecialty, $otherRoom);

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patient)
    );

    $response = $this->service->create(
        buildAppointmentPayload($otherDoctor, $otherSpecialty, $otherSchedule, $otherRoom, $patient, [
            'appointment_time' => '11:00:00',
        ])
    );

    expect($response->getData(true)['status'])->toBeTrue()
        ->and(Appointment::count())->toBe(2);
});

it('enforces the configured minimum days between appointments', function () {
    DB::table('settings')->insert([
        'key' => 'appointments.min_days_between',
        'value' => '3',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $patient = Patient::factory()->create();
    $date = now()->addWeek()->startOfDay();

    $otherSpecialty = Specialty::factory()->create();
    $otherRoom = ConsultingRoom::factory()->create();
    [$otherDoctor, , $otherSchedule] = makeSecondaryDoctor($otherSpecialty, $otherRoom);

    $this->service->create(
        buildAppointmentPayload($this->doctor, $this->specialty, $this->schedule, $this->room, $patient, [
            'appointment_date' => $date->toDateString(),
        ])
    );

    $tooSoon = $this->service->create(
        buildAppointmentPayload($otherDoctor, $otherSpecialty, $otherSchedule, $otherRoom, $patient, [
            'appointment_date' => $date->copy()->addDay()->toDateString(),
            'appointment_time' => '11:00:00',
        ])
    );

    expect($tooSoon->getData(true)['status'])->toBeFalse()
        ->and($tooSoon->getData(true)['errors'])->toHaveKey('conflict');

    $allowed = $this->service->create(
        buildAppointmentPayload($otherDoctor, $otherSpecialty, $otherSchedule, $otherRoom, $patient, [
            'appointment_date' => $date->copy()->addDays(3)->toDateString(),
            'appointment_time' => '11:00:00',
        ])
    );

    expect($allowed->getData(true)['status'])->toBeTrue()
        ->and(Appointment::count())->toBe(2);
});
