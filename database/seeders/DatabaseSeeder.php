<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

            if (config('changelog.enabled')) {
                $this->call(ChangelogSeeder::class);
            }
        }

        if (app()->environment('production')) {
            $this->call(ProductionSeeder::class);
        }
    }
}
