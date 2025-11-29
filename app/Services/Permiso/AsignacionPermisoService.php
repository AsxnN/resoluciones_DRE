<?php

namespace App\Services\Permiso;

use App\Models\User;
use App\Models\Permiso;
use App\Models\UsuarioPermisoMetadata;
use App\Models\Auditoria;
use Illuminate\Support\Facades\DB;

class AsignacionPermisoService
{
    /**
     * Asignar un permiso a un usuario
     */
    public function asignarPermiso(User $usuario, int $permisoId, ?string $observacion = null): bool
    {
        DB::beginTransaction();
        try {
            $permiso = Permiso::findOrFail($permisoId);

            // ✅ 1. Usar Spatie para asignar el permiso
            $usuario->givePermissionTo($permiso);

            // ✅ 2. Registrar en metadata (auditoría custom)
            UsuarioPermisoMetadata::create([
                'id_usuario' => $usuario->id,
                'permission_id' => $permisoId,
                'id_usuario_asigna' => auth()->id(),
                'observacion' => $observacion ?? 'Asignado desde panel de administración',
                'fecha_asignacion' => now(),
                'i_active' => true,
            ]);

            // ✅ 3. Registrar en auditoría general
            Auditoria::create([
                'tabla_afectada' => 'model_has_permissions',
                'id_registro' => $usuario->id,
                'accion' => 'asignar_permiso',
                'datos_nuevos' => json_encode([
                    'usuario' => $usuario->name,
                    'permiso' => $permiso->name,
                    'modulo' => $permiso->modulo->nombre_modulo ?? null,
                ]),
                'id_usuario' => auth()->id(),
                'ip_address' => request()->ip(),
                'descripcion' => "Permiso '{$permiso->name}' asignado a {$usuario->name}",
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al asignar permiso: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Revocar un permiso de un usuario
     */
    public function revocarPermiso(User $usuario, int $permisoId, ?string $motivo = null): bool
    {
        DB::beginTransaction();
        try {
            $permiso = Permiso::findOrFail($permisoId);

            // ✅ 1. Usar Spatie para revocar el permiso
            $usuario->revokePermissionTo($permiso);

            // ✅ 2. Actualizar metadata (marcar como inactivo)
            UsuarioPermisoMetadata::where('id_usuario', $usuario->id)
                ->where('permission_id', $permisoId)
                ->where('i_active', true)
                ->update([
                    'i_active' => false,
                    'fecha_revocacion' => now(),
                    'observacion' => $motivo ?? 'Revocado desde panel de administración',
                ]);

            // ✅ 3. Registrar en auditoría general
            Auditoria::create([
                'tabla_afectada' => 'model_has_permissions',
                'id_registro' => $usuario->id,
                'accion' => 'revocar_permiso',
                'datos_anteriores' => json_encode([
                    'usuario' => $usuario->name,
                    'permiso' => $permiso->name,
                ]),
                'id_usuario' => auth()->id(),
                'ip_address' => request()->ip(),
                'descripcion' => "Permiso '{$permiso->name}' revocado de {$usuario->name}",
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al revocar permiso: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sincronizar todos los permisos de un usuario
     */
    public function sincronizarPermisos(User $usuario, array $permisosIds): array
    {
        DB::beginTransaction();
        try {
            // Obtener permisos actuales (Spatie)
            $permisosActuales = $usuario->permissions->pluck('id')->toArray();
            
            // Calcular diferencias
            $permisosAgregar = array_diff($permisosIds, $permisosActuales);
            $permisosRevocar = array_diff($permisosActuales, $permisosIds);

            // Asignar nuevos
            foreach ($permisosAgregar as $permisoId) {
                $this->asignarPermiso($usuario, $permisoId, 'Asignación masiva desde panel admin');
            }

            // Revocar antiguos
            foreach ($permisosRevocar as $permisoId) {
                $this->revocarPermiso($usuario, $permisoId, 'Revocación masiva desde panel admin');
            }

            DB::commit();

            return [
                'asignados' => count($permisosAgregar),
                'revocados' => count($permisosRevocar),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtener historial de permisos de un usuario
     */
    public function obtenerHistorial(User $usuario)
    {
        return UsuarioPermisoMetadata::where('id_usuario', $usuario->id)
            ->with(['permiso.modulo', 'usuarioAsigna'])
            ->orderBy('fecha_asignacion', 'desc')
            ->get();
    }
}