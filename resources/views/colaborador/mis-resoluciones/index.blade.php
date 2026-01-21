{{-- filepath: resources/views/colaborador/mis-resoluciones/index.blade.php --}}
@extends('layouts.colaborador')

@php
    // Asegurar que $tipoAcceso esté definido
    $tipoAcceso = $tipoAcceso ?? Auth::user()->tipo_acceso;
@endphp

@section('title', 'Mis Resoluciones')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Mis Resoluciones
        </h1>
        <p class="text-gray-600 mt-1">
            @if($tipoAcceso === 'cliente')
                Resoluciones asignadas a tu cuenta
            @else
                Resoluciones en las que estás involucrado
            @endif
        </p>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Información de tipo de usuario -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="text-sm font-bold text-blue-900 mb-1">
                    @if($tipoAcceso === 'cliente')
                        🌐 Usuario Externo
                    @else
                        👔 Trabajador DRE
                    @endif
                </h4>
                <p class="text-xs text-blue-800">
                    @if($tipoAcceso === 'cliente')
                        Solo puedes ver resoluciones firmadas que han sido asignadas específicamente a tu cuenta.
                    @else
                        Puedes ver todas las resoluciones en las que estás relacionado, sin necesidad de asignación adicional.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Número, asunto..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="tipo" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos los tipos</option>
                        @foreach($tiposResolucion as $tipo)
                            <option value="{{ $tipo->id_tipo_resolucion }}" {{ request('tipo') == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo_resolucion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                    <input type="date" 
                           name="fecha_desde" 
                           value="{{ request('fecha_desde') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Fecha Hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                    <input type="date" 
                           name="fecha_hasta" 
                           value="{{ request('fecha_hasta') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                <a href="{{ route('colaborador.mis-resoluciones.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Resoluciones -->
    <div class="space-y-4">
        @forelse($resoluciones as $resolucion)
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden border border-gray-200">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4 gap-4">
                    <div class="flex-1 min-w-0">
                        <!-- Número de resolución -->
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $resolucion->num_resolucion }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {{ $resolucion->fecha_resolucion ? $resolucion->fecha_resolucion->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Asunto -->
                        <p class="text-sm text-gray-700 mb-3 line-clamp-2">
                            {{ $resolucion->asunto_resolucion }}
                        </p>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Tipo -->
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                            </span>
                            
                            <!-- Estado -->
                            @php
                                $estadoNombre = $resolucion->estado->nombre_estado ?? 'Desconocido';
                                $estadoClass = match($estadoNombre) {
                                    'Firmada', 'Firmado', 'Aprobada', 'Aprobado' => 'bg-green-100 text-green-800',
                                    'Borrador' => 'bg-yellow-100 text-yellow-800',
                                    'Pendiente', 'En Proceso' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $estadoClass }}">
                                {{ $estadoNombre }}
                            </span>

                            <!-- Firmada -->
                            @if($resolucion->archivo_firmado)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Firmada
                            </span>
                            @endif

                            <!-- Badge de tipo de usuario -->
                            @if($tipoAcceso === 'cliente')
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                🌐 Cliente Externo
                            </span>
                            @else
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                👔 Personal DRE
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <a href="{{ route('colaborador.mis-resoluciones.show', $resolucion) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver Detalle
                        </a>
                        
                        @if($resolucion->archivo_firmado || ($tipoAcceso !== 'cliente' && $resolucion->archivo_resolucion))
                        <a href="{{ route('colaborador.mis-resoluciones.descargar', $resolucion) }}" 
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar PDF
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-xs">
                            <span class="text-gray-500">Creado por:</span>
                            <span class="font-medium text-gray-900">{{ $resolucion->usuarioCreador->name ?? 'N/A' }}</span>
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs">
                            <span class="text-gray-500">Creado:</span>
                            <span class="font-medium text-gray-900">{{ $resolucion->fecha_creacion ? $resolucion->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}</span>
                        </span>
                    </div>

                    @if($resolucion->fecha_firma)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs">
                            <span class="text-gray-500">Firmado:</span>
                            <span class="font-medium text-green-700">{{ $resolucion->fecha_firma->format('d/m/Y H:i') }}</span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No hay resoluciones disponibles</h3>
                <p class="text-gray-600 text-sm">
                    @if($tipoAcceso === 'cliente')
                        No tienes resoluciones asignadas aún. Las resoluciones firmadas que te asignen aparecerán aquí.
                    @else
                        No estás relacionado con ninguna resolución. Las resoluciones donde aparezca tu nombre se mostrarán aquí automáticamente.
                    @endif
                </p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($resoluciones->hasPages())
    <div class="mt-6 bg-white rounded-lg shadow-md p-4">
        {{ $resoluciones->links() }}
    </div>
    @endif
</div>
@endsection