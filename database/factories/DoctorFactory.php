<?php

namespace Database\Factories;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'document_type_id' => DocumentType::factory(),
            'document_number' => fake()->unique()->numerify('##########'),
            'license_number' => fake()->unique()->numerify('LIC-#######'),
            'phone' => fake()->numerify('##########'),
        ];
    }
}
