<?php
// filepath: app/Http/Controllers/Auth/ColaboradorLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ColaboradorLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.colaborador-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Credenciales incorrectas.'),
            ]);
        }

        $user = Auth::user();

        if (!$user->i_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Su cuenta está inactiva.'),
            ]);
        }

        if ($user->tipo_acceso !== 'colaborador') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('No tiene permisos de colaborador.'),
            ]);
        }

        $request->session()->regenerate();
        $user->update(['ultima_sesion' => now()]);

        \App\Models\Auditoria::create([
            'tabla_afectada' => 'users',
            'id_registro' => $user->id,
            'accion' => 'login',
            'id_usuario' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'descripcion' => 'Login colaborador exitoso',
        ]);

        return redirect()->intended(route('colaborador.dashboard'));
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \App\Models\Auditoria::create([
            'tabla_afectada' => 'users',
            'id_registro' => $userId,
            'accion' => 'logout',
            'id_usuario' => $userId,
            'ip_address' => $request->ip(),
            'descripcion' => 'Logout colaborador',
        ]);

        return redirect()->route('login');
    }
}