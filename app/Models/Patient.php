<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Patient extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'document_type_id',
        'document_number',
        'gender',
        'birthdate',
        'phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope to get patients that have appointments with a specific doctor
     */
    public function scopeForDoctor(Builder $query, string $doctorId): Builder
    {
        return $query->whereHas('appointments', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->distinct();
    }

    /**
     * Scope to get patients with active appointments for a specific doctor
     */
    public function scopeWithActiveAppointmentsForDoctor(Builder $query, string $doctorId): Builder
    {
        return $query->whereHas('appointments', function ($query) use ($doctorId)
        {
            $query->where('doctor_id', $doctorId)
                  ->where('appointment_date', '>=', now());
        })
            ->distinct();
    }

    /**
     * Get patients that have appointments with a specific doctor
     */
    public static function getForDoctor(string $doctorId)
    {
        return static::forDoctor($doctorId)->get();
    }

    /**
     * Get count of patients for a specific doctor
     */
    public static function countForDoctor(string $doctorId): int
    {
        return static::forDoctor($doctorId)->count();
    }

    /**
     * Check if a patient has appointments with a specific doctor
     */
    public function hasAppointmentsWith(string $doctorId): bool
    {
        return $this->appointments()->where('doctor_id', $doctorId)->exists();
    }
}
