<?php
// filepath: app/Http/Controllers/Admin/GestionPrivilegiosController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\User;
use App\Observers\PermisoObserver;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class GestionPrivilegiosController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        $usuarios = User::with(['persona', 'permissions'])
            ->where('tipo_acceso', '!=', 'admin')
            ->orderBy('tipo_acceso')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.privilegios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de gestión de permisos
     */
    public function gestionar(User $usuario)
    {
        // Obtener todos los módulos con sus permisos
        $modulos = Modulo::with(['permisos' => function($query) {
            $query->orderBy('name');
        }])
        ->where('i_active', true)
        ->orderBy('orden')
        ->get();

        // Permisos actuales del usuario
        $permisosUsuario = $usuario->permissions->pluck('id')->toArray();

        return view('admin.privilegios.gestionar', compact('usuario', 'modulos', 'permisosUsuario'));
    }

    /**
     * Actualizar permisos del usuario
     */
    public function actualizar(Request $request, User $usuario)
    {
        $request->validate([
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permissions,id',
        ]);

        $permisosNuevos = $request->input('permisos', []);
        $permisosAnteriores = $usuario->permissions->pluck('id')->toArray();

        // Obtener permisos agregados y removidos
        $permisosAgregados = array_diff($permisosNuevos, $permisosAnteriores);
        $permisosRemovidos = array_diff($permisosAnteriores, $permisosNuevos);

        // Sincronizar permisos
        $usuario->syncPermissions($permisosNuevos);

        // Registrar en metadata los cambios
        foreach ($permisosAgregados as $permisoId) {
            $permiso = Permission::find($permisoId);
            if ($permiso) {
                PermisoObserver::permisoAsignado($usuario, $permiso);
            }
        }

        foreach ($permisosRemovidos as $permisoId) {
            $permiso = Permission::find($permisoId);
            if ($permiso) {
                PermisoObserver::permisoRevocado($usuario, $permiso);
            }
        }

        return redirect()
            ->route('admin.privilegios.gestionar', $usuario)
            ->with('success', '✅ Permisos actualizados correctamente');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleEstado(User $usuario)
    {
        $usuario->i_active = !$usuario->i_active;
        $usuario->save();

        $estado = $usuario->i_active ? 'activado' : 'desactivado';

        return redirect()
            ->back()
            ->with('success', "Usuario {$estado} correctamente");
    }

    /**
     * Copiar permisos de un usuario a otro
     */
    public function copiarPermisos(Request $request)
    {
        $request->validate([
            'usuario_origen' => 'required|exists:users,id',
            'usuario_destino' => 'required|exists:users,id|different:usuario_origen',
        ]);

        $usuarioOrigen = User::findOrFail($request->usuario_origen);
        $usuarioDestino = User::findOrFail($request->usuario_destino);

        $permisos = $usuarioOrigen->permissions->pluck('id')->toArray();
        $usuarioDestino->syncPermissions($permisos);

        // Registrar auditoría
        foreach ($permisos as $permisoId) {
            $permiso = Permission::find($permisoId);
            if ($permiso) {
                PermisoObserver::permisoAsignado($usuarioDestino, $permiso);
            }
        }

        return redirect()
            ->back()
            ->with('success', '✅ Permisos copiados exitosamente');
    }

    /**
     * Asignar todos los permisos de un módulo
     */
    public function asignarModuloCompleto(Request $request, User $usuario)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id_modulo',
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        $permisos = $modulo->permisos;

        foreach ($permisos as $permiso) {
            if (!$usuario->hasPermissionTo($permiso)) {
                $usuario->givePermissionTo($permiso);
                PermisoObserver::permisoAsignado($usuario, $permiso);
            }
        }

        return redirect()
            ->back()
            ->with('success', "✅ Todos los permisos del módulo '{$modulo->nombre_modulo}' asignados");
    }

    /**
     * Revocar todos los permisos de un módulo
     */
    public function revocarModuloCompleto(Request $request, User $usuario)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id_modulo',
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        $permisos = $modulo->permisos;

        foreach ($permisos as $permiso) {
            if ($usuario->hasPermissionTo($permiso)) {
                $usuario->revokePermissionTo($permiso);
                PermisoObserver::permisoRevocado($usuario, $permiso);
            }
        }

        return redirect()
            ->back()
            ->with('success', "✅ Todos los permisos del módulo '{$modulo->nombre_modulo}' revocados");
    }
}