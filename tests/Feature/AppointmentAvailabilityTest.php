<?php

use App\Enums\Weekday;
use App\Models\Appointment;
use App\Models\ConsultingRoom;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Services\AppointmentAvailabilityService;
use App\Services\AppointmentService;

beforeEach(function () {
    $this->specialty = Specialty::factory()->create();
    $this->doctor = Doctor::factory()->create();
    $this->doctor->specialties()->attach($this->specialty);
    $this->room = ConsultingRoom::factory()->create();
    $this->schedule = Schedule::factory()->create([
        'doctor_id' => $this->doctor->id,
        'specialty_id' => $this->specialty->id,
        'consulting_room_id' => $this->room->id,
        'weekday' => Weekday::Monday,
        'start_time' => '08:00:00',
        'end_time' => '17:00:00',
    ]);
    $this->date = now()->startOfWeek()->addWeek()->toDateString();
    $this->appointments = app(AppointmentService::class);
    $this->availability = app(AppointmentAvailabilityService::class);
});

function availabilityPayload(string $date, object $context, string $time = '10:00:00', array $overrides = []): array
{
    return array_merge([
        'patient_id' => Patient::factory()->create()->id,
        'doctor_id' => $context->doctor->id,
        'specialty_id' => $context->specialty->id,
        'schedule_id' => $context->schedule->id,
        'consulting_room_id' => $context->room->id,
        'appointment_date' => $date,
        'appointment_time' => $time,
        'status' => 'pending',
        'notes' => null,
    ], $overrides);
}

function slotsOf($response): \Illuminate\Support\Collection
{
    return collect($response->getData(true)['data']['schedules'])
        ->flatMap(fn (array $schedule) => $schedule['time_slots']);
}

it('hides a slot occupied by a completed appointment', function () {
    $this->appointments->create(availabilityPayload($this->date, $this));

    $appointment = Appointment::firstOrFail();
    $this->appointments->update($appointment, ['status' => 'completed']);

    $response = $this->availability->getDoctorCompleteAvailability($this->doctor->id, $this->date);
    $slot = slotsOf($response)->firstWhere('start', '10:00:00');

    expect($slot)->not->toBeNull()
        ->and($slot['is_available'])->toBeFalse();
});

it('shows the slot again after the appointment is cancelled', function () {
    $this->appointments->create(availabilityPayload($this->date, $this));

    $appointment = Appointment::firstOrFail();
    $this->appointments->update($appointment, ['status' => 'cancelled']);

    $response = $this->availability->getDoctorCompleteAvailability($this->doctor->id, $this->date);
    $slot = slotsOf($response)->firstWhere('start', '10:00:00');

    expect($slot)->not->toBeNull()
        ->and($slot['is_available'])->toBeTrue();
});

it('hides the time in another room when the doctor is already booked', function () {
    $otherRoom = ConsultingRoom::factory()->create();
    Schedule::factory()->create([
        'doctor_id' => $this->doctor->id,
        'specialty_id' => $this->specialty->id,
        'consulting_room_id' => $otherRoom->id,
        'weekday' => Weekday::Monday,
        'start_time' => '08:00:00',
        'end_time' => '17:00:00',
    ]);

    $this->appointments->create(availabilityPayload($this->date, $this));

    $response = $this->availability->getDoctorCompleteAvailability($this->doctor->id, $this->date);

    $otherRoomSlots = collect($response->getData(true)['data']['schedules'])
        ->firstWhere('consulting_room_id', $otherRoom->id)['time_slots'];

    $slot = collect($otherRoomSlots)->firstWhere('start', '10:00:00');

    expect($slot)->not->toBeNull()
        ->and($slot['is_available'])->toBeFalse();
});
