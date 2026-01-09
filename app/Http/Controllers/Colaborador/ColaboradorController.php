<?php
// filepath: app/Http/Controllers/Colaborador/ColaboradorController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Colaborador as ColaboradorModel;
use App\Models\Dependencia;
use App\Models\Direccion;
use App\Models\Especialidad;
use App\Models\Persona;
use App\Models\TipoPersonal;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ColaboradorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:colaboradores.ver', only: ['index', 'show']),
            new Middleware('permission:colaboradores.crear', only: ['create', 'store']),
            new Middleware('permission:colaboradores.editar', only: ['edit', 'update']),
            new Middleware('permission:colaboradores.eliminar', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = ColaboradorModel::with([
            'persona', 
            'cargo', 
            'unidad', 
            'direccion', 
            'dependencia', 
            'especialidad', 
            'tipoPersonal'
        ]);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('persona', function($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                ->orWhere('apellido_paterno', 'like', "%{$search}%")
                ->orWhere('apellido_materno', 'like', "%{$search}%")
                ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_cargos')) {
            $query->where('id_cargos', $request->id_cargos);
        }

        if ($request->filled('id_unidades')) {
            $query->where('id_unidades', $request->id_unidades);
        }

        if ($request->filled('id_direcciones')) {
            $query->where('id_direcciones', $request->id_direcciones);
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $colaboradores = $query->paginate(20)->withQueryString();

        // Datos para filtros - CORREGIDO
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $unidades = Unidad::where('i_active', true)->orderBy('nombre_unidad')->get(); // ← Cambiado
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.colaboradores.index', compact(
            'colaboradores', 
            'cargos', 
            'unidades', 
            'direcciones',
            'dependencias',
            'especialidades',
            'tiposPersonal'
        ));
    }

    public function create()
    {
        $personas = Persona::whereDoesntHave('colaborador')
            ->orderBy('apellido_paterno')
            ->get();

        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $unidades = Unidad::where('i_active', true)->orderBy('nombre_unidad')->get(); // ← Cambiado
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.colaboradores.create', compact(
            'personas',
            'cargos',
            'unidades',
            'direcciones',
            'dependencias',
            'especialidades',
            'tiposPersonal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:colaborador,id_persona',
            'id_cargos' => 'required|exists:cargo,id_cargo',
            'id_unidades' => 'required|exists:unidad,id_unidad',
            'id_direcciones' => 'required|exists:direccion,id_direcciones',
            'id_dependencia' => 'required|exists:dependencia,id_dependencia',
            'id_especialidad' => 'required|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'required|exists:tipo_personal,id_tipo_personal',
        ]);

        $validated['id_usuario'] = Auth::id();
        $validated['i_active'] = 1;

        ColaboradorModel::create($validated);

        return redirect()->route('colaborador.colaboradores.index')
            ->with('success', '✅ Colaborador creado exitosamente');
    }

    public function show(ColaboradorModel $colaborador)
    {
        $colaborador->load([
            'persona',
            'cargo',
            'unidad',
            'direccion',
            'dependencia',
            'especialidad',
            'tipoPersonal',
            'usuario'
        ]);

        return view('colaborador.colaboradores.show', compact('colaborador'));
    }

    public function edit(ColaboradorModel $colaborador)
    {
        $personas = Persona::orderBy('apellido_paterno')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $unidades = Unidad::where('i_active', true)->orderBy('nombre_unidad')->get(); // ← Cambiado
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.colaboradores.edit', compact(
            'colaborador',
            'personas',
            'cargos',
            'unidades',
            'direcciones',
            'dependencias',
            'especialidades',
            'tiposPersonal'
        ));
    }

    public function update(Request $request, ColaboradorModel $colaborador)
    {
        $validated = $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:colaborador,id_persona,' . $colaborador->id_colab_dis . ',id_colab_dis',
            'id_cargos' => 'required|exists:cargo,id_cargo',
            'id_unidades' => 'required|exists:unidad,id_unidad',
            'id_direcciones' => 'required|exists:direccion,id_direcciones',
            'id_dependencia' => 'required|exists:dependencia,id_dependencia',
            'id_especialidad' => 'required|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'required|exists:tipo_personal,id_tipo_personal',
            'i_active' => 'required|boolean',
        ]);

        $colaborador->update($validated);

        return redirect()->route('colaborador.colaboradores.index')
            ->with('success', '✅ Colaborador actualizado exitosamente');
    }

    public function destroy(ColaboradorModel $colaborador)
    {
        $colaborador->delete();

        return redirect()->route('colaborador.colaboradores.index')
            ->with('success', '✅ Colaborador eliminado exitosamente');
    }
}