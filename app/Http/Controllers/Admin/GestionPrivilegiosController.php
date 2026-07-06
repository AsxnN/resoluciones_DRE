<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\User;
use App\Observers\PermisoObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionPrivilegiosController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        $usuarios = User::with(['persona', 'permissions'])
            ->whereIn('tipo_acceso', ['admin', 'colaborador'])
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
            $query->where('i_active', true)->orderBy('name');
        }])
        ->where('i_active', true)
        ->where('slug', '!=', 'gestion-privilegios')
        ->orderBy('orden')
        ->get();

        // Obtener permisos actuales del usuario
        $permisosUsuario = $usuario->permissions->pluck('id')->toArray();

        // Obtener otros usuarios para copiar permisos
        $otrosUsuarios = User::where('id', '!=', $usuario->id)
            ->where('i_active', true)
            ->with('persona')
            ->orderBy('name')
            ->get();

        return view('admin.privilegios.gestionar', compact('usuario', 'modulos', 'permisosUsuario', 'otrosUsuarios'));
    }

    /**
     * Actualizar permisos de usuario
     */
    public function actualizar(Request $request, User $usuario)
    {
        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            // Obtener permisos anteriores
            $permisosAnteriores = $usuario->permissions->pluck('id')->toArray();
            $permisosNuevos = $request->input('permisos', []);

            // Permisos a revocar
            $permisosRevocados = array_diff($permisosAnteriores, $permisosNuevos);
            foreach ($permisosRevocados as $permisoId) {
                $permiso = Permiso::find($permisoId);
                if ($permiso) {
                    $usuario->revokePermissionTo($permiso);
                    PermisoObserver::permisoRevocado($usuario, $permiso);
                }
            }

            // Permisos a asignar
            $permisosAsignados = array_diff($permisosNuevos, $permisosAnteriores);
            foreach ($permisosAsignados as $permisoId) {
                $permiso = Permiso::find($permisoId);
                if ($permiso) {
                    $usuario->givePermissionTo($permiso);
                    PermisoObserver::permisoAsignado($usuario, $permiso);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.privilegios.gestionar', $usuario)
                ->with('success', "Permisos de {$usuario->name} actualizados correctamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar permisos: ' . $e->getMessage());
        }
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleEstado(User $usuario)
    {
        $usuario->update(['i_active' => !$usuario->i_active]);

        $estado = $usuario->i_active ? 'activado' : 'desactivado';
        
        return back()->with('success', "Usuario {$usuario->name} {$estado} correctamente");
    }

    /**
     * Copiar permisos de otro usuario
     */
    public function copiarPermisos(Request $request)
    {
        $request->validate([
            'usuario_destino_id' => 'required|exists:users,id',
            'usuario_origen_id' => 'required|exists:users,id',
        ]);

        $usuarioDestino = User::findOrFail($request->usuario_destino_id);
        $usuarioOrigen = User::findOrFail($request->usuario_origen_id);

        DB::beginTransaction();
        try {
            // Revocar todos los permisos actuales
            $usuarioDestino->syncPermissions([]);

            // Copiar permisos del usuario origen
            $permisos = $usuarioOrigen->permissions;
            
            foreach ($permisos as $permiso) {
                $usuarioDestino->givePermissionTo($permiso);
                PermisoObserver::permisoAsignado($usuarioDestino, $permiso);
            }

            // Registrar la acción de copiado
            PermisoObserver::permisosCopiadosDesdeOtroUsuario($usuarioDestino, $usuarioOrigen);

            DB::commit();

            return back()->with('success', "Permisos copiados de {$usuarioOrigen->name} a {$usuarioDestino->name}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al copiar permisos: ' . $e->getMessage());
        }
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

        DB::beginTransaction();
        try {
            foreach ($modulo->permisos as $permiso) {
                if (!$usuario->hasPermissionTo($permiso)) {
                    $usuario->givePermissionTo($permiso);
                    PermisoObserver::permisoAsignado($usuario, $permiso);
                }
            }

            DB::commit();

            return back()->with('success', "Todos los permisos del módulo '{$modulo->nombre_modulo}' asignados");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar módulo: ' . $e->getMessage());
        }
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

        DB::beginTransaction();
        try {
            foreach ($modulo->permisos as $permiso) {
                if ($usuario->hasPermissionTo($permiso)) {
                    $usuario->revokePermissionTo($permiso);
                    PermisoObserver::permisoRevocado($usuario, $permiso);
                }
            }

            DB::commit();

            return back()->with('success', "Todos los permisos del módulo '{$modulo->nombre_modulo}' revocados");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al revocar módulo: ' . $e->getMessage());
        }
    }
}