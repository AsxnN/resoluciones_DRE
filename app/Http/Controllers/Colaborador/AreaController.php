<?php
// filepath: app/Http/Controllers/Colaborador/AreaController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver_areas')->only(['index', 'show']);
        $this->middleware('permission:crear_areas')->only(['create', 'store']);
        $this->middleware('permission:editar_areas')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_areas')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Area::query();

        if ($request->filled('search')) {
            $query->where('nombre_area', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $areas = $query->orderBy('nombre_area')->paginate(20)->withQueryString();

        return view('colaborador.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('colaborador.areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_area' => 'required|string|max:100|unique:area,nombre_area',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        Area::create($validated);

        return redirect()->route('colaborador.areas.index')
            ->with('success', '✅ Área creada exitosamente');
    }

    public function edit(Area $area)
    {
        return view('colaborador.areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre_area' => 'required|string|max:100|unique:area,nombre_area,' . $area->id_area . ',id_area',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $area->update($validated);

        return redirect()->route('colaborador.areas.index')
            ->with('success', '✅ Área actualizada exitosamente');
    }

    public function destroy(Area $area)
    {
        // Verificar si tiene colaboradores asociados
        if ($area->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un área con colaboradores asociados');
        }

        $area->delete();

        return redirect()->route('colaborador.areas.index')
            ->with('success', '✅ Área eliminada exitosamente');
    }

    public function toggleEstado(Area $area)
    {
        $area->i_active = !$area->i_active;
        $area->save();

        $estado = $area->i_active ? 'activada' : 'desactivada';

        return redirect()->back()
            ->with('success', "✅ Área {$estado} correctamente");
    }
}