<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

Route::get('/', fn() => redirect('/login'));

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

if (app()->environment('local') && config('changelog.enabled')) {
    Route::get('/changelogs', [ChangelogController::class, 'index'])
        ->middleware('auth')
        ->name('changelogs.index');
}

Route::middleware('auth')->group(function ()
{
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/profile/change-locale', [ProfileController::class, 'changeLocale'])
        ->name('profile.change-locale');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function ()
{
    $finder = new Finder();
    $finder->files()
        ->in(__DIR__ . '/medical-booking')
        ->name('*.php');

    foreach ($finder as $file)
    {
        require $file->getRealPath();
    }
});

Route::fallback(fn () => abort(404));
