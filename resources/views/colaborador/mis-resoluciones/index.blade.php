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
                <p class="text-gray-600 mt-1">Resoluciones donde estoy involucrado</p>
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
                    <p class="text-4xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-400">
                <p class="text-xs text-blue-100">Todas mis resoluciones</p>
            </div>
        </div>

        <!-- Creadas por mí -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Creadas</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['creadas'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Por mí</p>
                </div>
            </div>
        </div>

        <!-- Involucrado -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Involucrado</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['involucrado'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Como parte</p>
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
                    <p class="text-3xl font-bold text-green-600">{{ $stats['firmadas'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Por mí</p>
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
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['este_mes'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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

            <!-- Filtro por tipo de relación (NUEVO) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mi Relación</label>
                <select name="relacion" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    <option value="involucrado" {{ request('relacion') === 'involucrado' ? 'selected' : '' }}>Involucrado</option>
                    <option value="notificado" {{ request('relacion') === 'notificado' ? 'selected' : '' }}>Notificado</option>
                    <option value="firmante" {{ request('relacion') === 'firmante' ? 'selected' : '' }}>Firmante</option>
                </select>
            </div>

            <div class="md:col-span-5 flex gap-2">
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
                        
                        <!-- Badge de Estado -->
                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                            {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-100 text-green-800' : 
                               ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $resolucion->estado->nombre_estado }}
                        </span>
                        
                        <!-- Badge de Tipo -->
                        <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 font-semibold">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>

                        <!-- Badge de Relación del Usuario (NUEVO) -->
                        @php
                            $userId = auth()->id();
                            $personaId = auth()->user()->id_persona;
                            $esCreador = $resolucion->id_usuario === $userId;
                            $esFirmante = $resolucion->id_usuario_firma === $userId;
                            $relacionPersona = null;
                            
                            if ($personaId && $resolucion->personas) {
                                $persona = $resolucion->personas->firstWhere('id_persona', $personaId);
                                if ($persona) {
                                    $relacionPersona = $persona->pivot->tipo_relacion;
                                }
                            }
                        @endphp

                        @if($esCreador)
                        <span class="px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold">
                            👤 Creador
                        </span>
                        @endif

                        @if($esFirmante)
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                            ✍️ Firmante
                        </span>
                        @endif

                        @if($relacionPersona)
                        <span class="px-3 py-1 text-xs rounded-full bg-cyan-100 text-cyan-800 font-semibold">
                            @if($relacionPersona === 'involucrado')
                                👥 Involucrado
                            @elseif($relacionPersona === 'notificado')
                                🔔 Notificado
                            @elseif($relacionPersona === 'firmante')
                                📝 Firmante Asignado
                            @endif
                        </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-700 mb-2">{{ $resolucion->asunto_resolucion }}</p>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>📅 {{ $resolucion->fecha_resolucion->format('d/m/Y') }}</span>
                        @if($resolucion->usuarioCreador)
                        <span>👤 Creado por: {{ $resolucion->usuarioCreador->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('colaborador.mis-resoluciones.show', $resolucion->id_resolucion) }}" 
                       class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    @if($resolucion->archivo_firmado)
                    <a href="{{ route('colaborador.resoluciones.descargar-firmado', $resolucion) }}" 
                       class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition text-sm font-medium">
                        📥 Descargar
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
            <p class="text-gray-500">Aún no tienes resoluciones creadas ni estás involucrado en ninguna</p>
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