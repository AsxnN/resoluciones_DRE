<?php
// filepath: app/Http/Controllers/Colaborador/TipoResolucionController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\TipoResolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TipoResolucionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:tipos-resolucion.ver', only: ['index', 'show']),
            new Middleware('permission:tipos-resolucion.crear', only: ['create', 'store']),
            new Middleware('permission:tipos-resolucion.editar', only: ['edit', 'update']),
            new Middleware('permission:tipos-resolucion.eliminar', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = TipoResolucion::query();

        if ($request->filled('search')) {
            $query->where('nombre_tipo_resolucion', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $tiposResolucion = $query->orderBy('nombre_tipo_resolucion')->paginate(20)->withQueryString();

        return view('colaborador.tipos-resolucion.index', compact('tiposResolucion'));
    }

    public function create()
    {
        return view('colaborador.tipos-resolucion.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_tipo_resolucion' => 'required|string|max:100|unique:tipo_resolucion,nombre_tipo_resolucion',
        ]);

        $validated['id_usuario'] = Auth::id();
        $validated['i_active'] = 1;

        TipoResolucion::create($validated);

        return redirect()->route('colaborador.tipos-resolucion.index')
            ->with('success', '✅ Tipo de Resolución creado exitosamente');
    }

    public function show(TipoResolucion $tipoResolucion)
    {
        return view('colaborador.tipos-resolucion.show', compact('tipoResolucion'));
    }

    public function edit(TipoResolucion $tipoResolucion)
    {
        return view('colaborador.tipos-resolucion.edit', compact('tipoResolucion'));
    }

    public function update(Request $request, TipoResolucion $tipoResolucion)
    {
        $validated = $request->validate([
            'nombre_tipo_resolucion' => 'required|string|max:100|unique:tipo_resolucion,nombre_tipo_resolucion,' . $tipoResolucion->id_tipo_resolucion . ',id_tipo_resolucion',
            'i_active' => 'boolean',
        ]);

        $validated['i_active'] = $request->has('i_active') ? 1 : 0;

        $tipoResolucion->update($validated);

        return redirect()->route('colaborador.tipos-resolucion.index')
            ->with('success', '✅ Tipo de Resolución actualizado exitosamente');
    }

    public function destroy(TipoResolucion $tipoResolucion)
    {
        // Verificar si tiene resoluciones asociadas
        if ($tipoResolucion->resoluciones()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un tipo de resolución con resoluciones asociadas');
        }

        $tipoResolucion->delete();

        return redirect()->route('colaborador.tipos-resolucion.index')
            ->with('success', '✅ Tipo de Resolución eliminado exitosamente');
    }
}