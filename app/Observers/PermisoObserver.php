<?php

namespace App\Observers;

use App\Models\Auditoria;
use App\Models\Permiso;
use App\Models\User;

class PermisoObserver
{
    /**
     * Registrar cuando se asigna un permiso
     */
    public static function permisoAsignado(User $usuario, Permiso $permiso): void
    {
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'asignar_permiso',
            'tabla_afectada' => 'model_has_permissions',
            'id_registro' => $usuario->id,
            'descripcion' => "Permiso '{$permiso->name}' asignado a {$usuario->name}",
            'datos_nuevos' => [
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->name,
                'permiso_id' => $permiso->id,
                'permiso_nombre' => $permiso->name,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    /**
     * Registrar cuando se revoca un permiso
     */
    public static function permisoRevocado(User $usuario, Permiso $permiso): void
    {
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'revocar_permiso',
            'tabla_afectada' => 'model_has_permissions',
            'id_registro' => $usuario->id,
            'descripcion' => "Permiso '{$permiso->name}' revocado de {$usuario->name}",
            'datos_anteriores' => [
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->name,
                'permiso_id' => $permiso->id,
                'permiso_nombre' => $permiso->name,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    /**
     * Registrar cuando se copian permisos de otro usuario
     */
    public static function permisosCopiadosDesdeOtroUsuario(User $usuarioDestino, User $usuarioOrigen): void
    {
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'copiar_permisos',
            'tabla_afectada' => 'model_has_permissions',
            'id_registro' => $usuarioDestino->id,
            'descripcion' => "Permisos copiados de {$usuarioOrigen->name} a {$usuarioDestino->name}",
            'datos_nuevos' => [
                'usuario_destino_id' => $usuarioDestino->id,
                'usuario_destino_nombre' => $usuarioDestino->name,
                'usuario_origen_id' => $usuarioOrigen->id,
                'usuario_origen_nombre' => $usuarioOrigen->name,
                'total_permisos' => $usuarioOrigen->permissions->count(),
                'permisos' => $usuarioOrigen->permissions->pluck('name')->toArray(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    /**
     * Registrar cuando se asigna un módulo completo
     */
    public static function moduloAsignado(User $usuario, $modulo, int $totalPermisos): void
    {
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'asignar_modulo',
            'tabla_afectada' => 'model_has_permissions',
            'id_registro' => $usuario->id,
            'descripcion' => "Módulo '{$modulo->nombre_modulo}' asignado a {$usuario->name} ({$totalPermisos} permisos)",
            'datos_nuevos' => [
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->name,
                'modulo_id' => $modulo->id_modulo,
                'modulo_nombre' => $modulo->nombre_modulo,
                'total_permisos' => $totalPermisos,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    /**
     * Registrar cuando se revoca un módulo completo
     */
    public static function moduloRevocado(User $usuario, $modulo, int $totalPermisos): void
    {
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'revocar_modulo',
            'tabla_afectada' => 'model_has_permissions',
            'id_registro' => $usuario->id,
            'descripcion' => "Módulo '{$modulo->nombre_modulo}' revocado de {$usuario->name} ({$totalPermisos} permisos)",
            'datos_anteriores' => [
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->name,
                'modulo_id' => $modulo->id_modulo,
                'modulo_nombre' => $modulo->nombre_modulo,
                'total_permisos' => $totalPermisos,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    /**
     * Registrar cuando se activa/desactiva un usuario
     */
    public static function estadoUsuarioCambiado(User $usuario, bool $estadoAnterior, bool $estadoNuevo): void
    {
        $accion = $estadoNuevo ? 'activado' : 'desactivado';
        
        Auditoria::create([
            'id_usuario' => auth()->id(),
            'accion' => 'cambiar_estado_usuario',
            'tabla_afectada' => 'users',
            'id_registro' => $usuario->id,
            'descripcion' => "Usuario {$usuario->name} {$accion}",
            'datos_anteriores' => [
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->name,
                'estado_anterior' => $estadoAnterior,
            ],
            'datos_nuevos' => [
                'estado_nuevo' => $estadoNuevo,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }
}