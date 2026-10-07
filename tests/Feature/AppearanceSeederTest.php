<?php

use Database\Seeders\AppearanceSeeder;
use Illuminate\Support\Facades\DB;

it('seeds the default appearance', function () {
    $this->seed(AppearanceSeeder::class);

    expect(DB::table('settings')->pluck('value', 'key'))
        ->toMatchArray([
            'appearance.sidebar_style' => 'dark',
            'appearance.sidebar_color' => '',
            'appearance.accent_color' => '#3886fd',
        ]);
});
