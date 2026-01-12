<?php
// filepath: bootstrap/app.php

use App\Http\Middleware\CheckPermiso;
use App\Http\Middleware\CheckTipoAcceso;
use App\Http\Middleware\EnsureUserHasPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Cargar rutas adicionales
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/colaborador.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/cliente.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middlewares globales
        $middleware->web(append: [
            // \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Aliases de middlewares
        $middleware->alias([
            'tipo_acceso' => CheckTipoAcceso::class, // ← Solo una vez, usando CheckTipoAcceso
            'permission' => CheckPermiso::class,
            'ensure_permission' => EnsureUserHasPermission::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'perfil.completo' => \App\Http\Middleware\VerificarPerfilCompleto::class, // ← Solo una vez
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();