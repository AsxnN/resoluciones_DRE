<?php
// filepath: app/Http/Controllers/Colaborador/CargoController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CargoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cargos.ver', only: ['index', 'show']),
            new Middleware('permission:cargos.crear', only: ['create', 'store']),
            new Middleware('permission:cargos.editar', only: ['edit', 'update']),
            new Middleware('permission:cargos.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    public function index(Request $request)
    {
        $query = Cargo::query();

        if ($request->filled('search')) {
            $query->where('nombre_cargo', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $cargos = $query->orderBy('nombre_cargo')->paginate(20)->withQueryString();

        return view('colaborador.cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('colaborador.cargos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_cargo' => 'required|string|max:100|unique:cargo,nombre_cargo',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        Cargo::create($validated);

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo creado exitosamente');
    }

    public function edit(Cargo $cargo)
    {
        return view('colaborador.cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'nombre_cargo' => 'required|string|max:100|unique:cargo,nombre_cargo,' . $cargo->id_cargo . ',id_cargo',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $cargo->update($validated);

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo actualizado exitosamente');
    }

    public function destroy(Cargo $cargo)
    {
        if ($cargo->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un cargo con colaboradores asociados');
        }

        $cargo->delete();

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo eliminado exitosamente');
    }

    public function toggleEstado(Cargo $cargo)
    {
        $cargo->i_active = !$cargo->i_active;
        $cargo->save();

        return redirect()->back()
            ->with('success', '✅ Cargo ' . ($cargo->i_active ? 'activado' : 'desactivado'));
    }
}