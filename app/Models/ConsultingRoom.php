<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class ConsultingRoom extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'location',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Scope to get consulting rooms for a specific doctor
     */
    public function scopeForDoctor(Builder $query, string $doctorId): Builder
    {
        return $query->whereHas('schedules', function ($query) use ($doctorId)
        {
            $query->where('doctor_id', $doctorId);
        });
    }

    /**
     * Get consulting rooms that are assigned to a specific doctor
     */
    public static function getForDoctor(string $doctorId)
    {
        return static::forDoctor($doctorId)->get();
    }
}
