{{-- filepath: resources/views/colaborador/resoluciones/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Ver Resolución')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.resoluciones.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📄 Detalles de Resolución</h1>
                    <p class="text-gray-600 mt-1">Información completa del registro</p>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="flex gap-2">
                @can('editar_resoluciones')
                <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" 
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition">
                    ✏️ Editar
                </a>
                @endcan
                
                @if($resolucion->archivo_firmado)
                <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    📥 Descargar PDF
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h2 class="text-3xl font-bold">{{ $resolucion->num_resolucion }}</h2>
                    <p class="text-blue-100 mt-2">{{ $resolucion->asunto_resolucion }}</p>
                    <div class="flex items-center gap-4 mt-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>
                        <span class="px-3 py-1 {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-500' : 
                               ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-500' : 'bg-blue-400') }} rounded-full text-sm font-semibold">
                            {{ $resolucion->estado->nombre_estado }}
                        </span>
                        <span class="text-sm text-blue-100">
                            📅 {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-blue-600 text-2xl font-bold shadow-lg">
                    {{ substr($resolucion->num_resolucion, 0, 3) }}
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <!-- Grid de Información -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Columna 1 -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Información General</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Número</p>
                                <p class="text-gray-900 font-semibold">{{ $resolucion->num_resolucion }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Fecha</p>
                                <p class="text-gray-900 font-semibold">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tipo</p>
                                <p class="text-gray-900 font-semibold">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Estado y Seguimiento</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Estado</p>
                                <p class="text-gray-900 font-semibold">{{ $resolucion->estado->nombre_estado }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Firmado</p>
                                <p class="text-gray-900 font-semibold">
                                    @if($resolucion->archivo_firmado)
                                        <span class="text-green-600">✓ Sí</span>
                                    @else
                                        <span class="text-red-600">✗ No</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($resolucion->firma)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Firmado por</p>
                                <p class="text-gray-900 font-semibold">{{ $resolucion->firma->usuario->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Columna 3 -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📁 Archivos</h3>
                    
                    <div class="space-y-3">
                        @if($resolucion->archivo_original)
                        <a href="{{ Storage::url($resolucion->archivo_original) }}" 
                           target="_blank"
                           class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Archivo Original</p>
                                        <p class="text-xs text-gray-500">{{ basename($resolucion->archivo_original) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endif

                        @if($resolucion->archivo_firmado)
                        <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                           target="_blank"
                           class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">PDF Firmado</p>
                                        <p class="text-xs text-gray-500">{{ basename($resolucion->archivo_firmado) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido de la Resolución -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Contenido de la Resolución</h3>
                <div class="prose max-w-none bg-gray-50 p-6 rounded-lg">
                    <p class="whitespace-pre-wrap text-gray-700">{{ $resolucion->contenido_resolucion }}</p>
                </div>
            </div>

            <!-- Observaciones -->
            @if($resolucion->observaciones)
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">💬 Observaciones</h3>
                <div class="bg-yellow-50 p-6 rounded-lg">
                    <p class="text-gray-700">{{ $resolucion->observaciones }}</p>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Información del Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">ID del Sistema</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $resolucion->id_resolucion }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">Creado</p>
                        <p class="font-semibold text-gray-900">{{ $resolucion->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">Última Actualización</p>
                        <p class="font-semibold text-gray-900">{{ $resolucion->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 mb-1">QR Code</p>
                        <p class="font-semibold text-gray-900">
                            @if($resolucion->codigo_qr)
                                <span class="text-green-600">✓ Generado</span>
                            @else
                                <span class="text-gray-400">✗ No generado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Adicionales -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        @can('editar_resoluciones')
        <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" 
           class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg text-center transition">
            ✏️ Editar Resolución
        </a>
        @endcan

        @if(!$resolucion->archivo_firmado && $resolucion->estado->nombre_estado === 'Pendiente de Firma')
        @can('firmar_resoluciones')
        <a href="{{ route('colaborador.firmas.create', ['resolucion' => $resolucion->id_resolucion]) }}" 
           class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-center transition">
            ✍️ Firmar Digitalmente
        </a>
        @endcan
        @endif

        @can('eliminar_resoluciones')
        <form method="POST" 
              action="{{ route('colaborador.resoluciones.destroy', $resolucion) }}" 
              onsubmit="return confirm('¿Está seguro de eliminar esta resolución? Esta acción no se puede deshacer.')"
              class="w-full">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                🗑️ Eliminar Resolución
            </button>
        </form>
        @endcan
    </div>
</div>
@endsection