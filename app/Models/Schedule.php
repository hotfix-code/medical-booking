<?php

namespace App\Models;

use App\Enums\Weekday;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Schedule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'doctor_id',
        'consulting_room_id',
        'specialty_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'weekday' => Weekday::class,
    ];

    protected $appends = ['time_slots'];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function consultingRoom(): BelongsTo
    {
        return $this->belongsTo(ConsultingRoom::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    protected function timeSlots(): Attribute
    {
        return Attribute::make(
            get: function ()
            {
                $slotMinutes = $this->slot_minutes ?? 60;
                $baseDate = Carbon::today();

                $start = $baseDate->copy()->setTimeFromTimeString($this->start_time);
                $end   = $baseDate->copy()->setTimeFromTimeString($this->end_time);

                if ($end->lessThanOrEqualTo($start))
                {
                    return [];
                }

                $period = CarbonPeriod::create($start, "{$slotMinutes} minutes", $end);
                $slots  = [];

                foreach ($period as $cursor)
                {
                    $slotStart = $cursor->copy();
                    $slotEnd   = $cursor->copy()->addMinutes($slotMinutes);
                    if ($slotEnd->gt($end))
                    {
                        break;
                    }
                    $slots[] = [
                        'start' => $slotStart->format('H:i:s'),
                        'end'   => $slotEnd->format('H:i:s'),
                    ];
                }

                return $slots;
            }
        );
    }
}
