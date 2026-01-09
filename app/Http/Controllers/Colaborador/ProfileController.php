<?php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
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
        
        return view('colaborador.profile.show', compact('user', 'persona'));
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $persona = $user->persona;
        
        return view('colaborador.profile.edit', compact('user', 'persona'));
    }

    /**
     * Actualizar datos del perfil
     * Sincroniza entre users y persona
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        $validated = $request->validate([
            // Datos de Usuario (users)
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            
            // Datos de Persona (persona)
            'tipo_documento' => 'required|string|max:20',
            'num_documento' => 'required|string|max:20|unique:persona,num_documento,' . $persona->id_persona . ',id_persona',
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ], [
            'num_documento.required' => 'El número de documento es obligatorio',
            'num_documento.unique' => 'Este documento ya está registrado',
        ]);

        DB::beginTransaction();
        try {
            // Construir el nombre completo
            $nombreCompleto = trim($validated['nombres'] . ' ' . $validated['apellido_paterno'] . ' ' . ($validated['apellido_materno'] ?? ''));

            // Actualizar User
            $user->update([
                'name' => $nombreCompleto,
                'email' => $validated['email'],
            ]);

            // Actualizar Persona
            $persona->update([
                'tipo_documento' => $validated['tipo_documento'],
                'num_documento' => $validated['num_documento'],
                'nombres' => $validated['nombres'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'],
                'correo' => $validated['email'], // Sincronizar email
                'telefono' => $validated['telefono'],
                'whatsapp' => $validated['whatsapp'],
                'direccion' => $validated['direccion'],
            ]);

            // Verificar si los datos están completos
            if ($this->datosCompletos($persona)) {
                $persona->update(['datos_completos' => true]);
            }

            DB::commit();

            return redirect()
                ->route('colaborador.profile.show')
                ->with('success', '✅ Perfil actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', '❌ Error al actualizar perfil: ' . $e->getMessage());
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
     * Verificar si los datos de la persona están completos
     */
    private function datosCompletos($persona): bool
    {
        return !empty($persona->tipo_documento) &&
               !empty($persona->num_documento) &&
               !empty($persona->nombres) &&
               !empty($persona->apellido_paterno) &&
               !empty($persona->correo) &&
               !empty($persona->telefono);
    }
}