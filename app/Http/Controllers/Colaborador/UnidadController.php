<?php
// filepath: app/Http/Controllers/Colaborador/UnidadController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UnidadController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:unidades.ver', only: ['index', 'show']),
            new Middleware('permission:unidades.crear', only: ['create', 'store']),
            new Middleware('permission:unidades.editar', only: ['edit', 'update']),
            new Middleware('permission:unidades.eliminar', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Unidad::query();

        if ($request->filled('search')) {
            $query->where('nombre_unidad', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $unidades = $query->orderBy('nombre_unidad')->paginate(20)->withQueryString();

        $stats = [
            'total' => Unidad::count(),
            'activas' => Unidad::where('i_active', true)->count(),
            'inactivas' => Unidad::where('i_active', false)->count(),
        ];

        return view('colaborador.unidades.index', compact('unidades', 'stats'));
    }

    public function create()
    {
        return view('colaborador.unidades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_unidad' => 'required|string|max:100|unique:unidad,nombre_unidad',
        ]);

        $validated['id_usuario'] = Auth::id();
        $validated['i_active'] = 1;

        Unidad::create($validated);

        return redirect()->route('colaborador.unidades.index')
            ->with('success', '✅ Unidad creada exitosamente');
    }

    public function show(Unidad $unidad)
    {
        $unidad->load(['colaboradores']);

        return view('colaborador.unidades.show', compact('unidad'));
    }

    public function edit(Unidad $unidad)
    {
        return view('colaborador.unidades.edit', compact('unidad'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $validated = $request->validate([
            'nombre_unidad' => 'required|string|max:100|unique:unidad,nombre_unidad,' . $unidad->id_unidad . ',id_unidad',
            'i_active' => 'boolean',
        ]);

        $validated['i_active'] = $request->has('i_active') ? 1 : 0;

        $unidad->update($validated);

        return redirect()->route('colaborador.unidades.index')
            ->with('success', '✅ Unidad actualizada exitosamente');
    }

    public function destroy(Unidad $unidad)
    {
        if ($unidad->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar una unidad con colaboradores asignados');
        }

        $unidad->delete();

        return redirect()->route('colaborador.unidades.index')
            ->with('success', '✅ Unidad eliminada exitosamente');
    }
}