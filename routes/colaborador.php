<?php
// filepath: routes/colaborador.php

use App\Http\Controllers\Auth\ColaboradorLoginController;
use App\Http\Controllers\Colaborador\AreaController;
use App\Http\Controllers\Colaborador\CargoController;
use App\Http\Controllers\Colaborador\ChatbotController;
use App\Http\Controllers\Colaborador\ColaboradorController;
use App\Http\Controllers\Colaborador\DashboardController;
use App\Http\Controllers\Colaborador\DependenciaController;
use App\Http\Controllers\Colaborador\DireccionController;
use App\Http\Controllers\Colaborador\EspecialidadController;
use App\Http\Controllers\Colaborador\FirmaController;
use App\Http\Controllers\Colaborador\MisResolucionesController;
use App\Http\Controllers\Colaborador\PersonaController;
use App\Http\Controllers\Colaborador\ResolucionController;
use App\Http\Controllers\Colaborador\ResolucionFirmadaController;
use App\Http\Controllers\Colaborador\TipoPersonalController;
use App\Http\Controllers\Colaborador\UsuarioController;
use App\Http\Controllers\Colaborador\ProfileController;
use App\Http\Controllers\Colaborador\UnidadController;
use App\Http\Controllers\Colaborador\RolController;
use App\Http\Controllers\Colaborador\TipoResolucionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Colaborador Routes
|--------------------------------------------------------------------------
*/

Route::prefix('colaborador')->name('colaborador.')->group(function () {
    
    // ========================================
    // AUTENTICACIÓN COLABORADOR
    // ========================================
    Route::get('login', [ColaboradorLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ColaboradorLoginController::class, 'login']);
    Route::post('logout', [ColaboradorLoginController::class, 'logout'])->name('logout');

    // ========================================
    // RUTAS PROTEGIDAS (COLABORADOR)
    // ========================================
    Route::middleware(['auth', 'tipo_acceso:colaborador'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // ========================================
        // PERFIL DE USUARIO
        // ========================================
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show'); // ✅ AGREGAR ESTA LÍNEA
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        
        
        // Notificaciones
        Route::patch('notificaciones/{notificacion}/marcar-leida', [DashboardController::class, 'marcarNotificacionLeida'])->name('notificaciones.marcar-leida');
        Route::post('notificaciones/marcar-todas-leidas', [DashboardController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas');

        // ========================================
        // MÓDULO: PERSONAS
        // ========================================
        Route::resource('personas', PersonaController::class);
        Route::patch('personas/{persona}/toggle-estado', [PersonaController::class, 'toggleEstado'])->name('personas.toggle-estado');
        Route::get('personas/buscar/dni', [PersonaController::class, 'buscarPorDni'])->name('personas.buscar-dni');
        
        // ✅ AGREGAR RUTA DE EXPORTACIÓN
        Route::get('personas-export', [PersonaController::class, 'export'])->name('personas.export');
        // ========================================
        // MÓDULO: RESOLUCIONES
        // ========================================
        Route::resource('resoluciones', ResolucionController::class)->parameters(['resoluciones' => 'resolucion']);
        Route::get('resoluciones/{resolucion}/descargar', [ResolucionController::class, 'descargar'])->name('resoluciones.descargar');
        Route::get('resoluciones/{resolucion}/descargar-firmado', [ResolucionController::class, 'descargarFirmado'])->name('resoluciones.descargar-firmado');
        Route::patch('resoluciones/{resolucion}/cambiar-estado', [ResolucionController::class, 'cambiarEstado'])->name('resoluciones.cambiar-estado');
        Route::post('resoluciones/generar-numero', [ResolucionController::class, 'generarNumero'])->name('resoluciones.generar-numero');

        Route::get('resoluciones/crear/paso1', [ResolucionController::class, 'create'])->name('resoluciones.create');
        Route::post('resoluciones/crear/paso1', [ResolucionController::class, 'storePaso1'])->name('resoluciones.store-paso1');
        Route::get('resoluciones/crear/paso2', [ResolucionController::class, 'createPaso2'])->name('resoluciones.create-paso2');
        Route::post('resoluciones/crear/paso2', [ResolucionController::class, 'storePaso2'])->name('resoluciones.store-paso2');
        Route::get('resoluciones/crear/paso3', [ResolucionController::class, 'createPaso3'])->name('resoluciones.create-paso3');
        Route::post('resoluciones/crear/guardar', [ResolucionController::class, 'storeFinal'])->name('resoluciones.store-final');

        // Firmas (CORREGIDO)
        Route::prefix('firma')->name('firma.')->group(function () {
            Route::get('/', [FirmaController::class, 'index'])->name('index');
            Route::get('/firmar', [FirmaController::class, 'create'])->name('firmar');
            Route::post('/', [FirmaController::class, 'store'])->name('store');
            Route::delete('/{firma}', [FirmaController::class, 'destroy'])->name('destroy');
        });

        // ========================================
        // MÓDULO: FIRMAS DIGITALES
        // ========================================
        Route::prefix('resoluciones-firmadas')->name('resoluciones-firmadas.')->group(function () {
            Route::get('/', [ResolucionFirmadaController::class, 'index'])->name('index');
            Route::get('cola-firma/{colaFirma}/firmar', [ResolucionFirmadaController::class, 'mostrarFormularioFirma'])->name('mostrar-firma');
            Route::post('cola-firma/{colaFirma}/firmar', [ResolucionFirmadaController::class, 'firmar'])->name('firmar');
            Route::post('cola-firma/{colaFirma}/rechazar', [ResolucionFirmadaController::class, 'rechazar'])->name('rechazar');
            Route::post('resoluciones/{resolucion}/solicitar-firma', [ResolucionFirmadaController::class, 'solicitarFirma'])->name('solicitar-firma');
            Route::get('resoluciones/{resolucion}/historial', [ResolucionFirmadaController::class, 'historial'])->name('historial');
            Route::get('resoluciones/{resolucion}/verificar-firma', [ResolucionFirmadaController::class, 'verificarFirma'])->name('verificar-firma');
        });

        // ========================================
        // MÓDULO: MIS RESOLUCIONES
        // ========================================
        Route::get('mis-resoluciones', [MisResolucionesController::class, 'index'])->name('mis-resoluciones.index');

        // ========================================
        // MÓDULO: CHATBOT IA
        // ========================================
        Route::prefix('chatbot')->name('chatbot.')->group(function () {
            Route::get('/', [ChatbotController::class, 'index'])->name('index');
            Route::post('consultar', [ChatbotController::class, 'consultar'])->name('consultar');
            Route::post('buscar-resoluciones', [ChatbotController::class, 'buscarResoluciones'])->name('buscar-resoluciones');
            Route::post('consultas/{consulta}/valorar', [ChatbotController::class, 'valorar'])->name('valorar');
        });

        // ========================================
        // CATÁLOGOS: ÁREAS
        // ========================================
        Route::resource('areas', AreaController::class);
        Route::patch('areas/{area}/toggle-estado', [AreaController::class, 'toggleEstado'])->name('areas.toggle-estado');

        // ========================================
        // CATÁLOGOS: CARGOS
        // ========================================
        Route::resource('cargos', CargoController::class);
        Route::patch('cargos/{cargo}/toggle-estado', [CargoController::class, 'toggleEstado'])->name('cargos.toggle-estado');

        // ========================================
        // CATÁLOGOS: DEPENDENCIAS
        // ========================================
        Route::resource('dependencias', DependenciaController::class);
        Route::patch('dependencias/{dependencia}/toggle-estado', [DependenciaController::class, 'toggleEstado'])->name('dependencias.toggle-estado');

        // ========================================
        // CATÁLOGOS: DIRECCIONES
        // ========================================
        Route::resource('direcciones', DireccionController::class);
        Route::patch('direcciones/{direccion}/toggle-estado', [DireccionController::class, 'toggleEstado'])->name('direcciones.toggle-estado');

        // ========================================
        // CATÁLOGOS: ESPECIALIDADES
        // ========================================
        Route::resource('especialidades', EspecialidadController::class);
        Route::patch('especialidades/{especialidad}/toggle-estado', [EspecialidadController::class, 'toggleEstado'])->name('especialidades.toggle-estado');

        // ========================================
        // CATÁLOGOS: TIPOS DE PERSONAL
        // ========================================
        Route::resource('tipos-personal', TipoPersonalController::class);
        Route::patch('tipos-personal/{tipoPersonal}/toggle-estado', [TipoPersonalController::class, 'toggleEstado'])->name('tipos-personal.toggle-estado');

        // ========================================
        // MÓDULO: COLABORADORES
        // ========================================
        Route::resource('colaboradores', ColaboradorController::class);
        Route::patch('colaboradores/{colaborador}/toggle-estado', [ColaboradorController::class, 'toggleEstado'])->name('colaboradores.toggle-estado');


        // Unidades
        Route::resource('unidades', UnidadController::class);
        // ========================================
        // MÓDULO: USUARIOS
        // ========================================
        Route::resource('usuarios', UsuarioController::class);
        Route::patch('usuarios/{usuario}/toggle-estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.toggle-estado');
        Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');
        
        // Tipos de Resolución
        Route::resource('tipos-resolucion', TipoResolucionController::class)->parameters([
            'tipos-resolucion' => 'tipoResolucion'
        ]);

        // Roles
        Route::resource('roles', RolController::class);
    });
});