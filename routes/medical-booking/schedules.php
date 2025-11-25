<?php

use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;


Route::prefix('/schedules')->group(function ()
{
    Route::get('/', [ScheduleController::class, 'index'])
        ->name('schedules.index');

    Route::post('/', [ScheduleController::class, 'store'])
        ->name('schedules.create');

    Route::put('/{schedule}', [ScheduleController::class, 'update'])
        ->whereUuid('schedule')
        ->name('schedules.update');

    Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])
        ->whereUuid('schedule')
        ->name('schedules.delete');

    Route::post('/fetch/{schedule}', [ScheduleController::class, 'fetch'])
        ->whereUuid('schedule')
        ->name('schedules.fetch');

    Route::post('/fetch-all/consulting-rooms/{consultingRoom}', [ScheduleController::class, 'fetchAllByConsultingRoom'])
        ->whereUuid('consultingRoom')
        ->name('schedules.fetch-all-by-consulting-room');
});
