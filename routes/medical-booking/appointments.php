<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;


Route::prefix('/appointments')->group(function ()
{
    Route::get('/', [AppointmentController::class, 'index'])
        ->name('appointments.index');

    Route::post('/', [AppointmentController::class, 'store'])
        ->name('appointments.create');

    Route::put('/{appointment}', [AppointmentController::class, 'update'])
        ->whereUuid('appointment')
        ->name('appointments.update');

    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])
        ->whereUuid('appointment')
        ->name('appointments.delete');

    Route::post('/fetch/{appointment}', [AppointmentController::class, 'fetch'])
        ->whereUuid('appointment')
        ->name('appointments.fetch');
});
