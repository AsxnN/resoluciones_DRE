<?php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Unidad;
use App\Models\Direccion;
use App\Models\Dependencia;
use App\Models\Area;
use App\Models\Especialidad;
use App\Models\TipoPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        $persona = $user->persona;
        $colaborador = $persona?->colaborador;
        
        return view('colaborador.profile.show', compact('user', 'persona', 'colaborador'));
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $persona = $user->persona;
        $colaborador = $persona?->colaborador;
        
        // Cargar listas para los selects
        $cargos = Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $unidades = Unidad::where('i_active', true)->orderBy('nombre_unidad')->get();
        $direcciones = Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get(); // ← CAMBIAR A PLURAL
        $dependencias = Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get(); // ← MANTENER SINGULAR
        $areas = Area::where('i_active', true)->orderBy('nombre_area')->get();
        $especialidades = Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();
        
        return view('colaborador.profile.edit', compact(
            'user', 
            'persona', 
            'colaborador',
            'cargos',
            'unidades',
            'direcciones',
            'dependencias',
            'areas',
            'especialidades',
            'tiposPersonal'
        ));
    }

    /**
     * Actualizar datos del perfil
     * Sincroniza entre users, persona y colaborador
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;
        $colaborador = $persona?->colaborador;

        $reniecVerificado = $persona?->obtenido_reniec ?? false;

        $validated = $request->validate([
            // Datos de Usuario (users)
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,

            // Datos de Persona — solo si NO fue verificado por RENIEC
            'tipo_documento' => $reniecVerificado ? 'nullable' : 'required|string|max:20',
            'num_documento'  => $reniecVerificado ? 'nullable' : 'required|string|max:20|unique:persona,num_documento,' . $persona->id_persona . ',id_persona',
            'nombres'         => $reniecVerificado ? 'nullable' : 'required|string|max:100',
            'apellido_paterno'=> $reniecVerificado ? 'nullable' : 'required|string|max:100',
            'apellido_materno'=> 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            
            // Datos de Colaborador (colaborador)
            'id_cargos' => 'required|exists:cargo,id_cargos',
            'id_unidades' => 'required|exists:unidad,id_unidad',
            'id_direcciones' => 'nullable|exists:direccion,id_direcciones',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencias',
            'id_area' => 'nullable|exists:area,id_area',
            'id_especialidad' => 'nullable|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'required|exists:tipo_personal,id_tipo_personal',
        ], [
            'num_documento.required' => 'El número de documento es obligatorio',
            'num_documento.unique' => 'Este documento ya está registrado',
            'id_cargos.required' => 'El cargo es obligatorio',
            'id_unidades.required' => 'La unidad es obligatoria',
            'id_tipo_personal.required' => 'El tipo de personal es obligatorio',
        ]);

        DB::beginTransaction();
        try {
            // Construir el nombre completo
            $nombresParaNombre  = $reniecVerificado ? $persona->nombres          : ($validated['nombres'] ?? $persona->nombres);
            $paternoParaNombre  = $reniecVerificado ? $persona->apellido_paterno : ($validated['apellido_paterno'] ?? $persona->apellido_paterno);
            $maternoParaNombre  = $reniecVerificado ? $persona->apellido_materno : ($validated['apellido_materno'] ?? '');
            $nombreCompleto = trim($nombresParaNombre . ' ' . $paternoParaNombre . ' ' . $maternoParaNombre);

            // Actualizar User
            $user->update([
                'name' => $nombreCompleto,
                'email' => $validated['email'],
            ]);

            // Actualizar Persona
            $dataPersona = [
                'correo'    => $validated['email'],
                'telefono'  => $validated['telefono'],
                'direccion' => $validated['direccion'],
            ];

            if (!$reniecVerificado) {
                $dataPersona['tipo_documento']   = $validated['tipo_documento'];
                $dataPersona['num_documento']    = $validated['num_documento'];
                $dataPersona['nombres']          = $validated['nombres'];
                $dataPersona['apellido_paterno'] = $validated['apellido_paterno'];
                $dataPersona['apellido_materno'] = $validated['apellido_materno'];
            }

            $persona->update($dataPersona);

            // Actualizar Colaborador
            if ($colaborador) {
                $colaborador->update([
                    'id_cargos' => $validated['id_cargos'],
                    'id_unidades' => $validated['id_unidades'],
                    'id_direcciones' => $validated['id_direcciones'],
                    'id_dependencia' => $validated['id_dependencia'],
                    'id_area' => $validated['id_area'],
                    'id_especialidad' => $validated['id_especialidad'],
                    'id_tipo_personal' => $validated['id_tipo_personal'],
                ]);
            }

            // Verificar si los datos están completos
            if ($this->datosCompletos($persona, $colaborador)) {
                // Marcar persona como completa
                $persona->update([
                    'datos_completos' => true,
                    'i_active' => true // ← Activar persona
                ]);
                
                // Activar colaborador
                if ($colaborador) {
                    $colaborador->update(['i_active' => true]);
                }
                
                // Usuario ya está activo desde la creación
            }

            DB::commit();

            return redirect()
                ->route('colaborador.profile.show')
                ->with('success', '✅ Perfil actualizado correctamente. Ahora tienes acceso completo al sistema.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar perfil: ' . $e->getMessage());
            $msg = app()->isProduction() ? 'Error interno al actualizar el perfil.' : $e->getMessage();
            return back()
                ->withInput()
                ->with('error', '❌ Error al actualizar perfil: ' . $msg);
        }
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->with('error', '❌ La contraseña actual es incorrecta');
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', '✅ Contraseña actualizada correctamente');
    }

    /**
     * Verificar si los datos de la persona y colaborador están completos
     */
    private function datosCompletos($persona, $colaborador): bool
    {
        $personaCompleta = !empty($persona->tipo_documento) &&
                          !empty($persona->num_documento) &&
                          $persona->num_documento !== 'TEMP-' . $persona->id_persona &&
                          !empty($persona->nombres) &&
                          !empty($persona->apellido_paterno) &&
                          !empty($persona->correo) &&
                          !empty($persona->telefono);
        
        $colaboradorCompleto = $colaborador && 
                              !empty($colaborador->id_cargos) &&
                              !empty($colaborador->id_unidades) &&
                              !empty($colaborador->id_tipo_personal);
        
        return $personaCompleta && $colaboradorCompleto;
    }
}