<?php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Direccion;
use App\Models\Dependencia;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Especialidad;
use App\Models\TipoPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PersonaController extends Controller implements HasMiddleware
{
    /**
     * Definir middleware usando Laravel 11
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:personas.ver', only: ['index', 'show']),
            new Middleware('permission:personas.crear', only: ['create', 'store']),
            new Middleware('permission:personas.editar', only: ['edit', 'update']),
            new Middleware('permission:personas.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    /**
     * Listar todas las personas
     */
    public function index(Request $request)
    {
        $query = Persona::with(['colaborador.direccion', 'colaborador.dependencia', 'colaborador.area', 'colaborador.cargo'])
            ->where('tipo_persona', 'colaborador')
            ->where('i_active', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres');

        // Filtros
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                ->orWhere('num_documento', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('direccion_id')) {
            $query->whereHas('colaborador', function($q) use ($request) {
                $q->where('id_direcciones', $request->direccion_id);
            });
        }

        if ($request->filled('dependencia_id')) {
            $query->whereHas('colaborador', function($q) use ($request) {
                $q->where('id_dependencia', $request->dependencia_id);
            });
        }

        if ($request->filled('cargo_id')) {
            $query->whereHas('colaborador', function($q) use ($request) {
                $q->where('id_cargos', $request->cargo_id);
            });
        }

        if ($request->filled('tipo_personal_id')) {
            $query->whereHas('colaborador', function($q) use ($request) {
                $q->where('id_tipo_personal', $request->tipo_personal_id);
            });
        }

        // ✅ Agregar filtro por área
        if ($request->filled('area_id')) {
            $query->whereHas('colaborador', function($q) use ($request) {
                $q->where('id_area', $request->area_id);
            });
        }

        $personas = $query->paginate(20);

        // Para los filtros - CORREGIR nombres de columnas
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get();
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get(); // ✅ AGREGAR ESTA LÍNEA
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.personas.index', compact(
            'personas',
            'direcciones',
            'dependencias',
            'areas',        // ✅ AGREGAR ESTA LÍNEA
            'cargos',
            'tiposPersonal'
        ));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get(); // ✅ CORREGIR
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.personas.create', compact(
            'direcciones',
            'dependencias',
            'areas',
            'cargos',
            'especialidades',
            'tiposPersonal'
        ));
    }

    /**
     * Guardar nueva persona
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:personas,dni',
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'required|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion_residencia' => 'nullable|string|max:255',
            'id_direccion' => 'required|exists:direcciones,id_direccion',
            'id_dependencia' => 'required|exists:dependencias,id_dependencia',
            'id_area' => 'nullable|exists:areas,id_area',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'id_especialidad' => 'nullable|exists:especialidades,id_especialidad',
            'id_tipo_personal' => 'required|exists:tipos_personal,id_tipo_personal',
        ]);

        $validated['i_active'] = true;

        DB::beginTransaction();
        try {
            $persona = Persona::create($validated);

            DB::commit();

            return redirect()
                ->route('colaborador.personas.index')
                ->with('success', 'Persona registrada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al registrar persona: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de persona
     */
    public function show(Persona $persona)
    {
        $persona->load([
            'direccion',
            'dependencia',
            'area',
            'cargo',
            'especialidad',
            'tipoPersonal'
        ]);

        return view('colaborador.personas.show', compact('persona'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Persona $persona)
    {
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get(); // ✅ CORREGIR
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();

        return view('colaborador.personas.edit', compact(
            'persona',
            'direcciones',
            'dependencias',
            'areas',
            'cargos',
            'especialidades',
            'tiposPersonal'
        ));
    }

    /**
     * Actualizar persona
     */
    public function update(Request $request, Persona $persona)
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:personas,dni,' . $persona->id_persona . ',id_persona',
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'required|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion_residencia' => 'nullable|string|max:255',
            'id_direccion' => 'required|exists:direcciones,id_direccion',
            'id_dependencia' => 'required|exists:dependencias,id_dependencia',
            'id_area' => 'nullable|exists:areas,id_area',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'id_especialidad' => 'nullable|exists:especialidades,id_especialidad',
            'id_tipo_personal' => 'required|exists:tipos_personal,id_tipo_personal',
        ]);

        DB::beginTransaction();
        try {
            $persona->update($validated);

            DB::commit();

            return redirect()
                ->route('colaborador.personas.index')
                ->with('success', 'Persona actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar persona: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar persona (soft delete)
     */
    public function destroy(Persona $persona)
    {
        DB::beginTransaction();
        try {
            $persona->update(['i_active' => false]);

            DB::commit();

            return back()->with('success', 'Persona eliminada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar persona: ' . $e->getMessage());
        }
    }

    /**
     * Activar/Desactivar persona
     */
    public function toggleEstado(Persona $persona)
    {
        DB::beginTransaction();
        try {
            $persona->update(['i_active' => !$persona->i_active]);

            DB::commit();

            $estado = $persona->i_active ? 'activada' : 'desactivada';
            return back()->with('success', "Persona {$estado} correctamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    /**
     * Buscar persona por DNI (AJAX)
     */
    public function buscarPorDni(Request $request)
    {
        $dni = $request->get('dni');
        
        $persona = Persona::where('dni', $dni)
            ->where('i_active', true)
            ->with([
                'direccion',
                'dependencia',
                'area',
                'cargo',
                'especialidad',
                'tipoPersonal'
            ])
            ->first();

        if ($persona) {
            return response()->json([
                'found' => true,
                'persona' => $persona,
                'nombre_completo' => $persona->nombre_completo,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function export(Request $request)
    {
        try {
            $query = Persona::with(['colaborador.direccion', 'colaborador.dependencia', 'colaborador.area', 'colaborador.cargo'])
                ->where('tipo_persona', 'colaborador')
                ->where('i_active', true)
                ->orderBy('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombres');

            // Aplicar los mismos filtros que en index()
            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->where(function($q) use ($buscar) {
                    $q->where('nombres', 'like', "%{$buscar}%")
                      ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                      ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                      ->orWhere('num_documento', 'like', "%{$buscar}%");
                });
            }

            if ($request->filled('direccion_id')) {
                $query->whereHas('colaborador', function($q) use ($request) {
                    $q->where('id_direcciones', $request->direccion_id);
                });
            }

            if ($request->filled('dependencia_id')) {
                $query->whereHas('colaborador', function($q) use ($request) {
                    $q->where('id_dependencia', $request->dependencia_id);
                });
            }

            if ($request->filled('cargo_id')) {
                $query->whereHas('colaborador', function($q) use ($request) {
                    $q->where('id_cargos', $request->cargo_id);
                });
            }

            if ($request->filled('area_id')) {
                $query->whereHas('colaborador', function($q) use ($request) {
                    $q->where('id_area', $request->area_id);
                });
            }

            if ($request->filled('tipo_personal_id')) {
                $query->whereHas('colaborador', function($q) use ($request) {
                    $q->where('id_tipo_personal', $request->tipo_personal_id);
                });
            }

            $personas = $query->get();

            // Crear CSV
            $filename = 'personas_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($personas) {
                $file = fopen('php://output', 'w');
                
                // BOM UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Encabezados
                fputcsv($file, [
                    'Tipo Documento',
                    'N° Documento',
                    'Apellido Paterno',
                    'Apellido Materno',
                    'Nombres',
                    'Correo',
                    'Teléfono',
                    'WhatsApp',
                    'Dirección',
                    'Dependencia',
                    'Área',
                    'Cargo',
                ], ';');

                // Datos
                foreach ($personas as $persona) {
                    fputcsv($file, [
                        $persona->tipo_documento ?? '',
                        $persona->num_documento ?? '',
                        $persona->apellido_paterno ?? '',
                        $persona->apellido_materno ?? '',
                        $persona->nombres ?? '',
                        $persona->correo ?? '',
                        $persona->telefono ?? '',
                        $persona->whatsapp ?? '',
                        $persona->colaborador->direccion->nombre_direcciones ?? '',
                        $persona->colaborador->dependencia->nombre_dependencia ?? '',
                        $persona->colaborador->area->nombre_area ?? '',
                        $persona->colaborador->cargo->nombre_cargo ?? '',
                    ], ';');
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
}