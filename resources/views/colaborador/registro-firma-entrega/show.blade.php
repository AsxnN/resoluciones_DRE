{{-- filepath: resources/views/colaborador/registro-firma-entrega/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Registro de Firma')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.registro-firma-entrega.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detalle de Registro de Firma</h1>
                <p class="text-sm text-gray-600 mt-1">Código: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $registro->codigo_verificacion }}</span></p>
            </div>
        </div>

        @php
            $color = $registro->color_estado;
            $bgClass = match($color) {
                'green' => 'bg-green-100 text-green-800 border-green-300',
                'blue' => 'bg-blue-100 text-blue-800 border-blue-300',
                'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                default => 'bg-gray-100 text-gray-800 border-gray-300',
            };
        @endphp
        <span class="px-4 py-2 text-sm font-bold rounded-lg border-2 {{ $bgClass }}">
            {{ $registro->estado_visual }}
        </span>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Grid de información -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Información de la Resolución -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                RESOLUCIÓN
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Número</p>
                    <p class="font-semibold text-gray-900">{{ $registro->resolucion->num_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tipo</p>
                    <p class="font-medium text-gray-800">{{ $registro->resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Asunto</p>
                    <p class="text-sm text-gray-700">{{ Str::limit($registro->resolucion->asunto_resolucion, 100) }}</p>
                </div>
                <a href="{{ route('colaborador.resoluciones.show', $registro->resolucion) }}" 
                   class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Ver resolución completa
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Información del Destinatario -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                DESTINATARIO
            </h3>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    {{ strtoupper(substr($registro->personaExterna->nombres, 0, 1) . substr($registro->personaExterna->apellido_paterno, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 text-lg">
                        {{ $registro->personaExterna->nombres }} {{ $registro->personaExterna->apellido_paterno }} {{ $registro->personaExterna->apellido_materno }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $registro->personaExterna->tipo_documento }}: {{ $registro->personaExterna->num_documento }}
                    </p>
                    @if($registro->correo_destino)
                    <p class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $registro->correo_destino }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de fechas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Línea de Tiempo</h3>
        <div class="space-y-4">
            <!-- Solicitud -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Solicitud creada</p>
                    <p class="text-sm text-gray-600">{{ $registro->fecha_solicitud->format('d/m/Y H:i') }}</p>
                    @if($registro->usuarioSolicita)
                    <p class="text-xs text-gray-500">Por: {{ $registro->usuarioSolicita->name }}</p>
                    @endif
                </div>
            </div>

            <!-- Firma -->
            @if($registro->fecha_firma)
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Documento firmado</p>
                    <p class="text-sm text-gray-600">{{ $registro->fecha_firma->format('d/m/Y H:i') }}</p>
                    @if($registro->usuarioFirmante)
                    <p class="text-xs text-gray-500">Por: {{ $registro->usuarioFirmante->name }}</p>
                    @endif
                </div>
            </div>
            @else
            <div class="flex items-start gap-4 opacity-50">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-500">Pendiente de firma</p>
                    <p class="text-sm text-gray-400">Esperando firma digital</p>
                </div>
            </div>
            @endif

            <!-- Entrega -->
            @if($registro->fecha_entrega)
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Entregado al destinatario</p>
                    <p class="text-sm text-gray-600">{{ $registro->fecha_entrega->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @else
            <div class="flex items-start gap-4 opacity-50">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-500">Pendiente de entrega</p>
                    <p class="text-sm text-gray-400">Esperando entrega al destinatario</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Observaciones -->
    @if($registro->observaciones)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <h4 class="text-sm font-bold text-yellow-900 mb-2">Observaciones:</h4>
        <p class="text-sm text-yellow-800 whitespace-pre-wrap">{{ $registro->observaciones }}</p>
    </div>
    @endif

    <!-- Acciones -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
        <div class="flex flex-wrap gap-3">
            @if(!$registro->firmado)
            <!-- Marcar como firmado -->
            <form method="POST" action="{{ route('colaborador.registro-firma-entrega.firmar', $registro) }}" class="inline-block">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones (opcional)</label>
                    <textarea name="observaciones" 
                              rows="2" 
                              placeholder="Notas sobre la firma..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Marcar como Firmado
                </button>
                <p class="text-xs text-gray-500 mt-1">⚠️ Simulación hasta integrar Firma Perú</p>
            </form>
            @elseif(!$registro->fecha_entrega)
            <!-- Registrar entrega -->
            <form method="POST" action="{{ route('colaborador.registro-firma-entrega.registrar-entrega', $registro) }}" class="inline-block w-full">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo destino (opcional)</label>
                        <input type="email" 
                               name="correo_destino" 
                               value="{{ $registro->correo_destino }}"
                               placeholder="correo@ejemplo.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones (opcional)</label>
                        <input type="text" 
                               name="observaciones" 
                               placeholder="Notas sobre la entrega..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Registrar Entrega
                </button>
            </form>
            @else
            <div class="w-full bg-green-50 border-2 border-green-300 rounded-lg p-4 text-center">
                <p class="text-green-800 font-bold flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    ✅ Proceso completado
                </p>
                <p class="text-sm text-green-700 mt-1">Este registro ya fue firmado y entregado</p>
            </div>
            @endif

            <a href="{{ route('colaborador.registro-firma-entrega.index') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection