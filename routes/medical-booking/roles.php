<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('/roles')->group(function ()
{
    Route::get('/', [RoleController::class, 'index'])
        ->name('roles.index');

    Route::post('/', [RoleController::class, 'store'])
        ->name('roles.create');

    Route::put('/{role}', [RoleController::class, 'update'])
        ->whereUuid('role')
        ->name('roles.update');

    Route::delete('/{role}', [RoleController::class, 'destroy'])
        ->whereUuid('role')
        ->name('roles.delete');

    Route::get('/fetch/{role}', [RoleController::class, 'fetch'])
        ->whereUuid('role')
        ->name('roles.fetch');

    Route::post('/{role}/permissions-selected', [RoleController::class, 'rolePermissionsSelected'])
        ->whereUuid('role')
        ->name('roles.permissions-selected');

    Route::put('/{role}/permissions', [RoleController::class, 'rolePermissionsUpdate'])
        ->whereUuid('role')
        ->name('roles.permissions');
});
