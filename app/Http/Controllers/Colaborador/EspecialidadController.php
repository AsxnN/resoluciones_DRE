<?php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EspecialidadController extends Controller implements HasMiddleware
{
    /**
     * Definir middleware usando Laravel 11
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:especialidades.ver', only: ['index', 'show']),
            new Middleware('permission:especialidades.crear', only: ['create', 'store']),
            new Middleware('permission:especialidades.editar', only: ['edit', 'update']),
            new Middleware('permission:especialidades.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    public function index(Request $request)
    {
        $query = Especialidad::query();

        if ($request->filled('search')) {
            $query->where('nombre_especialidad', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $especialidades = $query->orderBy('nombre_especialidad')->paginate(20)->withQueryString();

        return view('colaborador.especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        return view('colaborador.especialidades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_especialidad' => 'required|string|max:100|unique:especialidad,nombre_especialidad',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        Especialidad::create($validated);

        return redirect()->route('colaborador.especialidades.index')
            ->with('success', '✅ Especialidad creada exitosamente');
    }

    public function edit(Especialidad $especialidad)
    {
        return view('colaborador.especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, Especialidad $especialidad)
    {
        $validated = $request->validate([
            'nombre_especialidad' => 'required|string|max:100|unique:especialidad,nombre_especialidad,' . $especialidad->id_especialidad . ',id_especialidad',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $especialidad->update($validated);

        return redirect()->route('colaborador.especialidades.index')
            ->with('success', '✅ Especialidad actualizada exitosamente');
    }

    public function destroy(Especialidad $especialidad)
    {
        if ($especialidad->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar una especialidad con colaboradores asociados');
        }

        $especialidad->delete();

        return redirect()->route('colaborador.especialidades.index')
            ->with('success', '✅ Especialidad eliminada exitosamente');
    }

    public function toggleEstado(Especialidad $especialidad)
    {
        $especialidad->i_active = !$especialidad->i_active;
        $especialidad->save();

        return redirect()->back()
            ->with('success', '✅ Especialidad ' . ($especialidad->i_active ? 'activada' : 'desactivada'));
    }
}