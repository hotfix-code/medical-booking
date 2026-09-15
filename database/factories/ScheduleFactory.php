<?php

namespace Database\Factories;

use App\Enums\Weekday;
use App\Models\ConsultingRoom;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'specialty_id' => Specialty::factory(),
            'consulting_room_id' => ConsultingRoom::factory(),
            'weekday' => Weekday::Monday,
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
        ];
    }
}
