{{-- filepath: resources/views/admin/usuarios/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ver Usuario')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👤 Perfil de Usuario</h1>
                    <p class="text-gray-600 mt-1">{{ $usuario->name }}</p>
                </div>
            </div>
            
            <!-- Estado Badge -->
            @if($usuario->activo)
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    ✓ Activo
                </span>
            @else
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                    ✗ Inactivo
                </span>
            @endif
        </div>
    </div>

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r 
            {{ $usuario->tipo_acceso === 'admin' ? 'from-red-600 to-red-700' : 
               ($usuario->tipo_acceso === 'colaborador' ? 'from-blue-600 to-blue-700' : 
               'from-green-600 to-green-700') }} 
            px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $usuario->name }}</h2>
                    <p class="text-white text-opacity-90 mt-2">{{ $usuario->email }}</p>
                    <div class="flex items-center gap-4 mt-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                            {{ ucfirst($usuario->tipo_acceso) }}
                        </span>
                        @if($usuario->persona)
                        <span class="text-sm">
                            DNI: {{ $usuario->persona->dni }}
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-4xl font-bold 
                    {{ $usuario->tipo_acceso === 'admin' ? 'text-red-600' : 
                       ($usuario->tipo_acceso === 'colaborador' ? 'text-blue-600' : 
                       'text-green-600') }}">
                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <!-- Grid de Información -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Información Personal</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="font-semibold text-gray-900">{{ $usuario->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-gray-900">{{ $usuario->email }}</p>
                        </div>

                        @if($usuario->persona)
                        <div>
                            <p class="text-sm text-gray-500">DNI</p>
                            <p class="font-semibold text-gray-900">{{ $usuario->persona->dni }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Nombres</p>
                            <p class="font-semibold text-gray-900">{{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información del Sistema -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Sistema</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Tipo de Acceso</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $usuario->tipo_acceso === 'admin' ? 'bg-red-100 text-red-800' : 
                                   ($usuario->tipo_acceso === 'colaborador' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-green-100 text-green-800') }}">
                                {{ ucfirst($usuario->tipo_acceso) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Estado</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $usuario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $usuario->activo ? '✓ Activo' : '✗ Inactivo' }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Fecha de Registro</p>
                            <p class="font-semibold text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Último Acceso</p>
                            <p class="font-semibold text-gray-900">
                                {{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->diffForHumans() : 'Nunca' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permisos Asignados -->
            @if($usuario->permisos->count() > 0)
            <div class="mb-8 pb-8 border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Permisos Asignados</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($usuario->permisos as $permiso)
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                            {{ $permiso->nombre_permiso }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Estadísticas -->
            @if($usuario->tipo_acceso === 'colaborador')
            <div class="pb-8 border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Estadísticas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-600 mb-1">Resoluciones Creadas</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['resoluciones'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-green-600 mb-1">Firmas Realizadas</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['firmas'] ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm text-purple-600 mb-1">Personas Registradas</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['personas'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex gap-4">
        @can('asignar_permisos')
        <a href="{{ route('admin.usuarios.asignar-permisos', $usuario) }}" 
           class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg text-center transition">
            🔐 Gestionar Permisos
        </a>
        @endcan

        @can('editar_usuarios')
        <button onclick="editUser({{ $usuario->id }})" 
                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            ✏️ Editar Usuario
        </button>
        @endcan
    </div>
</div>

@push('scripts')
<script>
function editUser(userId) {
    // Implementar edición
    window.location.href = `/admin/usuarios/${userId}/edit`;
}
</script>
@endpush
@endsection