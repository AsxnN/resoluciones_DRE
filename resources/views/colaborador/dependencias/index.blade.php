{{-- filepath: resources/views/colaborador/dependencias/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Dependencias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🏛️ Gestión de Dependencias</h1>
            <p class="text-gray-600 mt-1">Administración de dependencias institucionales</p>
        </div>
        @can('crear_dependencias')
        <a href="{{ route('colaborador.dependencias.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Dependencia
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre de la dependencia..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="activo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('colaborador.dependencias.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Dependencias</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $dependencias->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Activas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['activas'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Con Resoluciones</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['con_resoluciones'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Dependencias -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($dependencias as $dependencia)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-white">{{ $dependencia->nombre_dependencia }}</h3>
                    @if($dependencia->activo)
                        <span class="px-2 py-1 bg-green-500 text-white rounded text-xs font-semibold">✓</span>
                    @else
                        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs font-semibold">✗</span>
                    @endif
                </div>
                @if($dependencia->siglas_dependencia)
                <p class="text-blue-100 text-sm font-semibold">{{ $dependencia->siglas_dependencia }}</p>
                @endif
            </div>

            <div class="p-6">
                @if($dependencia->descripcion_dependencia)
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($dependencia->descripcion_dependencia, 100) }}</p>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="text-center p-2 bg-blue-50 rounded">
                        <p class="text-lg font-bold text-blue-600">{{ $dependencia->resoluciones_count ?? 0 }}</p>
                        <p class="text-xs text-blue-800">Resoluciones</p>
                    </div>
                    <div class="text-center p-2 bg-green-50 rounded">
                        <p class="text-lg font-bold text-green-600">{{ $dependencia->direcciones_count ?? 0 }}</p>
                        <p class="text-xs text-green-800">Direcciones</p>
                    </div>
                </div>

                <!-- Información Adicional -->
                @if($dependencia->direccion || $dependencia->telefono)
                <div class="space-y-2 text-xs text-gray-600 mb-4 p-3 bg-gray-50 rounded">
                    @if($dependencia->direccion)
                    <div class="flex items-start">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $dependencia->direccion }}</span>
                    </div>
                    @endif
                    @if($dependencia->telefono)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $dependencia->telefono }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Acciones -->
                <div class="flex gap-2">
                    <a href="{{ route('colaborador.dependencias.show', $dependencia) }}" 
                       class="flex-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    
                    @can('editar_dependencias')
                    <a href="{{ route('colaborador.dependencias.edit', $dependencia) }}" 
                       class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </a>
                    @endcan

                    @can('eliminar_dependencias')
                    <form method="POST" 
                          action="{{ route('colaborador.dependencias.destroy', $dependencia) }}" 
                          onsubmit="return confirm('¿Está seguro de eliminar esta dependencia?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition text-sm font-medium">
                            🗑️
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay dependencias</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva dependencia</p>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($dependencias->hasPages())
    <div class="mt-6">
        {{ $dependencias->links() }}
    </div>
    @endif
</div>
@endsection