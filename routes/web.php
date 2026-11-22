<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Responsable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Redirige vers l'espace correspondant au rôle après la connexion
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    return $user->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('responsable.recherche');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ----- Espace responsable de classe -----
Route::middleware(['auth', 'role:responsable'])
    ->prefix('responsable')->name('responsable.')->group(function () {
        Route::get('/recherche', [Responsable\RechercheController::class, 'index'])
            ->name('recherche');
        Route::get('/reservations',
            [Responsable\ReservationController::class, 'index'])
            ->name('reservations.index');
        Route::post('/reservations',
            [Responsable\ReservationController::class, 'store'])
            ->name('reservations.store');
        Route::delete('/reservations/{reservation}',
            [Responsable\ReservationController::class, 'annuler'])
            ->name('reservations.annuler');
        Route::get('/historique',
            [Responsable\ReservationController::class, 'historique'])
            ->name('historique');
    });

// ----- Espace administration -----
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        Route::resource('salles', Admin\SalleController::class)
            ->except(['show']);
        Route::get('/demandes', [Admin\DemandeController::class, 'index'])
            ->name('demandes');
        Route::post('/demandes/{reservation}/approuver',
            [Admin\DemandeController::class, 'approuver'])
            ->name('demandes.approuver');
        Route::post('/demandes/{reservation}/refuser',
            [Admin\DemandeController::class, 'refuser'])
            ->name('demandes.refuser');
        Route::get('/statistiques',
            [Admin\StatistiqueController::class, 'index'])
            ->name('statistiques');
    });

require __DIR__.'/auth.php';
