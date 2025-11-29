<?php
// filepath: app/Http/Controllers/Colaborador/PersonaController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver_personas')->only(['index', 'show']);
        $this->middleware('permission:crear_personas')->only(['create', 'store']);
        $this->middleware('permission:editar_personas')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_personas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Persona::query();

        // Filtros
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('apellido_materno', 'like', "%{$search}%")
                  ->orWhere('num_documento', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $personas = $query->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->paginate(20)
            ->withQueryString();

        return view('colaborador.personas.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colaborador.personas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_persona' => 'required|in:colaborador,cliente',
            'tipo_documento' => 'required|in:DNI,CE,PASAPORTE',
            'num_documento' => [
                'required',
                'string',
                'max:20',
                'unique:persona,num_documento',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo_documento === 'DNI' && !preg_match('/^\d{8}$/', $value)) {
                        $fail('El DNI debe tener 8 dígitos.');
                    }
                },
            ],
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo' => 'required|email|max:150|unique:persona,correo',
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        // Verificar si tiene todos los datos completos
        $validated['datos_completos'] = !empty($validated['telefono']) && 
                                       !empty($validated['direccion']);

        $persona = Persona::create($validated);

        return redirect()
            ->route('colaborador.personas.show', $persona)
            ->with('success', '✅ Persona creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        $persona->load(['colaborador', 'cliente', 'usuario']);

        return view('colaborador.personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        return view('colaborador.personas.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        $validated = $request->validate([
            'tipo_persona' => 'required|in:colaborador,cliente',
            'tipo_documento' => 'required|in:DNI,CE,PASAPORTE',
            'num_documento' => [
                'required',
                'string',
                'max:20',
                'unique:persona,num_documento,' . $persona->id_persona . ',id_persona',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo_documento === 'DNI' && !preg_match('/^\d{8}$/', $value)) {
                        $fail('El DNI debe tener 8 dígitos.');
                    }
                },
            ],
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo' => 'required|email|max:150|unique:persona,correo,' . $persona->id_persona . ',id_persona',
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'i_active' => 'required|boolean',
        ]);

        // Actualizar datos_completos
        $validated['datos_completos'] = !empty($validated['telefono']) && 
                                       !empty($validated['direccion']);

        $persona->update($validated);

        return redirect()
            ->route('colaborador.personas.show', $persona)
            ->with('success', '✅ Persona actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        // Verificar que no tenga dependencias
        if ($persona->colaborador || $persona->cliente) {
            return redirect()
                ->back()
                ->with('error', '❌ No se puede eliminar una persona con dependencias (colaborador/cliente)');
        }

        $persona->delete();

        return redirect()
            ->route('colaborador.personas.index')
            ->with('success', '✅ Persona eliminada exitosamente');
    }

    /**
     * Buscar persona por DNI (AJAX)
     */
    public function buscarPorDni(Request $request)
    {
        $request->validate([
            'num_documento' => 'required|string|max:20',
        ]);

        $persona = Persona::where('num_documento', $request->num_documento)->first();

        if ($persona) {
            return response()->json([
                'success' => true,
                'persona' => $persona,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Persona no encontrada',
        ], 404);
    }

    /**
     * Toggle estado activo
     */
    public function toggleEstado(Persona $persona)
    {
        $persona->i_active = !$persona->i_active;
        $persona->save();

        $estado = $persona->i_active ? 'activada' : 'desactivada';

        return redirect()
            ->back()
            ->with('success', "✅ Persona {$estado} correctamente");
    }
}