<?php

use App\Http\Controllers\DocumentTypeController;
use Illuminate\Support\Facades\Route;


Route::prefix('/document-types')->group(function ()
{
    Route::get('/', [DocumentTypeController::class, 'index'])
        ->name('document-types.index');

    Route::post('/', [DocumentTypeController::class, 'store'])
        ->name('document-types.create');

    Route::put('/{documentType}', [DocumentTypeController::class, 'update'])
        ->whereUuid('documentType')
        ->name('document-types.update');

    Route::delete('/{documentType}', [DocumentTypeController::class, 'destroy'])
        ->whereUuid('documentType')
        ->name('document-types.delete');

    Route::post('/fetch/{documentType}', [DocumentTypeController::class, 'fetch'])
        ->whereUuid('documentType')
        ->name('document-types.fetch');

    Route::post('/fetch-all', [DocumentTypeController::class, 'fetchAll'])
        ->name('document-types.fetch-all');
});
