<?php
// filepath: app/Http/Controllers/Auth/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Mostrar formulario de login de administradores
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Procesar login de administradores
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificar credenciales
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Las credenciales proporcionadas no coinciden con nuestros registros.'),
            ]);
        }

        $user = Auth::user();

        // Verificar que sea usuario activo
        if (!$user->i_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Su cuenta está inactiva. Contacte al administrador.'),
            ]);
        }

        // Verificar que sea tipo admin
        if ($user->tipo_acceso !== 'admin') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('No tiene permisos de administrador.'),
            ]);
        }

        // Regenerar sesión
        $request->session()->regenerate();

        // Actualizar última sesión
        $user->update(['ultima_sesion' => now()]);

        // Auditoría
        \App\Models\Auditoria::create([
            'tabla_afectada' => 'users',
            'id_registro' => $user->id,
            'accion' => 'login',
            'id_usuario' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'descripcion' => 'Login administrador exitoso',
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Auditoría
        \App\Models\Auditoria::create([
            'tabla_afectada' => 'users',
            'id_registro' => $userId,
            'accion' => 'logout',
            'id_usuario' => $userId,
            'ip_address' => $request->ip(),
            'descripcion' => 'Logout administrador',
        ]);

        return redirect('/admin/login');
    }
}