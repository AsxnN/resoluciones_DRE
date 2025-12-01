<?php
// filepath: app/Http/Controllers/Colaborador/UsuarioController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UsuarioController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:usuarios.ver', only: ['index', 'show']),
            new Middleware('permission:usuarios.crear', only: ['create', 'store']),
            new Middleware('permission:usuarios.editar', only: ['edit', 'update']),
            new Middleware('permission:usuarios.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    public function index(Request $request)
    {
        $query = User::with('persona');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('persona', function($pq) use ($search) {
                      $pq->where('nombres', 'like', "%{$search}%")
                         ->orWhere('apellido_paterno', 'like', "%{$search}%")
                         ->orWhere('num_documento', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tipo_acceso')) {
            $query->where('tipo_acceso', $request->tipo_acceso);
        }

        if ($request->filled('i_active')) {
            $query->where('i_active', $request->boolean('i_active'));
        }

        $usuarios = $query->orderBy('tipo_acceso')->orderBy('name')->paginate(20)->withQueryString();

        return view('colaborador.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $personas = Persona::whereDoesntHave('usuario')
            ->orderBy('apellido_paterno')
            ->get();

        return view('colaborador.usuarios.create', compact('personas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:users,id_persona',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'tipo_acceso' => 'required|in:admin,colaborador,cliente',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['i_active'] = true;

        User::create($validated);

        return redirect()->route('colaborador.usuarios.index')
            ->with('success', '✅ Usuario creado exitosamente');
    }

    public function show(User $usuario)
    {
        $usuario->load(['persona', 'permissions', 'roles']);

        return view('colaborador.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        return view('colaborador.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'tipo_acceso' => 'required|in:admin,colaborador,cliente',
            'i_active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('colaborador.usuarios.show', $usuario)
            ->with('success', '✅ Usuario actualizado exitosamente');
    }

    public function destroy(User $usuario)
    {
        // No permitir eliminar admin principal
        if ($usuario->tipo_acceso === 'admin' && $usuario->id === 1) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar el administrador principal');
        }

        $usuario->delete();

        return redirect()->route('colaborador.usuarios.index')
            ->with('success', '✅ Usuario eliminado exitosamente');
    }

    public function toggleEstado(User $usuario)
    {
        $usuario->i_active = !$usuario->i_active;
        $usuario->save();

        return redirect()->back()
            ->with('success', '✅ Usuario ' . ($usuario->i_active ? 'activado' : 'desactivado'));
    }

    public function resetPassword(User $usuario)
    {
        $nuevaPassword = 'Password123';
        $usuario->password = Hash::make($nuevaPassword);
        $usuario->save();

        return redirect()->back()
            ->with('success', "✅ Contraseña restablecida a: {$nuevaPassword}");
    }
}