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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColaboradorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver_colaboradores')->only(['index', 'show']);
        $this->middleware('permission:crear_colaboradores')->only(['create', 'store']);
        $this->middleware('permission:editar_colaboradores')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_colaboradores')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = ColaboradorModel::with([
            'persona',
            'area',
            'cargo',
            'dependencia',
            'direccion',
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
                  ->orWhere('num_documento', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area')) {
            $query->where('id_area', $request->area);
        }

        if ($request->filled('cargo')) {
            $query->where('id_cargo', $request->cargo);
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $colaboradores = $query->paginate(20)->withQueryString();

        // Datos para filtros
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();

        return view('colaborador.colaboradores.index', compact('colaboradores', 'areas', 'cargos'));
    }

    public function create()
    {
        $personas = Persona::where('tipo_persona', 'colaborador')
            ->whereDoesntHave('colaborador')
            ->orderBy('apellido_paterno')
            ->get();

        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direccion')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.colaboradores.create', compact(
            'personas',
            'areas',
            'cargos',
            'dependencias',
            'direcciones',
            'especialidades',
            'tiposPersonal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:colaborador,id_persona',
            'id_area' => 'required|exists:area,id_area',
            'id_cargo' => 'required|exists:cargo,id_cargo',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencia',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
            'id_especialidad' => 'nullable|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'nullable|exists:tipo_personal,id_tipo_personal',
        ]);

        $validated['id_usuario'] = Auth::id();

        ColaboradorModel::create($validated);

        return redirect()->route('colaborador.colaboradores.index')
            ->with('success', '✅ Colaborador creado exitosamente');
    }

    public function show(ColaboradorModel $colaborador)
    {
        $colaborador->load([
            'persona',
            'area',
            'cargo',
            'dependencia',
            'direccion',
            'especialidad',
            'tipoPersonal',
            'usuario'
        ]);

        return view('colaborador.colaboradores.show', compact('colaborador'));
    }

    public function edit(ColaboradorModel $colaborador)
    {
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direccion')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.colaboradores.edit', compact(
            'colaborador',
            'areas',
            'cargos',
            'dependencias',
            'direcciones',
            'especialidades',
            'tiposPersonal'
        ));
    }

    public function update(Request $request, ColaboradorModel $colaborador)
    {
        $validated = $request->validate([
            'id_area' => 'required|exists:area,id_area',
            'id_cargo' => 'required|exists:cargo,id_cargo',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencia',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
            'id_especialidad' => 'nullable|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'nullable|exists:tipo_personal,id_tipo_personal',
            'i_active' => 'required|boolean',
        ]);

        $colaborador->update($validated);

        return redirect()->route('colaborador.colaboradores.show', $colaborador)
            ->with('success', '✅ Colaborador actualizado exitosamente');
    }

    public function destroy(ColaboradorModel $colaborador)
    {
        $colaborador->delete();

        return redirect()->route('colaborador.colaboradores.index')
            ->with('success', '✅ Colaborador eliminado exitosamente');
    }

    public function toggleEstado(ColaboradorModel $colaborador)
    {
        $colaborador->i_active = !$colaborador->i_active;
        $colaborador->save();

        return redirect()->back()
            ->with('success', '✅ Colaborador ' . ($colaborador->i_active ? 'activado' : 'desactivado'));
    }
}