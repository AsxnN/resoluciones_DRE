{{-- filepath: resources/views/colaborador/mis-resoluciones/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h1>
                <p class="text-gray-600 mt-1">Detalle de la resolución</p>
            </div>
        </div>
    </div>

    <!-- Estado y Tipo -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Estado Actual</h3>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-100 text-green-800' : 
                   ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                {{ $resolucion->estado->nombre_estado }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Tipo de Resolución</h3>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
            </span>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Información de la Resolución
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Número -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Número de Resolución</label>
                <p class="text-lg font-semibold text-gray-900">{{ $resolucion->num_resolucion }}</p>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Resolución</label>
                <p class="text-lg text-gray-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
            </div>

            <!-- Asunto -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Asunto</label>
                <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $resolucion->asunto_resolucion }}</p>
            </div>

            <!-- Visto -->
            @if($resolucion->visto_resolucion)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Visto</label>
                <div class="text-gray-900 bg-gray-50 p-4 rounded-lg prose max-w-none">
                    {!! nl2br(e($resolucion->visto_resolucion)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Usuario Creador -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Creado Por
        </h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($resolucion->usuarioCreador->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ $resolucion->usuarioCreador->name }}</p>
                <p class="text-sm text-gray-500">{{ $resolucion->usuarioCreador->email }}</p>
                <p class="text-xs text-gray-400">{{ $resolucion->fecha_creacion->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Firmante (si existe) -->
    @if($resolucion->usuarioFirmante)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Firmado Por
        </h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($resolucion->usuarioFirmante->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ $resolucion->usuarioFirmante->name }}</p>
                <p class="text-sm text-gray-500">{{ $resolucion->usuarioFirmante->email }}</p>
                @if($resolucion->fecha_firma)
                <p class="text-xs text-gray-400">{{ $resolucion->fecha_firma->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Archivos -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Documentos
        </h3>

        <div class="space-y-3">
            @if($resolucion->archivo_resolucion)
            <a href="{{ Storage::url($resolucion->archivo_resolucion) }}" 
               target="_blank"
               class="flex items-center justify-between p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Archivo Original</p>
                        <p class="text-sm text-gray-500">{{ basename($resolucion->archivo_resolucion) }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>
            @endif

            @if($resolucion->archivo_firmado)
            <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
               target="_blank"
               class="flex items-center justify-between p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Archivo Firmado</p>
                        <p class="text-sm text-gray-500">{{ basename($resolucion->archivo_firmado) }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>
            @endif

            @if(!$resolucion->archivo_resolucion && !$resolucion->archivo_firmado)
            <p class="text-center text-gray-500 py-4">No hay documentos adjuntos</p>
            @endif
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex justify-between items-center">
        <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
            ← Volver a Mis Resoluciones
        </a>

        @if($resolucion->archivo_firmado)
        <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
           download
           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
            📥 Descargar Firmado
        </a>
        @endif
    </div>
</div>
@endsection