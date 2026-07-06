<?php
// filepath: routes/admin.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\GestionPrivilegiosController;
use App\Http\Controllers\Admin\ClienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

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
        // CLIENTES
        // ========================================
        Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');

        // ========================================
        // AUDITORÍA
        // ========================================
        Route::prefix('auditoria')->name('auditoria.')->group(function () {
            Route::get('/', [AuditoriaController::class, 'index'])->name('index');
            Route::get('/exportar', [AuditoriaController::class, 'exportar'])->name('exportar');
            Route::delete('/limpiar', [AuditoriaController::class, 'limpiar'])->name('limpiar');
            Route::get('/{auditoria}', [AuditoriaController::class, 'show'])->name('show');
        });

        // ========================================
        // ALIAS (PARA COMPATIBILIDAD CON VISTAS)
        // ========================================
        // Usuarios -> Privilegios
        Route::get('usuarios', fn() => redirect()->route('admin.privilegios.index'))->name('usuarios.index');
        Route::get('usuarios/{usuario}/editar', fn($usuario) => redirect()->route('admin.privilegios.gestionar', $usuario))->name('usuarios.edit');
        
        // Módulos -> Privilegios
        Route::get('modulos', fn() => redirect()->route('admin.privilegios.index'))->name('modulos.index');

        // Permisos -> Privilegios (NUEVO)
        Route::get('permisos', fn() => redirect()->route('admin.privilegios.index'))->name('permisos.index');

        // ========================================
        // REPORTES Y AUDITORÍA (FUTURO)
        // ========================================
        Route::prefix('reportes')->name('reportes.')->group(function () {
            // Route::get('auditoria', [ReporteController::class, 'auditoria'])->name('auditoria');
            // Route::get('actividad', [ReporteController::class, 'actividad'])->name('actividad');
        });
    });
});