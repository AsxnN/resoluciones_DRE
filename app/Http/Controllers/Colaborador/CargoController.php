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
            $query->buscar($request->search);
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $cargos = $query->orderBy('codigo_cargo')->paginate(20)->withQueryString();

        $stats = [
            'total' => Cargo::count(),
            'activos' => Cargo::where('i_active', true)->count(),
            'inactivos' => Cargo::where('i_active', false)->count(),
        ];

        return view('colaborador.cargos.index', compact('cargos', 'stats'));
    }

    public function create()
    {
        return view('colaborador.cargos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_cargo' => 'required|string|max:20|unique:cargo,codigo_cargo',
            'nombre_cargo' => 'required|string|max:100|unique:cargo,nombre_cargo',
            'descripcion' => 'nullable|string',
        ]);

        $validated['id_usuario'] = Auth::id();
        $validated['i_active'] = true;

        Cargo::create($validated);

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo creado exitosamente');
    }

    public function show(Cargo $cargo)
    {
        $cargo->load(['usuario', 'colaboradores.persona']);
        
        return view('colaborador.cargos.show', compact('cargo'));
    }

    public function edit(Cargo $cargo)
    {
        return view('colaborador.cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'codigo_cargo' => 'required|string|max:20|unique:cargo,codigo_cargo,' . $cargo->id_cargos . ',id_cargos',
            'nombre_cargo' => 'required|string|max:100|unique:cargo,nombre_cargo,' . $cargo->id_cargos . ',id_cargos',
            'descripcion' => 'nullable|string',
            'i_active' => 'nullable|boolean',
        ]);

        $validated['i_active'] = $request->has('i_active');

        $cargo->update($validated);

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo actualizado exitosamente');
    }

    public function destroy(Cargo $cargo)
    {
        if ($cargo->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar: hay ' . $cargo->colaboradores()->count() . ' colaboradores con este cargo');
        }

        $cargo->delete();

        return redirect()->route('colaborador.cargos.index')
            ->with('success', '✅ Cargo eliminado exitosamente');
    }

    public function toggleEstado(Cargo $cargo)
    {
        $cargo->i_active = !$cargo->i_active;
        $cargo->save();

        $estado = $cargo->i_active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "✅ Cargo {$estado} correctamente");
    }
}