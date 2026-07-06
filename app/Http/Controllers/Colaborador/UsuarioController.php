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

        // Filtro por rol (tipo_acceso es la fuente real de autorización; los roles de Spatie no se usan)
        if ($request->filled('role')) {
            $query->where('tipo_acceso', $request->role);
        }

        // Filtro por estado (usar i_active)
        if ($request->filled('activo')) {
            $query->where('i_active', $request->boolean('activo'));
        }

        $usuarios = $query->orderBy('name')->paginate(20)->withQueryString();

        // Estadísticas (usar i_active y tipo_acceso, no roles de Spatie - esa tabla no se usa)
        $stats = [
            'activos' => User::where('i_active', true)->count(),
            'admins' => User::where('tipo_acceso', 'admin')->count(),
            'colaboradores' => User::where('tipo_acceso', 'colaborador')->count(),
        ];

        return view('colaborador.usuarios.index', compact('usuarios', 'stats'));
    }

    public function create()
    {
        // Cargar catálogos para colaborador
        $cargos = \App\Models\Cargo::where('i_active', true)->orderBy('nombre_cargo')->get();
        $unidades = \App\Models\Unidad::where('i_active', true)->orderBy('nombre_unidad')->get();
        $direcciones = \App\Models\Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get();
        $dependencias = \App\Models\Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get();
        $areas = \App\Models\Area::where('i_active', true)->orderBy('nombre_area')->get();
        $especialidades = \App\Models\Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get();
        $tiposPersonal = \App\Models\TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get();
        $rolesOrganizacionales = \App\Models\Rol::where('i_active', true)->orderBy('nombre_rol')->get();

        return view('colaborador.usuarios.create', compact(
            'cargos',
            'unidades',
            'direcciones',
            'dependencias',
            'areas',
            'especialidades',
            'tiposPersonal',
            'rolesOrganizacionales'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Usuario (username NO se valida aquí, se genera automáticamente)
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'tipo_acceso' => 'required|in:admin,colaborador,cliente',
            'i_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            
            // Persona
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'tipo_documento' => 'required|in:DNI,CE,Pasaporte',
            'num_documento' => 'required|string|max:20|unique:persona,num_documento',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            
            // Colaborador
            'id_cargos' => 'nullable|exists:cargo,id_cargos',
            'id_unidad' => 'nullable|exists:unidad,id_unidad',
            'id_direcciones' => 'nullable|exists:direccion,id_direcciones',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencias',
            'id_area' => 'nullable|exists:area,id_area',
            'id_especialidad' => 'nullable|exists:especialidad,id_especialidad',
            'id_tipo_personal' => 'nullable|exists:tipo_personal,id_tipo_personal',
            'id_rol' => 'nullable|exists:roles_organizacionales,id_rol',
            'colaborador_activo' => 'nullable|boolean',
        ]);

        // Evitar escalación de privilegios: solo un admin puede crear otro admin
        if ($validated['tipo_acceso'] === 'admin' && Auth::user()->tipo_acceso !== 'admin') {
            abort(403, 'No tienes permiso para crear usuarios administradores.');
        }

        DB::beginTransaction();

        try {
            // 1. Generar username único
            $username = $this->generarUsernameUnico(
                $validated['nombres'],
                $validated['apellido_paterno']
            );

            // 2. Crear Persona
            $tipoPersona = $validated['tipo_acceso'] === 'cliente' ? 'cliente' : 'colaborador';
            $persona = Persona::withoutEvents(function () use ($validated, $tipoPersona) {
                return Persona::create([
                    'tipo_persona' => $tipoPersona,
                    'tipo_documento' => $validated['tipo_documento'],
                    'num_documento' => $validated['num_documento'],
                    'nombres' => $validated['nombres'],
                    'apellido_paterno' => $validated['apellido_paterno'],
                    'apellido_materno' => $validated['apellido_materno'] ?? null,
                    'correo' => $validated['email'],
                    'telefono' => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'datos_completos' => true,
                    'i_active' => true,
                ]);
            });

            // 3. Crear Usuario
            $nombreCompleto = trim("{$validated['nombres']} {$validated['apellido_paterno']} " . ($validated['apellido_materno'] ?? ''));
            
            $usuario = User::create([
                'id_persona' => $persona->id_persona,
                'name' => $nombreCompleto,
                'username' => $username,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tipo_acceso' => $validated['tipo_acceso'],
                'i_active' => $validated['i_active'] ?? true,
                'email_verified_at' => ($validated['email_verified'] ?? false) ? now() : null,
                'id_rol' => $validated['id_rol'] ?? null,
            ]);

            // 4. Crear registro según tipo
            if ($validated['tipo_acceso'] === 'colaborador') {
                $codigoColaborador = 'COL-' . str_pad($persona->id_persona, 6, '0', STR_PAD_LEFT);
                \App\Models\Colaborador::create([
                    'id_colab_dis' => $codigoColaborador,
                    'id_persona' => $persona->id_persona,
                    'id_usuario' => $usuario->id,
                    'id_cargos' => $validated['id_cargos'] ?? null,
                    'id_unidad' => $validated['id_unidad'] ?? null,
                    'id_direcciones' => $validated['id_direcciones'] ?? null,
                    'id_dependencia' => $validated['id_dependencia'] ?? null,
                    'id_area' => $validated['id_area'] ?? null,
                    'id_especialidad' => $validated['id_especialidad'] ?? null,
                    'id_tipo_personal' => $validated['id_tipo_personal'] ?? null,
                    'i_active' => $validated['colaborador_activo'] ?? true,
                ]);
            } elseif ($validated['tipo_acceso'] === 'cliente') {
                \App\Models\Cliente::create([
                    'id_persona' => $persona->id_persona,
                    'tipo_cliente' => 'externo',
                    'i_active' => $validated['i_active'] ?? true,
                ]);
            }
            // Para 'admin': solo Persona + User, sin tabla colaborador/cliente

            // 5. Registrar Auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'users',
                'id_registro' => $usuario->id,
                'accion' => 'crear',
                'id_usuario' => Auth::id(),
                'ip_address' => $request->ip(),
                'descripcion' => "Usuario {$validated['tipo_acceso']} creado: {$nombreCompleto} (username: {$username})",
            ]);

            DB::commit();

            return redirect()->route('colaborador.usuarios.show', $usuario)
                ->with('success', "✅ Usuario creado exitosamente. Username: <strong>{$username}</strong>");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear usuario: ' . $e->getMessage());
            $msg = app()->isProduction() ? 'Error interno al crear el usuario.' : $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al crear usuario: ' . $msg);
        }
    }

    /**
     * Genera un username único basado en nombre y apellido
     * Formato: primera letra del nombre + apellido paterno
     * Si existe, agrega número incremental: jmartinez2, jmartinez3, etc.
     */
    private function generarUsernameUnico($nombres, $apellidoPaterno)
    {
        // Limpiar y normalizar texto
        $primeraLetraNombre = mb_substr($nombres, 0, 1);
        $primeraLetraNombre = $this->limpiarTexto($primeraLetraNombre);
        
        $apellidoLimpio = $this->limpiarTexto($apellidoPaterno);
        
        // Generar username base
        $usernameBase = strtolower($primeraLetraNombre . $apellidoLimpio);
        $username = $usernameBase;
        $contador = 1;
        
        // Verificar si existe y agregar número si es necesario
        while (User::where('username', $username)->exists()) {
            $contador++;
            $username = $usernameBase . $contador;
        }
        
        return $username;
    }

    /**
     * Limpia texto: elimina acentos, ñ por n, espacios y caracteres especiales
     */
    private function limpiarTexto($texto)
    {
        // Normalizar y eliminar acentos
        $texto = mb_strtolower($texto);
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'u'],
            $texto
        );
        
        // Eliminar todo lo que no sea letra
        $texto = preg_replace('/[^a-z]/', '', $texto);
        
        return $texto;
    }
    
    public function show(User $usuario)
    {
        $usuario->load(['persona', 'rolOrganizacional', 'permissions']);

        return view('colaborador.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        // Personas que aún no tienen usuario O que están asociadas a este usuario
        $personas = Persona::whereDoesntHave('usuario')
            ->orWhere('id_persona', $usuario->id_persona)
            ->orderBy('apellido_paterno')
            ->get();

        return view('colaborador.usuarios.edit', compact('usuario', 'personas'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'email'              => 'required|email|unique:users,email,' . $usuario->id,
            'password'           => ['nullable', 'confirmed', Password::min(8)],
            'id_rol'             => 'nullable|exists:roles_organizacionales,id_rol',
            'tipo_acceso'        => 'required|in:admin,colaborador,cliente',
            'activo'             => 'nullable|boolean',
            // Campos editables de persona
            'telefono'           => 'nullable|string|max:20',
            'direccion'          => 'nullable|string|max:500',
            // Nombres: solo se aceptan si la persona NO fue verificada por RENIEC
            'nombres'            => 'nullable|string|max:100',
            'apellido_paterno'   => 'nullable|string|max:100',
            'apellido_materno'   => 'nullable|string|max:100',
            // Campos para COLABORADOR
            'id_cargos'          => 'nullable|exists:cargo,id_cargos',
            'id_unidad'          => 'nullable|exists:unidad,id_unidad',
            'id_direcciones'     => 'nullable|exists:direccion,id_direcciones',
            'id_dependencia'     => 'nullable|exists:dependencia,id_dependencias',
            'id_area'            => 'nullable|exists:area,id_area',
            'id_especialidad'    => 'nullable|exists:especialidad,id_especialidad',
            'id_tipo_personal'   => 'nullable|exists:tipo_personal,id_tipo_personal',
            'colaborador_activo' => 'nullable|boolean',
        ]);

        // Evitar escalación de privilegios: solo un admin puede asignar tipo admin
        if ($validated['tipo_acceso'] === 'admin' && Auth::user()->tipo_acceso !== 'admin') {
            abort(403, 'No tienes permiso para asignar tipo de acceso administrador.');
        }

        DB::beginTransaction();

        try {
            // Actualizar usuario
            $dataUsuario = [
                'email'       => $validated['email'],
                'tipo_acceso' => $validated['tipo_acceso'],
                'i_active'    => $request->boolean('activo'),
                'id_rol'      => $validated['id_rol'] ?? null,
            ];

            if ($request->filled('password')) {
                $dataUsuario['password'] = Hash::make($validated['password']);
            }

            $usuario->update($dataUsuario);

            // Actualizar persona asociada
            if ($usuario->persona) {
                $dataPersona = [
                    'correo'    => $validated['email'],
                    'telefono'  => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                ];

                // Nombres solo editables si NO fue verificado por RENIEC
                if (!$usuario->persona->obtenido_reniec) {
                    if (!empty($validated['nombres']))          $dataPersona['nombres']          = $validated['nombres'];
                    if (!empty($validated['apellido_paterno'])) $dataPersona['apellido_paterno'] = $validated['apellido_paterno'];
                    if (isset($validated['apellido_materno']))  $dataPersona['apellido_materno'] = $validated['apellido_materno'];
                }

                $usuario->persona->update($dataPersona);

                // Sincronizar users.name con el nombre completo de persona
                $persona = $usuario->persona->fresh();
                $usuario->update([
                    'name' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                ]);
            }

            // Actualizar datos del colaborador si existe
            if ($usuario->colaborador) {
                $dataColaborador = [
                    'id_cargos' => $validated['id_cargos'] ?? null,
                    'id_unidad' => $validated['id_unidad'] ?? null,
                    'id_direcciones' => $validated['id_direcciones'] ?? null,
                    'id_dependencia' => $validated['id_dependencia'] ?? null,
                    'id_area' => $validated['id_area'] ?? null,
                    'id_especialidad' => $validated['id_especialidad'] ?? null,
                    'id_tipo_personal' => $validated['id_tipo_personal'] ?? null,
                    'i_active' => $request->boolean('colaborador_activo'),
                ];
                
                $usuario->colaborador->update($dataColaborador);
            }

            // Registrar auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'users',
                'id_registro' => $usuario->id,
                'accion' => 'actualizar',
                'id_usuario' => Auth::id(),
                'ip_address' => $request->ip(),
                'descripcion' => "Usuario actualizado: {$usuario->name} (ID: {$usuario->id})",
            ]);

            DB::commit();

            return redirect()->route('colaborador.usuarios.show', $usuario)
                ->with('success', '✅ Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar usuario: ' . $e->getMessage());
            $msg = app()->isProduction() ? 'Error interno al actualizar el usuario.' : $e->getMessage();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar usuario: ' . $msg]);
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
        $nuevaPassword = \Illuminate\Support\Str::random(10);
        $usuario->password = Hash::make($nuevaPassword);
        $usuario->save();

        if ($usuario->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($usuario->email)
                    ->send(new \App\Mail\CredencialesAcceso([
                        'nombre'      => $usuario->name,
                        'username'    => $usuario->username,
                        'password'    => $nuevaPassword,
                        'tipo_acceso' => $usuario->tipo_acceso,
                    ]));
            } catch (\Exception $e) {
                \Log::error('Error al enviar credenciales tras reseteo: ' . $e->getMessage());
            }
        }

        return redirect()->back()
            ->with('success', 'Contraseña restablecida. Las nuevas credenciales han sido enviadas al correo del usuario.');
    }
}