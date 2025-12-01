<?php
// filepath: app/Http/Controllers/Colaborador/TipoPersonalController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\TipoPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TipoPersonalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:tipos_personal.ver', only: ['index', 'show']),
            new Middleware('permission:tipos_personal.crear', only: ['create', 'store']),
            new Middleware('permission:tipos_personal.editar', only: ['edit', 'update']),
            new Middleware('permission:tipos_personal.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    public function index(Request $request)
    {
        $query = TipoPersonal::query();

        if ($request->filled('search')) {
            $query->where('nombre_tipo_personal', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $tiposPersonal = $query->orderBy('nombre_tipo_personal')->paginate(20)->withQueryString();

        return view('colaborador.tipos-personal.index', compact('tiposPersonal'));
    }

    public function create()
    {
        return view('colaborador.tipos-personal.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_tipo_personal' => 'required|string|max:100|unique:tipo_personal,nombre_tipo_personal',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $validated['id_usuario'] = Auth::id();

        TipoPersonal::create($validated);

        return redirect()->route('colaborador.tipos-personal.index')
            ->with('success', '✅ Tipo de Personal creado exitosamente');
    }

    public function edit(TipoPersonal $tipoPersonal)
    {
        return view('colaborador.tipos-personal.edit', compact('tipoPersonal'));
    }

    public function update(Request $request, TipoPersonal $tipoPersonal)
    {
        $validated = $request->validate([
            'nombre_tipo_personal' => 'required|string|max:100|unique:tipo_personal,nombre_tipo_personal,' . $tipoPersonal->id_tipo_personal . ',id_tipo_personal',
            'descripcion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        $tipoPersonal->update($validated);

        return redirect()->route('colaborador.tipos-personal.index')
            ->with('success', '✅ Tipo de Personal actualizado exitosamente');
    }

    public function destroy(TipoPersonal $tipoPersonal)
    {
        if ($tipoPersonal->colaboradores()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar un tipo de personal con colaboradores asociados');
        }

        $tipoPersonal->delete();

        return redirect()->route('colaborador.tipos-personal.index')
            ->with('success', '✅ Tipo de Personal eliminado exitosamente');
    }

    public function toggleEstado(TipoPersonal $tipoPersonal)
    {
        $tipoPersonal->i_active = !$tipoPersonal->i_active;
        $tipoPersonal->save();

        return redirect()->back()
            ->with('success', '✅ Tipo de Personal ' . ($tipoPersonal->i_active ? 'activado' : 'desactivado'));
    }
}