<?php
// filepath: app/Http/Controllers/Colaborador/DependenciaController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DependenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver_dependencias')->only(['index', 'show']);
        $this->middleware('permission:crear_dependencias')->only(['create', 'store']);
        $this->middleware('permission:editar_dependencias')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_dependencias')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Dependencia::query();

        if ($request->filled('search')) {
            $query->where('nombre_dependencia', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $dependencias = $query->orderBy('nombre_dependencia')->paginate(20)->withQueryString();

        return view('colaborador.dependencias.index', compact('dependencias'));
    }

    public function create()
    {
        return view('colaborador.dependencias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_dependencia' => 'required|string|max:100|unique:dependencia,nombre_dependencia',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        Dependencia::create($validated);

        return redirect()->route('colaborador.dependencias.index')
            ->with('success', '✅ Dependencia creada exitosamente');
    }

    public function edit(Dependencia $dependencia)
    {
        return view('colaborador.dependencias.edit', compact('dependencia'));
    }

    public function update(Request $request, Dependencia $dependencia)
    {
        $validated = $request->validate([
            'nombre_dependencia' => 'required|string|max:100|unique:dependencia,nombre_dependencia,' . $dependencia->id_dependencia . ',id_dependencia',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $dependencia->update($validated);

        return redirect()->route('colaborador.dependencias.index')
            ->with('success', '✅ Dependencia actualizada exitosamente');
    }

    public function destroy(Dependencia $dependencia)
    {
        if ($dependencia->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar una dependencia con colaboradores asociados');
        }

        $dependencia->delete();

        return redirect()->route('colaborador.dependencias.index')
            ->with('success', '✅ Dependencia eliminada exitosamente');
    }

    public function toggleEstado(Dependencia $dependencia)
    {
        $dependencia->i_active = !$dependencia->i_active;
        $dependencia->save();

        return redirect()->back()
            ->with('success', '✅ Dependencia ' . ($dependencia->i_active ? 'activada' : 'desactivada'));
    }
}