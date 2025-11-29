{{-- filepath: resources/views/admin/auditoria/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Auditoría del Sistema')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📊 Auditoría del Sistema</h1>
        <p class="text-gray-600 mt-1">Registro de actividades y cambios en el sistema</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('admin.auditoria.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Usuario -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                <select name="usuario" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('usuario') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Acción -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                <select name="accion" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    <option value="crear" {{ request('accion') === 'crear' ? 'selected' : '' }}>Crear</option>
                    <option value="editar" {{ request('accion') === 'editar' ? 'selected' : '' }}>Editar</option>
                    <option value="eliminar" {{ request('accion') === 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                    <option value="firmar" {{ request('accion') === 'firmar' ? 'selected' : '' }}>Firmar</option>
                    <option value="login" {{ request('accion') === 'login' ? 'selected' : '' }}>Login</option>
                </select>
            </div>

            <!-- Modelo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="modelo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="Resolucion" {{ request('modelo') === 'Resolucion' ? 'selected' : '' }}>Resolución</option>
                    <option value="Usuario" {{ request('modelo') === 'Usuario' ? 'selected' : '' }}>Usuario</option>
                    <option value="Persona" {{ request('modelo') === 'Persona' ? 'selected' : '' }}>Persona</option>
                    <option value="Firma" {{ request('modelo') === 'Firma' ? 'selected' : '' }}>Firma</option>
                </select>
            </div>

            <!-- Fecha Desde -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                <input type="date" 
                       name="fecha_desde" 
                       value="{{ request('fecha_desde') }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Botones -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('admin.auditoria.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Hoy</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['hoy'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Crear</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['crear'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Editar</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['editar'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Eliminar</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['eliminar'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de Auditoría -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($auditorias->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($auditorias as $auditoria)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start">
                        <!-- Icono de Acción -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center
                                {{ $auditoria->accion === 'crear' ? 'bg-green-100 text-green-600' : 
                                   ($auditoria->accion === 'editar' ? 'bg-yellow-100 text-yellow-600' : 
                                   ($auditoria->accion === 'eliminar' ? 'bg-red-100 text-red-600' : 
                                   'bg-blue-100 text-blue-600')) }}">
                                @if($auditoria->accion === 'crear')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                @elseif($auditoria->accion === 'editar')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                @elseif($auditoria->accion === 'eliminar')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ ucfirst($auditoria->accion) }}
                                    </h3>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                        {{ $auditoria->modelo_tipo }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $auditoria->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-gray-700 mb-3">{{ $auditoria->descripcion }}</p>

                            <div class="flex items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $auditoria->usuario->name }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <span>{{ $auditoria->ip_address }}</span>
                                </div>
                            </div>

                            <!-- Datos Adicionales -->
                            @if($auditoria->datos_antiguos || $auditoria->datos_nuevos)
                            <details class="mt-3">
                                <summary class="cursor-pointer text-sm text-blue-600 hover:text-blue-800">
                                    Ver cambios detallados
                                </summary>
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg text-xs">
                                    @if($auditoria->datos_antiguos)
                                        <div class="mb-2">
                                            <p class="font-semibold text-gray-700 mb-1">Datos Anteriores:</p>
                                            <pre class="text-gray-600 overflow-x-auto">{{ json_encode(json_decode($auditoria->datos_antiguos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                    @if($auditoria->datos_nuevos)
                                        <div>
                                            <p class="font-semibold text-gray-700 mb-1">Datos Nuevos:</p>
                                            <pre class="text-gray-600 overflow-x-auto">{{ json_encode(json_decode($auditoria->datos_nuevos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            </details>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if($auditorias->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $auditorias->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay registros de auditoría</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron registros con los filtros aplicados</p>
            </div>
        @endif
    </div>
</div>
@endsection