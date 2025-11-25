<?php

use App\Http\Controllers\ConsultingRoomController;
use Illuminate\Support\Facades\Route;


Route::prefix('/consulting-rooms')->group(function ()
{
    Route::get('/', [ConsultingRoomController::class, 'index'])
        ->name('consulting-rooms.index');

    Route::post('/', [ConsultingRoomController::class, 'store'])
        ->name('consulting-rooms.create');

    Route::put('/{consultingRoom}', [ConsultingRoomController::class, 'update'])
        ->whereUuid('consultingRoom')
        ->name('consulting-rooms.update');

    Route::delete('/{consultingRoom}', [ConsultingRoomController::class, 'destroy'])
        ->whereUuid('consultingRoom')
        ->name('consulting-rooms.delete');

    Route::post('/fetch/{consultingRoom}', [ConsultingRoomController::class, 'fetch'])
        ->whereUuid('consultingRoom')
        ->name('consulting-rooms.fetch');
});
