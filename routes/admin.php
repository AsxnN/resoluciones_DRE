<?php
// filepath: routes/admin.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GestionPrivilegiosController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// ========================================
// AUTENTICACIÓN ADMIN
// ========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Login
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // ========================================
    // RUTAS PROTEGIDAS (ADMIN)
    // ========================================
    Route::middleware(['auth', 'tipo_acceso:admin'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ========================================
        // GESTIÓN DE PRIVILEGIOS
        // ========================================
        Route::prefix('privilegios')->name('privilegios.')->group(function () {
            Route::get('/', [GestionPrivilegiosController::class, 'index'])->name('index');
            Route::get('{usuario}/gestionar', [GestionPrivilegiosController::class, 'gestionar'])->name('gestionar');
            Route::put('{usuario}/actualizar', [GestionPrivilegiosController::class, 'actualizar'])->name('actualizar');
            Route::patch('{usuario}/toggle-estado', [GestionPrivilegiosController::class, 'toggleEstado'])->name('toggle-estado');
            
            // Acciones especiales
            Route::post('copiar-permisos', [GestionPrivilegiosController::class, 'copiarPermisos'])->name('copiar-permisos');
            Route::post('{usuario}/asignar-modulo', [GestionPrivilegiosController::class, 'asignarModuloCompleto'])->name('asignar-modulo');
            Route::post('{usuario}/revocar-modulo', [GestionPrivilegiosController::class, 'revocarModuloCompleto'])->name('revocar-modulo');
        });

        // ========================================
        // REPORTES Y AUDITORÍA (OPCIONAL)
        // ========================================
        Route::prefix('reportes')->name('reportes.')->group(function () {
            // Route::get('auditoria', [ReporteController::class, 'auditoria'])->name('auditoria');
            // Route::get('actividad', [ReporteController::class, 'actividad'])->name('actividad');
        });
    });
});