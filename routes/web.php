<?php
// filepath: routes/web.php

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Públicas y Redirecciones)
|--------------------------------------------------------------------------
*/

// Ruta raíz - redirige al login según contexto
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return redirect()->route(match($user->tipo_acceso) {
            'admin' => 'admin.dashboard',
            'colaborador' => 'colaborador.dashboard',
            'cliente' => 'cliente.dashboard',
            default => 'login',
        });
    }
    return redirect()->route('login'); // Redirige al login unificado
});

// Logout universal
Route::post('/logout', LogoutController::class)->name('logout');

// Redirecciones de Jetstream (si se usa)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return redirect()->route(match($user->tipo_acceso) {
            'admin' => 'admin.dashboard',
            'colaborador' => 'colaborador.dashboard',
            'cliente' => 'cliente.dashboard',
            default => 'login',
        });
    })->name('dashboard');
});

// Rutas de perfil (Jetstream)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});