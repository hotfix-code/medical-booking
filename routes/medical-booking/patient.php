<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;


Route::prefix('/patients')->group(function ()
{
    Route::get('/', [PatientController::class, 'index'])
        ->name('patients.index');

    Route::post('/', [PatientController::class, 'store'])
        ->name('patients.store');

    Route::put('/{patient}', [PatientController::class, 'update'])
        ->whereUuid('patient')
        ->name('patients.update');

    Route::delete('/{patient}', [PatientController::class, 'destroy'])
        ->whereUuid('patient')
        ->name('patients.delete');

    Route::post('/fetch/{patient}', [PatientController::class, 'fetch'])
        ->whereUuid('patient')
        ->name('patients.fetch');

    Route::post('/fetch-all', [PatientController::class, 'fetchAll'])
        ->name('patients.fetch-all');
});
