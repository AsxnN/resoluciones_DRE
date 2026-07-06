<?php
// filepath: routes/cliente.php

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

    // Auto-registro de cliente (público, sin auth)
    Route::get('register', [\App\Http\Controllers\Auth\ClienteRegisterController::class, 'showForm'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\ClienteRegisterController::class, 'register']);

    // ========================================
    // RUTAS PROTEGIDAS (CLIENTE)
    // ========================================
    Route::middleware(['auth', 'tipo_acceso:cliente'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ========================================
        // MÓDULO: BÚSQUEDA DE RESOLUCIONES
        // ========================================
        Route::prefix('busqueda')->name('busqueda.')->group(function () {
            Route::get('/', [MisResolucionesController::class, 'buscar'])->name('index');
        });

        // ========================================
        // MÓDULO: MIS RESOLUCIONES (ALIAS)
        // ========================================
        Route::prefix('resoluciones')->name('resoluciones.')->group(function () {
            Route::get('/', [MisResolucionesController::class, 'index'])->name('index');
            Route::get('{resolucion}', [MisResolucionesController::class, 'show'])->name('show');
            Route::get('{resolucion}/descargar', [MisResolucionesController::class, 'descargar'])->name('descargar');
        });

        // ========================================
        // MÓDULO: MIS RESOLUCIONES (RUTA ORIGINAL - MANTENER POR COMPATIBILIDAD)
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