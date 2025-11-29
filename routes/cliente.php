<?php
// filepath: routes/cliente.php

use App\Http\Controllers\Auth\ClienteLoginController;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\MisResolucionesController;
use App\Http\Controllers\Cliente\QuejaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cliente Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cliente')->name('cliente.')->group(function () {
    
    // ========================================
    // AUTENTICACIÓN CLIENTE
    // ========================================
    Route::get('login', [ClienteLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClienteLoginController::class, 'login']);
    Route::post('logout', [ClienteLoginController::class, 'logout'])->name('logout');

    // ========================================
    // RUTAS PROTEGIDAS (CLIENTE)
    // ========================================
    Route::middleware(['auth', 'tipo_acceso:cliente'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ========================================
        // MÓDULO: MIS RESOLUCIONES
        // ========================================
        Route::prefix('mis-resoluciones')->name('mis-resoluciones.')->group(function () {
            Route::get('/', [MisResolucionesController::class, 'index'])->name('index');
            Route::get('{resolucion}', [MisResolucionesController::class, 'show'])->name('show');
            Route::get('{resolucion}/descargar', [MisResolucionesController::class, 'descargar'])->name('descargar');
        });

        // ========================================
        // MÓDULO: QUEJAS
        // ========================================
        Route::resource('quejas', QuejaController::class)->only(['index', 'create', 'store']);
    });
});