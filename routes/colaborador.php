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
use App\Http\Controllers\Colaborador\ReporteController;
use Illuminate\Support\Facades\Route;

Route::prefix('colaborador')->name('colaborador.')->group(function () {
    
    // AUTENTICACIÓN
    Route::get('login', [ColaboradorLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ColaboradorLoginController::class, 'login']);
    Route::post('logout', [ColaboradorLoginController::class, 'logout'])->name('logout');

    // RUTAS PROTEGIDAS
    Route::middleware(['auth', 'tipo_acceso:colaborador'])->group(function () {
        
        // ========================================
        // PERFIL - SIEMPRE ACCESIBLE (Sin middleware de perfil completo)
        // ========================================
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        });
        
        // ========================================
        // TODAS LAS DEMÁS RUTAS REQUIEREN PERFIL COMPLETO
        // ========================================
        Route::middleware(['perfil.completo'])->group(function () {
            
            // Dashboard
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            // Notificaciones
            Route::patch('notificaciones/{notificacion}/marcar-leida', [DashboardController::class, 'marcarNotificacionLeida'])->name('notificaciones.marcar-leida');
            Route::post('notificaciones/marcar-todas-leidas', [DashboardController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas');

            // PERSONAS
            Route::resource('personas', PersonaController::class);
            Route::patch('personas/{persona}/toggle-estado', [PersonaController::class, 'toggleEstado'])->name('personas.toggle-estado');
            Route::get('personas/buscar/dni', [PersonaController::class, 'buscarPorDni'])->name('personas.buscar-dni');
            Route::get('personas-export', [PersonaController::class, 'export'])->name('personas.export');
            
            // RESOLUCIONES

            Route::get('resoluciones/crear/paso1', [ResolucionController::class, 'create'])->name('resoluciones.create');
            Route::post('resoluciones/crear/paso1', [ResolucionController::class, 'storePaso1'])->name('resoluciones.store-paso1');
            Route::get('resoluciones/crear/paso2', [ResolucionController::class, 'createPaso2'])->name('resoluciones.create-paso2');
            Route::post('resoluciones/crear/paso2', [ResolucionController::class, 'storePaso2'])->name('resoluciones.store-paso2');
            Route::get('resoluciones/crear/paso3', [ResolucionController::class, 'createPaso3'])->name('resoluciones.create-paso3');
            Route::post('resoluciones/crear/guardar', [ResolucionController::class, 'storeFinal'])->name('resoluciones.store-final');
            Route::post('resoluciones/firmar-masivo', [ResolucionController::class, 'firmarMasivo'])->name('resoluciones.firmarMasivo');
            Route::get('resoluciones/revisar-firma', [ResolucionController::class, 'revisarFirma'])->name('resoluciones.revisar-firma');

            Route::resource('resoluciones', ResolucionController::class)->parameters(['resoluciones' => 'resolucion']);
            
            Route::get('resoluciones/{resolucion}/descargar', [ResolucionController::class, 'descargar'])->name('resoluciones.descargar');
            Route::get('resoluciones/{resolucion}/descargar-firmado', [ResolucionController::class, 'descargarFirmado'])->name('resoluciones.descargar-firmado');
            Route::patch('resoluciones/{resolucion}/cambiar-estado', [ResolucionController::class, 'cambiarEstado'])->name('resoluciones.cambiar-estado');
            Route::post('resoluciones/generar-numero', [ResolucionController::class, 'generarNumero'])->name('resoluciones.generar-numero');

            Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('personas-resoluciones', [ReporteController::class, 'personasConMasResoluciones'])->name('personas-resoluciones');
            Route::get('personas-resoluciones/exportar', [ReporteController::class, 'exportarPersonasResoluciones'])->name('personas-resoluciones.exportar');
            });

            // FIRMAS
            Route::prefix('firma')->name('firma.')->group(function () {
                Route::get('/', [FirmaController::class, 'index'])->name('index');
                Route::get('/firmar', [FirmaController::class, 'create'])->name('firmar');
                Route::post('/', [FirmaController::class, 'store'])->name('store');
                Route::delete('/{firma}', [FirmaController::class, 'destroy'])->name('destroy');
            });

            // RESOLUCIONES FIRMADAS
            Route::prefix('resoluciones-firmadas')->name('resoluciones-firmadas.')->group(function () {
                Route::get('/', [ResolucionFirmadaController::class, 'index'])->name('index');
                Route::get('cola-firma/{colaFirma}/firmar', [ResolucionFirmadaController::class, 'mostrarFormularioFirma'])->name('mostrar-firma');
                Route::post('cola-firma/{colaFirma}/firmar', [ResolucionFirmadaController::class, 'firmar'])->name('firmar');
                Route::post('cola-firma/{colaFirma}/rechazar', [ResolucionFirmadaController::class, 'rechazar'])->name('rechazar');
                Route::post('resoluciones/{resolucion}/solicitar-firma', [ResolucionFirmadaController::class, 'solicitarFirma'])->name('solicitar-firma');
                Route::get('resoluciones/{resolucion}/historial', [ResolucionFirmadaController::class, 'historial'])->name('historial');
                Route::get('resoluciones/{resolucion}/verificar-firma', [ResolucionFirmadaController::class, 'verificarFirma'])->name('verificar-firma');
            });

            // MIS RESOLUCIONES
            Route::prefix('mis-resoluciones')->name('mis-resoluciones.')->group(function () {
                Route::get('/', [MisResolucionesController::class, 'index'])->name('index');
                Route::get('/{resolucion}', [MisResolucionesController::class, 'show'])->name('show');
                Route::get('/estadisticas/ajax', [MisResolucionesController::class, 'estadisticas'])->name('estadisticas');
            });

            // CHATBOT IA
            Route::prefix('chatbot')->name('chatbot.')->group(function () {
                Route::get('/', [ChatbotController::class, 'index'])->name('index');
                Route::post('consultar', [ChatbotController::class, 'consultar'])->name('consultar');
                Route::post('buscar-resoluciones', [ChatbotController::class, 'buscarResoluciones'])->name('buscar-resoluciones');
                Route::post('consultas/{consulta}/valorar', [ChatbotController::class, 'valorar'])->name('valorar');
            });

            // CATÁLOGOS
            Route::resource('areas', AreaController::class);
            Route::patch('areas/{area}/toggle-estado', [AreaController::class, 'toggleEstado'])->name('areas.toggle-estado');

            Route::resource('cargos', CargoController::class)->parameters(['cargos' => 'cargo']);
            Route::patch('cargos/{cargo}/toggle-estado', [CargoController::class, 'toggleEstado'])->name('cargos.toggle-estado');

            Route::resource('dependencias', DependenciaController::class)->parameters(['dependencias' => 'dependencia']);
            Route::patch('dependencias/{dependencia}/toggle-estado', [DependenciaController::class, 'toggleEstado'])->name('dependencias.toggle-estado');

            Route::resource('direcciones', DireccionController::class)->parameters(['direcciones' => 'direccion']);
            Route::patch('direcciones/{direccion}/toggle-estado', [DireccionController::class, 'toggleEstado'])->name('direcciones.toggle-estado');

            Route::resource('especialidades', EspecialidadController::class)->parameters(['especialidades' => 'especialidad']);
            Route::patch('especialidades/{especialidad}/toggle-estado', [EspecialidadController::class, 'toggleEstado'])->name('especialidades.toggle-estado');

            Route::resource('tipos-personal', TipoPersonalController::class)->parameters(['tipos-personal' => 'tipoPersonal']);
            Route::patch('tipos-personal/{tipoPersonal}/toggle-estado', [TipoPersonalController::class, 'toggleEstado'])->name('tipos-personal.toggle-estado');

            Route::resource('colaboradores', ColaboradorController::class)->parameters(['colaboradores' => 'colaborador']);
            Route::patch('colaboradores/{colaborador}/toggle-estado', [ColaboradorController::class, 'toggleEstado'])->name('colaboradores.toggle-estado');

            Route::resource('unidades', UnidadController::class)->parameters(['unidades' => 'unidad']);

            Route::resource('usuarios', UsuarioController::class);
            Route::patch('usuarios/{usuario}/toggle-estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.toggle-estado');
            Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');

            Route::resource('tipos-resolucion', TipoResolucionController::class)->parameters(['tipos-resolucion' => 'tipoResolucion']);

            Route::resource('roles', RolController::class);
        });
    });
});