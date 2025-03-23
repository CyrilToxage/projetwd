<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\FormulaireController;
use App\Http\Controllers\FormulairePublicController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.formulaires.index');
    })->name('dashboard');
});

// Routes publiques
Route::get('/f/{token}', [FormulairePublicController::class, 'show'])->name('formulaire.repondre');
Route::post('/f/{token}/submit', [FormulairePublicController::class, 'submit'])->name('formulaire.submit');
Route::get('/merci', [FormulairePublicController::class, 'merci'])->name('formulaire.merci');

// Routes admin (protégées par authentification)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Page d'accueil après connexion
    Route::get('/dashboard', function () {
        return redirect()->route('admin.formulaires.index');
    })->name('dashboard');

    // Routes des formulaires
    Route::resource('admin/formulaires', FormulaireController::class)->names('admin.formulaires');
    Route::post('/admin/formulaires/{formulaire}/generate-link', [FormulaireController::class, 'generateLink'])
        ->name('admin.formulaires.generate-link');
    Route::get('/admin/formulaires/{formulaire}/export', [FormulaireController::class, 'export'])
        ->name('admin.formulaires.export');
});
