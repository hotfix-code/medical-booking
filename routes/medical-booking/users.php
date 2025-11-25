<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('/users')->group(function ()
{
    Route::get('/', [UserController::class, 'index'])
        ->name('users.index');

    Route::post('/', [UserController::class, 'store'])
        ->name('users.create');

    Route::put('/{user}', [UserController::class, 'update'])
        ->whereUuid('user')
        ->name('users.update');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->whereUuid('user')
        ->name('users.delete');

    Route::post('/fetch/{user}', [UserController::class, 'fetch'])
        ->whereUuid('user')
        ->name('users.fetch');
});
