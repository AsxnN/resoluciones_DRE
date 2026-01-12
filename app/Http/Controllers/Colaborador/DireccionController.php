<?php
// filepath: app/Http/Controllers/Colaborador/DireccionController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:direcciones.ver', only: ['index', 'show']),
            new Middleware('permission:direcciones.crear', only: ['create', 'store']),
            new Middleware('permission:direcciones.editar', only: ['edit', 'update']),
            new Middleware('permission:direcciones.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }
    
    public function index(Request $request)
    {
        $query = Direccion::query();

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $query->buscar($request->search);
        }

        // Filtro por estado
        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $direcciones = $query->orderBy('nombre_direcciones')->paginate(20)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Direccion::count(),
            'activas' => Direccion::where('i_active', true)->count(),
            'inactivas' => Direccion::where('i_active', false)->count(),
        ];

        return view('colaborador.direcciones.index', compact('direcciones', 'stats'));
    }

    public function create()
    {
        // No hay dependencias en la tabla direccion según tu migración
        return view('colaborador.direcciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_direcciones' => 'required|string|max:100|unique:direccion,nombre_direcciones',
        ]);

        $validated['id_usuario'] = Auth::id();
        $validated['i_active'] = 1;

        Direccion::create($validated);

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección creada exitosamente');
    }

    public function show(Direccion $direccion)
    {
        $direccion->load(['personas', 'areas']);

        $stats = [
            'personas' => $direccion->personas()->count(),
            'areas' => $direccion->areas()->count(),
            'colaboradores' => $direccion->colaboradores()->count(),
        ];

        return view('colaborador.direcciones.show', compact('direccion', 'stats'));
    }

    public function edit(Direccion $direccion)
    {
        return view('colaborador.direcciones.edit', compact('direccion'));
    }

    public function update(Request $request, Direccion $direccion)
    {
        $validated = $request->validate([
            'nombre_direcciones' => 'required|string|max:100|unique:direccion,nombre_direcciones,' . $direccion->id_direcciones . ',id_direcciones',
            'i_active' => 'required|boolean',
        ]);

        $direccion->update($validated);

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección actualizada exitosamente');
    }

    public function destroy(Direccion $direccion)
    {
        // Solo verificar si tiene colaboradores asociados
        if ($direccion->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar una dirección con ' . $direccion->colaboradores()->count() . ' colaborador(es) asignado(s)');
        }

        $direccion->delete();

        return redirect()->route('colaborador.direcciones.index')
            ->with('success', '✅ Dirección eliminada exitosamente');
    }

    public function toggleEstado(Direccion $direccion)
    {
        $direccion->i_active = !$direccion->i_active;
        $direccion->save();

        $estado = $direccion->i_active ? 'activada' : 'desactivada';

        return redirect()->back()
            ->with('success', "✅ Dirección {$estado} exitosamente");
    }
}