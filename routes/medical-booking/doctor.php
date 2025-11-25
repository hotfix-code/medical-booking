<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;


Route::prefix('/doctors')->group(function ()
{
    Route::get('/', [DoctorController::class, 'index'])
        ->name('doctors.index');

    Route::post('/', [DoctorController::class, 'store'])
        ->name('doctors.store');

    Route::put('/{doctor}', [DoctorController::class, 'update'])
        ->whereUuid('doctor')
        ->name('doctors.update');

    Route::delete('/{doctor}', [DoctorController::class, 'destroy'])
        ->whereUuid('doctor')
        ->name('doctors.delete');

    Route::post('/fetch/{doctor}', [DoctorController::class, 'fetch'])
        ->whereUuid('doctor')
        ->name('doctors.fetch');


    Route::post('/fetch/{doctor}/specialties/{specialty}', [DoctorController::class, 'fetchBySpecialty'])
        ->whereUuid('doctor')
        ->name('doctors.fetch');

    Route::post('/fetch/{doctor}/availability', [DoctorController::class, 'fetchWithAvailability'])
        ->whereUuid('doctor')
        ->name('doctors.fetch.availability');

    Route::post('/fetch/{doctor}/slots', [DoctorController::class, 'availableSlots'])
        ->whereUuid('doctor')
        ->name('doctors.fetch.slots');
});
