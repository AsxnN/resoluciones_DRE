<?php
// filepath: app/Http/Controllers/Auth/ClienteRegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClienteRegisterController extends Controller
{
    /**
     * Muestra el formulario de auto-registro de cliente.
     */
    public function showForm()
    {
        // Si ya está autenticado como cliente, redirigir al dashboard
        if (Auth::check() && Auth::user()->tipo_acceso === 'cliente') {
            return redirect()->route('cliente.dashboard');
        }

        return view('auth.cliente-register');
    }

    /**
     * Procesa el auto-registro de un nuevo cliente.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'tipo_documento'   => 'required|in:DNI,CE,Pasaporte',
            'num_documento'    => 'required|string|max:20|unique:persona,num_documento',
            'email'            => 'required|email|max:150|unique:users,email',
            'telefono'         => 'nullable|string|max:20',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'num_documento.unique' => 'Ya existe una cuenta registrada con ese número de documento.',
            'email.unique'         => 'Ya existe una cuenta registrada con ese correo electrónico.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear Persona
            $persona = Persona::create([
                'tipo_persona'     => 'cliente',
                'tipo_documento'   => $validated['tipo_documento'],
                'num_documento'    => $validated['num_documento'],
                'nombres'          => strtoupper($validated['nombres']),
                'apellido_paterno' => strtoupper($validated['apellido_paterno']),
                'apellido_materno' => $validated['apellido_materno'] ? strtoupper($validated['apellido_materno']) : null,
                'correo'           => $validated['email'],
                'telefono'         => $validated['telefono'] ?? null,
                'datos_completos'  => true,
                'i_active'         => true,
            ]);

            // 2. Generar username único
            $username = $this->generarUsername($validated['nombres'], $validated['apellido_paterno']);

            // 3. Crear User
            $nombreCompleto = trim(
                strtoupper($validated['nombres']) . ' ' .
                strtoupper($validated['apellido_paterno']) . ' ' .
                strtoupper($validated['apellido_materno'] ?? '')
            );

            $user = User::create([
                'id_persona'       => $persona->id_persona,
                'name'             => $nombreCompleto,
                'username'         => $username,
                'email'            => $validated['email'],
                'password'         => Hash::make($validated['password']),
                'tipo_acceso'      => 'cliente',
                'i_active'         => true,
                'email_verified_at'=> now(), // Auto-verificar en auto-registro
            ]);

            // 4. Crear Cliente
            Cliente::create([
                'id_persona'  => $persona->id_persona,
                'tipo_cliente'=> 'externo',
                'i_active'    => true,
            ]);

            // 5. Auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'users',
                'id_registro'    => $user->id,
                'accion'         => 'auto_registro',
                'id_usuario'     => $user->id,
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
                'descripcion'    => "Auto-registro cliente: {$nombreCompleto}",
            ]);

            DB::commit();

            // 6. Login automático y redirigir al dashboard
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('cliente.dashboard')
                ->with('success', "¡Bienvenido, {$user->name}! Tu cuenta ha sido creada exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear tu cuenta. Por favor intenta nuevamente.');
        }
    }

    /**
     * Genera un username único: primera letra del nombre + apellido paterno
     */
    private function generarUsername(string $nombres, string $apellidoPaterno): string
    {
        $primeraLetra = mb_strtolower(mb_substr($nombres, 0, 1));
        $apellido     = mb_strtolower($apellidoPaterno);

        // Normalizar: quitar acentos, ñ → n, no alfanumérico
        $map = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n','ü'=>'u'];
        $primeraLetra = strtr($primeraLetra, $map);
        $apellido     = strtr($apellido, $map);
        $apellido     = preg_replace('/[^a-z]/', '', $apellido);

        $base     = $primeraLetra . $apellido;
        $username = $base;
        $i        = 1;

        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $base . $i;
        }

        return $username;
    }
}
