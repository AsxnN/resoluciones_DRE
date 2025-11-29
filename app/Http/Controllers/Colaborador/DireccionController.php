<?php
// filepath: app/Http/Controllers/Colaborador/DireccionController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver_direcciones')->only(['index', 'show']);
        $this->middleware('permission:crear_direcciones')->only(['create', 'store']);
        $this->middleware('permission:editar_direcciones')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_direcciones')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Direccion::query();

        if ($request->filled('search')) {
            $query->where('nombre_direccion', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $direcciones = $query->orderBy('nombre_direccion')->paginate(20)->withQueryString();

        return view('colaborador.direcciones.index', compact('direcciones'));
    }

    public function create()
    {
        return view('colaborador.direcciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_direccion' => 'required|string|max:100|unique:direccion,nombre_direccion',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        Direccion::create($validated);

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección creada exitosamente');
    }

    public function edit(Direccion $direccion)
    {
        return view('colaborador.direcciones.edit', compact('direccion'));
    }

    public function update(Request $request, Direccion $direccion)
    {
        $validated = $request->validate([
            'nombre_direccion' => 'required|string|max:100|unique:direccion,nombre_direccion,' . $direccion->id_direccion . ',id_direccion',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $direccion->update($validated);

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección actualizada exitosamente');
    }

    public function destroy(Direccion $direccion)
    {
        if ($direccion->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar una dirección con colaboradores asociados');
        }

        $direccion->delete();

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección eliminada exitosamente');
    }

    public function toggleEstado(Direccion $direccion)
    {
        $direccion->i_active = !$direccion->i_active;
        $direccion->save();

        return redirect()->back()
            ->with('success', '✅ Dirección ' . ($direccion->i_active ? 'activada' : 'desactivada'));
    }
}