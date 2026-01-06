{{-- filepath: resources/views/colaborador/usuarios/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Ver Usuario')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.usuarios.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👤 Detalle del Usuario</h1>
                    <p class="text-gray-600 mt-1">Información completa de: <strong>{{ $usuario->name }}</strong></p>
                </div>
            </div>

            <div class="flex gap-2">
                @can('usuarios.editar')
                <a href="{{ route('colaborador.usuarios.edit', $usuario) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                @endcan

                @can('usuarios.eliminar')
                @if($usuario->id !== auth()->id())
                <form method="POST" 
                      action="{{ route('colaborador.usuarios.destroy', $usuario) }}" 
                      onsubmit="return confirm('¿Está seguro de eliminar este usuario?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
                @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos de Acceso -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🔐 Datos de Acceso</h3>
                    @if($usuario->i_active)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            ✓ Activo
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                            ✗ Inactivo
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre de Usuario</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $usuario->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Correo Electrónico</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $usuario->email }}</p>
                        @if($usuario->email_verified_at)
                            <span class="text-xs text-green-600">✓ Verificado</span>
                        @else
                            <span class="text-xs text-red-600">✗ No verificado</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Persona Asociada -->
            @if($usuario->persona)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Persona Asociada</h3>
                
                <div class="flex items-center mb-4">
                    @if($usuario->persona->foto_persona ?? false)
                        <img class="h-16 w-16 rounded-full object-cover" 
                             src="{{ asset('storage/' . $usuario->persona->foto_persona) }}" 
                             alt="{{ $usuario->persona->nombres }}">
                    @else
                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($usuario->persona->nombres, 0, 1) }}{{ substr($usuario->persona->apellido_paterno, 0, 1) }}
                        </div>
                    @endif
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $usuario->persona->nombres }} 
                            {{ $usuario->persona->apellido_paterno }} 
                            {{ $usuario->persona->apellido_materno ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600">{{ $usuario->persona->tipo_documento }}: {{ $usuario->persona->num_documento }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('colaborador.personas.show', $usuario->persona) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver perfil completo →
                    </a>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Sin Persona Asociada</h3>
                        <p class="mt-1 text-sm text-yellow-700">Este usuario no está vinculado a ninguna persona del sistema.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actividad -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Actividad</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Último acceso</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $usuario->ultima_sesion ? $usuario->ultima_sesion->diffForHumans() : 'Nunca' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Cuenta creada</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $usuario->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Última actualización</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $usuario->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="space-y-6">
            <!-- Roles -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔑 Roles Asignados</h3>
                
                @if($usuario->roles->count() > 0)
                    <div class="space-y-2">
                        @foreach($usuario->roles as $role)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($role->name === 'colaborador' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $role->permissions->count() }} permisos
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Sin roles asignados</p>
                @endif
            </div>

            <!-- Permisos Directos -->
            @if($usuario->permissions->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Permisos Directos</h3>
                
                <div class="space-y-1 max-h-64 overflow-y-auto">
                    @foreach($usuario->permissions as $permission)
                    <div class="flex items-center text-sm p-2 hover:bg-gray-50 rounded">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700">{{ $permission->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Estadísticas -->
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">📈 Estadísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Resoluciones creadas</span>
                        <span class="text-2xl font-bold">{{ $usuario->resoluciones()->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm">Permisos totales</span>
                        <span class="text-2xl font-bold">{{ $usuario->getAllPermissions()->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm">Días desde creación</span>
                        <span class="text-2xl font-bold">{{ $usuario->created_at->diffInDays() }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            @can('usuarios.editar')
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Acciones Rápidas</h3>
                
                <div class="space-y-2">
                    <form method="POST" action="{{ route('colaborador.usuarios.toggle-estado', $usuario) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full px-4 py-2 text-sm {{ $usuario->i_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium rounded-lg transition">
                            {{ $usuario->i_active ? '🔴 Desactivar Usuario' : '✅ Activar Usuario' }}
                        </button>
                    </form>

                    <form method="POST" 
                          action="{{ route('colaborador.usuarios.reset-password', $usuario) }}"
                          onsubmit="return confirm('¿Restablecer contraseña a: Password123?')">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-medium rounded-lg transition">
                            🔑 Restablecer Contraseña
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection