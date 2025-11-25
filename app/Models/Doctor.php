<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'document_type_id',
        'document_number',
        'license_number',
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

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(
            Specialty::class,
            'doctor_specialty',
            'doctor_id',
            'specialty_id'
        )->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class)
            ->orderBy('weekday')
            ->orderBy('start_time');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
