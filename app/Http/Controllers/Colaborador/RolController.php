<?php
// filepath: app/Http/Controllers/Colaborador/RolController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

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
        $query = Rol::withCount('colaboradores');

        if ($request->filled('search')) {
            $query->where('nombre_rol', 'like', '%' . $request->search . '%');
        }

        $roles = $query->where('i_active', true)
            ->orderBy('nombre_rol')
            ->paginate(20)
            ->withQueryString();

        return view('colaborador.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('colaborador.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_rol' => 'required|string|max:100|unique:roles_organizacionales,nombre_rol',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Rol::create([
            'nombre_rol' => $validated['nombre_rol'],
            'descripcion' => $validated['descripcion'] ?? null,
            'i_active' => true,
            'id_usuario' => Auth::id(),
        ]);

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol organizacional creado exitosamente');
    }

    public function show(Rol $role)
    {
        $role->load('colaboradores');
        
        return view('colaborador.roles.show', compact('role'));
    }

    public function edit(Rol $role)
    {
        return view('colaborador.roles.edit', compact('role'));
    }

    public function update(Request $request, Rol $role)
    {
        $validated = $request->validate([
            'nombre_rol' => 'required|string|max:100|unique:roles_organizacionales,nombre_rol,' . $role->id_rol . ',id_rol',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'nullable|boolean',
        ]);

        $role->update([
            'nombre_rol' => $validated['nombre_rol'],
            'descripcion' => $validated['descripcion'] ?? null,
            'i_active' => $validated['i_active'] ?? true,
        ]);

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol organizacional actualizado exitosamente');
    }

    public function destroy(Rol $role)
    {
        // Verificar si tiene colaboradores asignados
        if ($role->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un rol con colaboradores asignados');
        }

        $role->update(['i_active' => false]);

        return redirect()->route('colaborador.roles.index')
            ->with('success', '✅ Rol desactivado exitosamente');
    }
}