{{-- filepath: resources/views/colaborador/mis-resoluciones/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Mis Resoluciones')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📑 Mis Resoluciones</h1>
                <p class="text-gray-600 mt-1">Resoluciones creadas por mí</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Filtro rápido -->
                <div class="flex items-center gap-2 bg-white rounded-lg shadow px-4 py-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Personales -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-medium">Total</p>
                    <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-400">
                <p class="text-xs text-blue-100">Resoluciones creadas</p>
            </div>
        </div>

        <!-- Borradores -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Borradores</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['borradores'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pendientes</p>
                </div>
            </div>
        </div>

        <!-- En Revisión -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">En Revisión</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['revision'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Proceso</p>
                </div>
            </div>
        </div>

        <!-- Firmadas -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Firmadas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['firmadas'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Completadas</p>
                </div>
            </div>
        </div>

        <!-- Este Mes -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Este Mes</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['mes_actual'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Número o asunto..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado->id_estado }}" {{ request('estado') == $estado->id_estado ? 'selected' : '' }}>
                        {{ $estado->nombre_estado }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id_tipo_resolucion }}" {{ request('tipo') == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
                        {{ $tipo->nombre_tipo_resolucion }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Filtrar
                </button>
                <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄 Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Resoluciones -->
    <div class="space-y-4">
        @forelse($resoluciones as $resolucion)
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h3>
                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                            {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-100 text-green-800' : 
                               ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $resolucion->estado->nombre_estado }}
                        </span>
                        <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 font-semibold">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>
                    </div>
                    <p class="text-gray-700 mb-2">{{ $resolucion->asunto_resolucion }}</p>
                    <p class="text-sm text-gray-500">📅 {{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('colaborador.mis-resoluciones.show', $resolucion->id_resolucion) }}" 
                       class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    @if($resolucion->archivo_firmado)
                    <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                       target="_blank"
                       class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition text-sm font-medium">
                        📥
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes resoluciones</h3>
            <p class="text-gray-500">Aún no has creado ninguna resolución</p>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($resoluciones->hasPages())
    <div class="mt-6">
        {{ $resoluciones->links() }}
    </div>
    @endif
</div>
@endsection