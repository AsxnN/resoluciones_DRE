{{-- filepath: resources/views/colaborador/resoluciones/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Ver Resolución')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.resoluciones.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Detalles de Resolución
                    </h1>
                    <p class="text-gray-600 mt-1">Información completa del registro</p>
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            <div class="flex gap-2 flex-wrap">
                @can('resoluciones.editar')
                    @if(!$resolucion->archivo_firmado)
                    <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" 
                       class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    @endif
                @endcan
                
                {{-- @if(!$resolucion->archivo_firmado)
                <a href="{{ route('colaborador.resoluciones.firmar', $resolucion) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Firmar Digitalmente
                </a>
                @else
                <a href="{{ route('colaborador.resoluciones.descargarFirmado', $resolucion) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar PDF Firmado
                </a>
                @endif --}}
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-10">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="text-white flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($resolucion->num_resolucion, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-4xl font-bold">{{ $resolucion->num_resolucion }}</h2>
                            <p class="text-blue-100 text-sm mt-1">
                                Creado {{ $resolucion->fecha_creacion->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center flex-wrap gap-3 mt-6">
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>
                        @php
                            $estadoNombre = $resolucion->estado->nombre_estado ?? 'Desconocido';
                            $estadoBgClass = match($estadoNombre) {
                                'Firmada', 'Firmado', 'Aprobada', 'Aprobado' => 'bg-green-500',
                                'Borrador' => 'bg-yellow-500',
                                'Pendiente', 'En Proceso' => 'bg-blue-400',
                                default => 'bg-gray-400',
                            };
                        @endphp
                        <span class="px-4 py-2 {{ $estadoBgClass }} rounded-full text-sm font-semibold text-white flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $estadoNombre }}
                        </span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-8">
            <!-- Grid de Información Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Información General - 2 columnas -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Visto -->
                    @if($resolucion->visto_resolucion)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            Visto
                        </h3>
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $resolucion->visto_resolucion }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Asunto -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            Asunto de la Resolución
                        </h3>
                        <div class="bg-purple-50 p-5 rounded-lg border border-purple-200">
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $resolucion->asunto_resolucion }}</p>
                        </div>
                    </div>

                    <!-- Creador -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            Creado por
                        </h3>
                        <div class="bg-indigo-50 p-5 rounded-lg border border-indigo-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($resolucion->usuarioCreador->name ?? 'N', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-gray-900 font-bold">{{ $resolucion->usuarioCreador->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">{{ $resolucion->usuarioCreador->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral - 1 columna -->
                <div class="space-y-6">
                    <!-- Estado y Firma -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado y Seguimiento</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Estado Actual</p>
                                <p class="text-gray-900 font-bold text-lg">{{ $resolucion->estado->nombre_estado }}</p>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-300">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Estado de Firma</p>
                                <div class="flex items-center gap-2 mt-2">
                                    @if($resolucion->archivo_firmado)
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-700 font-semibold">Firmada</span>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-yellow-700 font-semibold">Sin firmar</span>
                                    @endif
                                </div>
                            </div>

                            @if($resolucion->archivo_firmado && $resolucion->fecha_firma)
                            <div class="pt-4 border-t border-gray-300">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha de Firma</p>
                                <p class="text-gray-900 font-medium">{{ $resolucion->fecha_firma->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $resolucion->fecha_firma->diffForHumans() }}</p>
                            </div>
                            @endif

                            @if($resolucion->usuarioFirmante)
                            <div class="pt-4 border-t border-gray-300">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Firmado por</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($resolucion->usuarioFirmante->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $resolucion->usuarioFirmante->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $resolucion->usuarioFirmante->email }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Archivos -->
                    <div class="bg-white rounded-lg border-2 border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            Archivos
                        </h3>
                        
                        <div class="space-y-3">
                            @if($resolucion->archivo_resolucion && Storage::disk('public')->exists($resolucion->archivo_resolucion))
                            <a href="{{ route('colaborador.resoluciones.descargar', $resolucion) }}" 
                               class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">Archivo Original</p>
                                        <p class="text-xs text-gray-500 truncate">{{ basename($resolucion->archivo_resolucion) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ number_format(Storage::disk('public')->size($resolucion->archivo_resolucion) / 1024, 2) }} KB</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </a>
                            @endif

                            @if($resolucion->archivo_firmado && Storage::disk('public')->exists($resolucion->archivo_firmado))
                            <a href="{{ route('colaborador.resoluciones.descargarFirmado', $resolucion) }}" 
                               class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border-2 border-green-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-green-900">Archivo Firmado ✓</p>
                                        <p class="text-xs text-green-700 truncate">{{ basename($resolucion->archivo_firmado) }}</p>
                                        <p class="text-xs text-green-600 mt-1">{{ number_format(Storage::disk('public')->size($resolucion->archivo_firmado) / 1024, 2) }} KB</p>
                                    </div>
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </a>
                            @endif

                            @if(!$resolucion->archivo_resolucion && !$resolucion->archivo_firmado)
                            <div class="text-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm">Sin archivos adjuntos</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Información del Sistema</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500">ID</p>
                                <p class="font-mono font-semibold text-gray-900">{{ $resolucion->id_resolucion }}</p>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-gray-500">Creado</p>
                                <p class="text-gray-900">{{ $resolucion->fecha_creacion->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-gray-500">Última Actualización</p>
                                <p class="text-gray-900">{{ $resolucion->fecha_actualizacion->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción inferiores -->
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('colaborador.resoluciones.index') }}" 
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Listado
        </a>

        @can('resoluciones.editar')
            @if(!$resolucion->archivo_firmado)
            <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" 
               class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar Resolución
            </a>
            @endif
        @endcan

        {{-- @if(!$resolucion->archivo_firmado)
        <a href="{{ route('colaborador.resoluciones.firmar', $resolucion) }}" 
           class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Firmar Digitalmente
        </a>
        @endif --}}

        @can('resoluciones.eliminar')
            @if(!$resolucion->archivo_firmado)
            <form method="POST" 
                  action="{{ route('colaborador.resoluciones.destroy', $resolucion) }}" 
                  onsubmit="return confirm('¿Está seguro de eliminar esta resolución?\n\nNúmero: {{ $resolucion->num_resolucion }}\n\nEsta acción no se puede deshacer.')"
                  class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar Resolución
                </button>
            </form>
            @endif
        @endcan
    </div>
</div>
@endsection