<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('/settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::put('/appearance', [SettingController::class, 'updateAppearance'])
        ->name('settings.appearance.update');
});
