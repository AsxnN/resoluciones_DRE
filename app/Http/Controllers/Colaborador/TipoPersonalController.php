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
            new Middleware('permission:tipos-personal.ver', only: ['index', 'show']),
            new Middleware('permission:tipos-personal.crear', only: ['create', 'store']),
            new Middleware('permission:tipos-personal.editar', only: ['edit', 'update']),
            new Middleware('permission:tipos-personal.eliminar', only: ['destroy']),
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
        ]);

        $validated['i_active'] = 1;

        TipoPersonal::create($validated);

        return redirect()->route('colaborador.tipos-personal.index')
            ->with('success', '✅ Tipo de Personal creado exitosamente');
    }

    public function show(TipoPersonal $tipoPersonal)
    {
        return view('colaborador.tipos-personal.show', compact('tipoPersonal'));
    }

    public function edit(TipoPersonal $tipoPersonal)
    {
        return view('colaborador.tipos-personal.edit', compact('tipoPersonal'));
    }

    public function update(Request $request, TipoPersonal $tipoPersonal)
    {
        $validated = $request->validate([
            'nombre_tipo_personal' => 'required|string|max:100|unique:tipo_personal,nombre_tipo_personal,' . $tipoPersonal->id_tipo_personal . ',id_tipo_personal',
            'i_active' => 'boolean',
        ]);

        $validated['i_active'] = $request->has('i_active') ? 1 : 0;

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
}