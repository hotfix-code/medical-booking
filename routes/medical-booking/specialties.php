<?php

use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;


Route::prefix('/specialties')->group(function ()
{
    Route::get('/', [SpecialtyController::class, 'index'])
        ->name('specialties.index');

    Route::post('/', [SpecialtyController::class, 'store'])
        ->name('specialties.create');

    Route::put('/{specialty}', [SpecialtyController::class, 'update'])
        ->whereUuid('specialty')
        ->name('specialties.update');

    Route::delete('/{specialty}', [SpecialtyController::class, 'destroy'])
        ->whereUuid('specialty')
        ->name('specialties.delete');

    Route::post('/fetch/{specialty}', [SpecialtyController::class, 'fetch'])
        ->whereUuid('specialty')
        ->name('specialties.fetch');

    Route::post('/fetch/{specialty}/doctors', [SpecialtyController::class, 'fetchWithDoctors'])
        ->whereUuid('specialty')
        ->name('specialties.fetch-with-doctors');
});
