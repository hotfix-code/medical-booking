<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppearanceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('settings')->insert([
            [
                'key' => 'appearance.sidebar_style',
                'value' => 'dark',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'appearance.sidebar_color',
                'value' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'appearance.accent_color',
                'value' => '#3886fd',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
