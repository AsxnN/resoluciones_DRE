{{-- filepath: resources/views/colaborador/resoluciones-firmadas/historial.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Historial de Firmas - ' . $resolucion->num_resolucion)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.resoluciones-firmadas.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Historial de Firmas para Entrega</h1>
                <p class="text-gray-600 mt-1">Todos los registros de firma de esta resolución</p>
            </div>
        </div>

        <!-- Información de la Resolución -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-purple-500 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Número</p>
                    <p class="font-bold text-purple-900 text-lg">{{ $resolucion->num_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Tipo</p>
                    <p class="font-semibold text-purple-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Fecha</p>
                    <p class="font-semibold text-purple-900">{{ $resolucion->fecha_resolucion ? $resolucion->fecha_resolucion->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Total Registros</p>
                    <p class="font-bold text-purple-900 text-lg">{{ $registros->count() }}</p>
                </div>
                <div class="md:col-span-4">
                    <p class="text-xs text-purple-700 font-medium mb-1">Asunto</p>
                    <p class="text-sm text-purple-900">{{ $resolucion->asunto_resolucion }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Solicitudes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $registros->count() }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Firmadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $registros->where('firmado', true)->count() }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Entregadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $registros->whereNotNull('fecha_entrega')->count() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Registros -->
    <div class="space-y-4">
        @forelse($registros as $registro)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar -->
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                            {{ strtoupper(substr($registro->personaExterna->nombres, 0, 1) . substr($registro->personaExterna->apellido_paterno, 0, 1)) }}
                        </div>

                        <!-- Info Destinatario -->
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $registro->personaExterna->nombres }} {{ $registro->personaExterna->apellido_paterno }} {{ $registro->personaExterna->apellido_materno }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $registro->personaExterna->tipo_documento }}: {{ $registro->personaExterna->num_documento }}
                            </p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $registro->codigo_verificacion }}
                                </span>
                                @php
                                    $color = $registro->color_estado;
                                    $bgClass = match($color) {
                                        'green' => 'bg-green-100 text-green-800',
                                        'blue' => 'bg-blue-100 text-blue-800',
                                        'yellow' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bgClass }}">
                                    {{ $registro->estado_visual }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Ver Detalle -->
                    <a href="{{ route('colaborador.registro-firma-entrega.show', $registro) }}" 
                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Ver Detalle
                    </a>
                </div>

                <!-- Timeline -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
                    <!-- Solicitud -->
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Solicitado</p>
                            <p class="text-xs text-gray-600">{{ $registro->fecha_solicitud ? $registro->fecha_solicitud->format('d/m/Y H:i') : 'N/A' }}</p>
                            @if($registro->usuarioSolicita)
                            <p class="text-xs text-gray-500">{{ $registro->usuarioSolicita->name }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Firma -->
                    <div class="flex items-start gap-3">
                        @if($registro->fecha_firma)
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Firmado</p>
                            <p class="text-xs text-gray-600">{{ $registro->fecha_firma->format('d/m/Y H:i') }}</p>
                            @if($registro->usuarioFirmante)
                            <p class="text-xs text-gray-500">{{ $registro->usuarioFirmante->name }}</p>
                            @endif
                        </div>
                        @else
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Pendiente firma</p>
                        </div>
                        @endif
                    </div>

                    <!-- Entrega -->
                    <div class="flex items-start gap-3">
                        @if($registro->fecha_entrega)
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Entregado</p>
                            <p class="text-xs text-gray-600">{{ $registro->fecha_entrega->format('d/m/Y H:i') }}</p>
                            @if($registro->correo_destino)
                            <p class="text-xs text-gray-500">{{ $registro->correo_destino }}</p>
                            @endif
                        </div>
                        @else
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Pendiente entrega</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Observaciones -->
                @if($registro->observaciones)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border-l-2 border-gray-300">
                    <p class="text-xs font-semibold text-gray-700 mb-1">Observaciones:</p>
                    <p class="text-sm text-gray-600">{{ $registro->observaciones }}</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No hay registros de firma</h3>
            <p class="text-gray-500">No se han creado registros de firma para esta resolución</p>
        </div>
        @endforelse
    </div>

    <!-- Botones de acción -->
    <div class="mt-6 flex gap-3">
        <a href="{{ route('colaborador.resoluciones-firmadas.index') }}" 
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
            Volver al Listado
        </a>

        <a href="{{ route('colaborador.registro-firma-entrega.create', $resolucion) }}" 
           class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Solicitud de Firma
        </a>

        <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
           class="px-6 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-lg transition-colors">
            Ver Resolución Completa
        </a>
    </div>
</div>
@endsection