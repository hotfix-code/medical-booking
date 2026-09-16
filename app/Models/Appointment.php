<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'specialty_id',
        'schedule_id',
        'consulting_room_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'doctor_slot_key',
        'room_slot_key',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
        'is_active' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function consultingRoom(): BelongsTo
    {
        return $this->belongsTo(ConsultingRoom::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            AppointmentStatus::Pending->value,
            AppointmentStatus::Confirmed->value
        ]);
    }

    public function scopeForDoctor(Builder $query, string $doctorId): Builder
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient(Builder $query, string $patientId): Builder
    {
        return $query->where('patient_id', $patientId);
    }

    public static function getForDoctor(string $doctorId)
    {
        return static::forDoctor($doctorId)->get();
    }

    public static function getForPatient(string $patientId)
    {
        return static::forPatient($patientId)->get();
    }

    public static function countForDoctor(string $doctorId): int
    {
        return static::forDoctor($doctorId)->count();
    }

    public static function countForPatient(string $patientId): int
    {
        return static::forPatient($patientId)->count();
    }
}
