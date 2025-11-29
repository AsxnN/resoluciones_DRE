{{-- filepath: resources/views/cliente/resoluciones/show.blade.php --}}
@extends('layouts.cliente')

@section('title', 'Ver Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('cliente.resoluciones.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📄 Resolución</h1>
                    <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
                </div>
            </div>
            
            @if($resolucion->archivo_firmado)
            <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
               target="_blank"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                📥 Descargar PDF
            </a>
            @endif
        </div>
    </div>

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
            <div class="text-white">
                <h2 class="text-2xl font-bold">{{ $resolucion->num_resolucion }}</h2>
                <p class="text-blue-100 mt-2">{{ $resolucion->asunto_resolucion }}</p>
                <div class="flex items-center gap-4 mt-4">
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                    </span>
                    <span class="px-3 py-1 {{ $resolucion->estado->nombre_estado === 'Publicada' ? 'bg-green-500' : 'bg-blue-400' }} rounded-full text-sm font-semibold">
                        {{ $resolucion->estado->nombre_estado }}
                    </span>
                    <span class="text-sm text-blue-100">
                        📅 {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <!-- Información General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Información</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Número de Resolución</p>
                            <p class="font-semibold text-gray-900">{{ $resolucion->num_resolucion }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha</p>
                            <p class="font-semibold text-gray-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tipo</p>
                            <p class="font-semibold text-gray-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estado</p>
                            <p class="font-semibold text-gray-900">{{ $resolucion->estado->nombre_estado }}</p>
                        </div>
                    </div>
                </div>

                <!-- Verificación -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Verificación</h3>
                    
                    @if($resolucion->archivo_firmado)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-900">Documento Firmado</p>
                                <p class="text-xs text-green-700">Esta resolución cuenta con firma digital válida</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($resolucion->codigo_qr)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-3">Código QR de Verificación</p>
                        <img src="data:image/svg+xml;base64,{{ base64_encode($resolucion->codigo_qr) }}" 
                             alt="QR Code" 
                             class="mx-auto w-40 h-40">
                        <p class="text-xs text-gray-500 mt-2">Escanea para verificar autenticidad</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contenido de la Resolución -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Contenido</h3>
                <div class="prose max-w-none bg-gray-50 p-6 rounded-lg">
                    <p class="whitespace-pre-wrap text-gray-700">{{ $resolucion->contenido_resolucion }}</p>
                </div>
            </div>

            <!-- Archivo Adjunto -->
            @if($resolucion->archivo_firmado)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📎 Archivo</h3>
                <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                   target="_blank"
                   class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <div class="ml-4 flex-1">
                        <p class="font-semibold text-gray-900">Descargar Resolución Firmada</p>
                        <p class="text-sm text-gray-500">{{ basename($resolucion->archivo_firmado) }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
            @endif

            <!-- Información del Sistema -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Información del Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">ID del Sistema</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $resolucion->id_resolucion }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">Fecha de Publicación</p>
                        <p class="font-semibold text-gray-900">{{ $resolucion->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">Última Actualización</p>
                        <p class="font-semibold text-gray-900">{{ $resolucion->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de Regreso -->
    <div class="mt-6">
        <a href="{{ route('cliente.resoluciones.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
            ← Volver a Mis Resoluciones
        </a>
    </div>
</div>
@endsection