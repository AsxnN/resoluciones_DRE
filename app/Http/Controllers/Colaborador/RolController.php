<?php
// filepath: app/Http/Controllers/Colaborador/RolController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RolController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.ver', only: ['index', 'show']),
            new Middleware('permission:roles.crear', only: ['create', 'store']),
            new Middleware('permission:roles.editar', only: ['edit', 'update']),
            new Middleware('permission:roles.eliminar', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Role::withCount(['users', 'permissions'])
            ->where('guard_name', 'colaborador'); // Solo roles del guard colaborador

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('colaborador.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'colaborador')->get();
        
        return view('colaborador.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Guard fijo en colaborador
        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'colaborador',
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $role->syncPermissions($permissions);
        }

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol creado exitosamente');
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        
        return view('colaborador.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'colaborador')->get();
        
        return view('colaborador.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        // Verificar si tiene usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un rol con usuarios asignados');
        }

        $role->delete();

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol eliminado exitosamente');
    }
}