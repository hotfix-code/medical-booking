<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local') || app()->environment('development')) {
            $this->call(DevelopmentSeeder::class);
        }

        if (app()->environment('production')) {
            $this->call(ProductionSeeder::class);
        }

        if (app()->environment(['local', 'development', 'production'])) {
            $this->call(AppearanceSeeder::class);
        }
    }
}
