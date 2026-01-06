<?php
// filepath: app/Http/Controllers/Colaborador/UsuarioController.php

namespace App\Http\Controllers\Colaborador;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

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
        $query = User::with(['persona', 'roles']);

        // Filtro de búsqueda
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

        // Filtro por rol
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filtro por estado (usar i_active)
        if ($request->filled('activo')) {
            $query->where('i_active', $request->boolean('activo'));
        }

        $usuarios = $query->orderBy('name')->paginate(20)->withQueryString();

        // Roles disponibles para filtro
        $roles = Role::where('guard_name', 'colaborador')->get();

        // Estadísticas (usar i_active)
        $stats = [
            'activos' => User::where('i_active', true)->count(),
            'admins' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'colaboradores' => User::whereHas('roles', fn($q) => $q->where('name', 'colaborador'))->count(),
        ];

        return view('colaborador.usuarios.index', compact('usuarios', 'roles', 'stats'));
    }

    public function create()
    {
        // Personas que aún no tienen usuario
        $personas = Persona::whereDoesntHave('usuario')
            ->orderBy('apellido_paterno')
            ->get();

        // Roles disponibles
        $roles = Role::where('guard_name', 'colaborador')->get();

        return view('colaborador.usuarios.create', compact('personas', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'activo' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            // Campos para crear PERSONA automáticamente
            'dni' => 'nullable|string|max:8',
            'telefono' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        
        try {
            // Validar que el DNI no exista en personas si se proporciona
            if (!empty($validated['dni'])) {
                $existeDni = Persona::where('num_documento', $validated['dni'])->exists();
                if ($existeDni) {
                    return back()->withInput()->withErrors(['dni' => 'El DNI ya está registrado en otra persona']);
                }
            }

            // Extraer nombres del name
            $nombreCompleto = explode(' ', $validated['name']);
            $nombres = $nombreCompleto[0] ?? '';
            $apellidoPaterno = $nombreCompleto[1] ?? '';
            $apellidoMaterno = implode(' ', array_slice($nombreCompleto, 2)) ?: null;

            // Crear persona automáticamente
            $persona = Persona::create([
                'tipo_persona' => 'colaborador',
                'tipo_documento' => 'DNI',
                'num_documento' => $validated['dni'] ?? 'SIN-DNI-' . time(),
                'nombres' => $nombres,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'correo' => $validated['email'], // ← MISMO EMAIL
                'telefono' => $validated['telefono'] ?? null,
                'datos_completos' => false,
                'i_active' => true,
            ]);

            // Crear usuario sin rol
            $usuario = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'id_persona' => $persona->id_persona,
                'i_active' => $request->has('activo') ? true : false,
                'guard_name' => 'colaborador',
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ]);

            DB::commit();

            return redirect()->route('colaborador.usuarios.index')
                ->with('success', '✅ Usuario creado exitosamente. Los roles se asignan desde el panel de administración.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
        }
    }
    
    public function show(User $usuario)
    {
        $usuario->load(['persona', 'roles.permissions', 'permissions']);

        return view('colaborador.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        // Personas que aún no tienen usuario O que están asociadas a este usuario
        $personas = Persona::whereDoesntHave('usuario')
            ->orWhere('id_persona', $usuario->id_persona)
            ->orderBy('apellido_paterno')
            ->get();

        // Roles disponibles
        $roles = Role::where('guard_name', 'colaborador')->get();

        return view('colaborador.usuarios.edit', compact('usuario', 'personas', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'id_persona' => 'nullable|exists:persona,id_persona|unique:users,id_persona,' . $usuario->id,
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'activo' => 'nullable|boolean',
            // Campos para PERSONA (no para USER)
            'dni' => 'nullable|string|max:8',
            'telefono' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        
        try {
            // Actualizar usuario (solo campos de users)
            $dataUsuario = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'i_active' => $request->has('activo') ? true : false,
            ];

            // Solo actualizar password si se proporcionó
            if ($request->filled('password')) {
                $dataUsuario['password'] = Hash::make($validated['password']);
            }

            $usuario->update($dataUsuario);

            // Sincronizar email y teléfono con persona si está asociada
            if ($usuario->persona) {
                $dataPersona = ['correo' => $validated['email']];
                
                if (!empty($validated['telefono'])) {
                    $dataPersona['telefono'] = $validated['telefono'];
                }
                
                if (!empty($validated['dni'])) {
                    // Verificar que el DNI no esté en uso por otra persona
                    $existeDni = Persona::where('num_documento', $validated['dni'])
                        ->where('id_persona', '!=', $usuario->id_persona)
                        ->exists();
                        
                    if (!$existeDni) {
                        $dataPersona['num_documento'] = $validated['dni'];
                    }
                }
                
                $usuario->persona->update($dataPersona);
            }

            // Sincronizar roles
            $usuario->syncRoles($validated['roles']);

            DB::commit();

            return redirect()->route('colaborador.usuarios.show', $usuario)
                ->with('success', '✅ Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $usuario)
    {
        // No permitir eliminar usuarios con resoluciones
        if ($usuario->resoluciones()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar: el usuario tiene resoluciones asociadas');
        }

        // No permitir eliminar el usuario autenticado
        if ($usuario->id === Auth::id()) {
            return redirect()->back()
                ->with('error', '❌ No puedes eliminar tu propio usuario');
        }

        $usuario->delete();

        return redirect()->route('colaborador.usuarios.index')
            ->with('success', '✅ Usuario eliminado exitosamente');
    }

    public function toggleEstado(User $usuario)
    {
        $usuario->i_active = !$usuario->i_active;
        $usuario->save();

        $estado = $usuario->i_active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "✅ Usuario {$estado} correctamente");
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