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
                    <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
                </div>
            </div>
            
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
        <!-- Header del Card con info básica HORIZONTAL -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white">
                <!-- Número de Resolución -->
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Número</p>
                    <p class="text-2xl font-bold">{{ $resolucion->num_resolucion }}</p>
                </div>
                
                <!-- Tipo -->
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Tipo</p>
                    <p class="text-lg font-semibold">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                </div>
                
                <!-- Fecha -->
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Fecha</p>
                    <p class="text-lg font-semibold">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
                    <p class="text-blue-100 text-xs">{{ $resolucion->fecha_resolucion->diffForHumans() }}</p>
                </div>
            </div>
            
            <!-- Estados en horizontal -->
            <div class="flex items-center gap-3 mt-4 flex-wrap">
                @php
                    $estadoNombre = $resolucion->estado->nombre_estado ?? 'Desconocido';
                    $estadoBgClass = match($estadoNombre) {
                        'Firmada', 'Firmado', 'Aprobada', 'Aprobado' => 'bg-green-500',
                        'Borrador' => 'bg-yellow-500',
                        'Pendiente', 'En Proceso' => 'bg-blue-400',
                        default => 'bg-gray-400',
                    };
                @endphp
                <span class="px-3 py-1.5 {{ $estadoBgClass }} rounded-full text-sm font-semibold text-white flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $estadoNombre }}
                </span>
                
                @if($resolucion->archivo_firmado)
                <span class="px-3 py-1.5 bg-green-500 rounded-full text-sm font-semibold text-white flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Firmada
                </span>
                @else
                <span class="px-3 py-1.5 bg-yellow-500 rounded-full text-sm font-semibold text-white flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Sin firmar
                </span>
                @endif
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="p-6">
            <!-- Información Básica en Grid Horizontal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Visto -->
                @if($resolucion->visto_resolucion)
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <h3 class="text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        VISTO
                    </h3>
                    <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $resolucion->visto_resolucion }}</p>
                </div>
                @endif

                <!-- Creador -->
                <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-500">
                    <h3 class="text-sm font-bold text-indigo-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        CREADO POR
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($resolucion->usuarioCreador->name ?? 'N', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-gray-900 font-semibold text-sm">{{ $resolucion->usuarioCreador->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $resolucion->usuarioCreador->email ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $resolucion->fecha_creacion->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asunto (Ancho completo) -->
            <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500 mb-6">
                <h3 class="text-sm font-bold text-purple-900 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ASUNTO
                </h3>
                <p class="text-gray-800 font-medium text-sm">{{ $resolucion->asunto_resolucion }}</p>
            </div>

            <!-- Información de Firma (si está firmada) -->
            @if($resolucion->archivo_firmado)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                    <h3 class="text-sm font-bold text-green-900 mb-2">FECHA DE FIRMA</h3>
                    <p class="text-gray-900 font-semibold">{{ $resolucion->fecha_firma->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $resolucion->fecha_firma->diffForHumans() }}</p>
                </div>

                @if($resolucion->usuarioFirmante)
                <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                    <h3 class="text-sm font-bold text-green-900 mb-2">FIRMADO POR</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($resolucion->usuarioFirmante->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-gray-900 font-semibold text-sm">{{ $resolucion->usuarioFirmante->name }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $resolucion->usuarioFirmante->email }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Archivos en Horizontal -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    ARCHIVOS ADJUNTOS
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @if($resolucion->archivo_resolucion && Storage::disk('public')->exists($resolucion->archivo_resolucion))
                    <a href="{{ route('colaborador.resoluciones.descargar', $resolucion) }}" 
                       class="flex items-center gap-3 p-3 bg-white hover:bg-gray-100 rounded-lg transition-colors border border-gray-300 group">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600">Archivo Original</p>
                            <p class="text-xs text-gray-500">Descargar PDF</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endif

                    @if($resolucion->archivo_firmado && Storage::disk('public')->exists($resolucion->archivo_firmado))
                    <a href="{{ route('colaborador.resoluciones.descargarFirmado', $resolucion) }}" 
                       class="flex items-center gap-3 p-3 bg-white hover:bg-green-50 rounded-lg transition-colors border-2 border-green-300 group">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-green-900 group-hover:text-green-700">Archivo Firmado</p>
                            <p class="text-xs text-green-600">Descargar PDF Firmado</p>
                        </div>
                        <svg class="w-5 h-5 text-green-600 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endif

                    @if(!$resolucion->archivo_resolucion && !$resolucion->archivo_firmado)
                    <div class="col-span-2 text-center py-6 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Sin archivos adjuntos</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Personas Relacionadas -->
            @php
                $personasInternas = $resolucion->personasRelacionadas()->where('es_interna', true)->get();
                $personasExternas = $resolucion->personasRelacionadas()->where('es_interna', false)->get();
                $totalPersonas = $personasInternas->count() + $personasExternas->count();
            @endphp

            @if($totalPersonas > 0)
            <div class="border-2 border-purple-200 rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
                    <h3 class="text-white font-bold text-base flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            PERSONAS RELACIONADAS
                        </span>
                        <span class="px-2 py-1 bg-white text-purple-700 rounded-full text-sm font-bold">
                            {{ $totalPersonas }}
                        </span>
                    </h3>
                </div>

                <div class="p-4 bg-gray-50">
                    <!-- Personas INTERNAS -->
                    @if($personasInternas->count() > 0)
                    <div class="mb-4">
                        <div class="bg-green-100 px-3 py-2 rounded-t-lg border-b-2 border-green-300">
                            <h4 class="text-green-900 font-bold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                👔 Trabajadores DRE ({{ $personasInternas->count() }})
                            </h4>
                        </div>
                        <div class="bg-white border-2 border-green-200 rounded-b-lg divide-y divide-gray-100">
                            @foreach($personasInternas as $persona)
                            <div class="p-4 hover:bg-green-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr($persona->nombres ?? 'N', 0, 1) . substr($persona->apellido_paterno ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900">
                                            {{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                                            <span>📄 {{ $persona->tipo_documento }}: {{ $persona->num_documento }}</span>
                                            @if($persona->tipo_relacion)
                                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                {{ $persona->tipo_relacion }}
                                            </span>
                                            @endif
                                        </div>
                                        @if($persona->descripcion_relacion)
                                        <p class="text-xs text-gray-600 italic mt-1">"{{ $persona->descripcion_relacion }}"</p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            ✓ Usuario del sistema
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Personas EXTERNAS -->
                    @if($personasExternas->count() > 0)
                    <div>
                        <div class="bg-blue-100 px-3 py-2 rounded-t-lg border-b-2 border-blue-300">
                            <h4 class="text-blue-900 font-bold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            🌐 Personas Externas ({{ $personasExternas->count() }})
                        </div>
                        <div class="bg-white border-2 border-blue-200 rounded-b-lg divide-y-2 divide-gray-200">
                            @foreach($personasExternas as $persona)
                            @php
                                $tieneUsuario = $persona->id_user !== null;
                                $usuarioSistema = null;
                                
                                if ($tieneUsuario) {
                                    $usuarioSistema = \App\Models\User::find($persona->id_user);
                                }
                            @endphp
                            <div class="p-4">
                                <!-- Cabecera de persona en HORIZONTAL -->
                                <div class="flex items-start gap-4 mb-4">
                                    <!-- Avatar -->
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                        {{ strtoupper(substr($persona->nombres ?? 'N', 0, 1) . substr($persona->apellido_paterno ?? 'A', 0, 1)) }}
                                    </div>
                                    
                                    <!-- Información básica -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4 flex-wrap">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-gray-900 text-lg">
                                                    {{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                                </p>
                                                <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 flex-wrap">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                        </svg>
                                                        {{ $persona->tipo_documento }}: <strong>{{ $persona->num_documento }}</strong>
                                                    </span>
                                                    @if($persona->tipo_relacion)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                        {{ $persona->tipo_relacion }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @if($persona->descripcion_relacion)
                                                <p class="text-sm text-gray-600 italic mt-2 bg-gray-50 p-2 rounded border border-gray-200">
                                                    "{{ $persona->descripcion_relacion }}"
                                                </p>
                                                @endif
                                            </div>
                                            
                                            <!-- Estado de usuario -->
                                            <div class="flex-shrink-0">
                                                @if($tieneUsuario && $usuarioSistema)
                                                <div class="bg-green-50 border-2 border-green-300 rounded-lg px-3 py-2 text-center min-w-[200px]">
                                                    <div class="flex items-center justify-center gap-2 mb-1">
                                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-green-800 font-bold text-sm">Usuario Registrado</span>
                                                    </div>
                                                    <p class="text-xs text-green-700 font-mono bg-green-100 px-2 py-1 rounded">
                                                        📧 {{ $usuarioSistema->email }}
                                                    </p>
                                                </div>
                                                @else
                                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg px-3 py-2 text-center min-w-[200px]">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-yellow-800 font-bold text-sm">Sin cuenta de usuario</span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulario y Botón de Asignación -->
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- FORMULARIO DE ENVÍO DE CREDENCIALES -->
                                    <div class="bg-blue-50 rounded-lg border-2 border-blue-300 p-4">
                                        <h5 class="text-blue-900 font-bold text-sm mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Enviar Credenciales
                                        </h5>
                                        <form action="{{ route('colaborador.resoluciones.enviar-credenciales', $persona->id_persona_resolucion_datos) }}" 
                                              method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="text-xs text-gray-700 font-semibold block mb-1.5">
                                                    Correo de destino:
                                                </label>
                                                <input type="email" 
                                                       name="correo" 
                                                       required 
                                                       placeholder="ejemplo@correo.com"
                                                       value="{{ $usuarioSistema->cliente->persona->correo ?? '' }}"
                                                       class="w-full px-3 py-2 text-sm border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                            </div>
                                            
                                            @if($tieneUsuario)
                                            <div class="mb-3 bg-orange-50 border border-orange-300 rounded-lg p-2">
                                                <p class="text-xs text-orange-800 font-semibold flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Se generará nueva contraseña
                                                </p>
                                                <p class="text-xs text-orange-700 mt-1">
                                                    El usuario ya existe. Se actualizará su contraseña.
                                                </p>
                                            </div>
                                            @else
                                            <div class="mb-3 bg-blue-100 border border-blue-300 rounded-lg p-2">
                                                <p class="text-xs text-blue-800 font-semibold flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                    </svg>
                                                    Se creará cuenta nueva
                                                </p>
                                                <p class="text-xs text-blue-700 mt-1">
                                                    Se generará un correo del sistema automáticamente.
                                                </p>
                                            </div>
                                            @endif
                                            
                                            <button type="submit" 
                                                    class="w-full px-4 py-2.5 {{ $tieneUsuario ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                                {{ $tieneUsuario ? 'Reenviar Credenciales' : 'Enviar Credenciales' }}
                                            </button>
                                        </form>
                                    </div>

                                    <!-- BOTÓN DE ASIGNAR RESOLUCIÓN AL CLIENTE -->
                                    @if($tieneUsuario && $resolucion->archivo_firmado)
                                    <div class="bg-purple-50 rounded-lg border-2 border-purple-300 p-4">
                                        <h5 class="text-purple-900 font-bold text-sm mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            Asignar Resolución
                                        </h5>
                                        
                                        @if($persona->asignado_a_cliente ?? false)
                                        <div class="bg-green-100 border border-green-300 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-green-800 font-semibold flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                ✅ Resolución ya asignada
                                            </p>
                                            @if($persona->fecha_asignacion)
                                            <p class="text-xs text-green-700 mt-1 ml-7">
                                                Asignado el {{ \Carbon\Carbon::parse($persona->fecha_asignacion)->format('d/m/Y H:i') }}
                                            </p>
                                            @endif
                                            <p class="text-xs text-green-600 mt-2 ml-7">
                                                El cliente puede ver esta resolución en "Mis Resoluciones"
                                            </p>
                                        </div>
                                        @else
                                        <div class="bg-purple-100 border border-purple-300 rounded-lg p-3 mb-3">
                                            <p class="text-xs text-purple-800">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Al asignar esta resolución, el cliente podrá verla en su módulo "Mis Resoluciones".
                                            </p>
                                        </div>
                                        @endif
                                        
                                        <form action="{{ route('colaborador.resoluciones.asignar-cliente', [$resolucion, $persona->id_persona_resolucion_datos]) }}" 
                                              method="POST"
                                              onsubmit="return confirm('¿Confirmar asignación de esta resolución al cliente?\n\n{{ $persona->nombres }} {{ $persona->apellido_paterno }}\n\nPodrá verla en su módulo Mis Resoluciones.')">
                                            @csrf
                                            
                                            <button type="submit" 
                                                    @if($persona->asignado_a_cliente ?? false) disabled @endif
                                                    class="w-full px-4 py-2.5 {{ ($persona->asignado_a_cliente ?? false) ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700' }} text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                                {{ ($persona->asignado_a_cliente ?? false) ? '✓ Ya Asignada' : 'Asignar al Cliente' }}
                                            </button>
                                        </form>
                                    </div>
                                    @elseif(!$tieneUsuario)
                                    <div class="bg-gray-100 border-2 border-gray-300 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 text-center flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            <span class="font-medium">Envía credenciales primero para poder asignar la resolución</span>
                                        </p>
                                    </div>
                                    @elseif(!$resolucion->archivo_firmado)
                                    <div class="bg-gray-100 border-2 border-gray-300 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 text-center flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span class="font-medium">La resolución debe estar firmada antes de asignarla</span>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="bg-blue-50 px-4 py-2 rounded-b-lg border-t-2 border-blue-300">
                            <p class="text-xs text-blue-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <strong>Nota:</strong> Primero envía las credenciales, luego asigna la resolución para que el cliente pueda verla en su panel.
                            </p>
                        </div>
                    </div>
                    @endif  <!-- Cierre de personasExternas -->
                </div>      <!-- Cierre de p-4 bg-gray-50 -->
            </div>          <!-- Cierre de border-2 border-purple-200 -->
            @endif          <!-- Cierre de if($totalPersonas > 0) -->
        </div>              <!-- Cierre de p-6 -->
    </div>                  <!-- Cierre de bg-white rounded-lg shadow-lg -->

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

    <!-- Botón de Asignar Resolución (solo si tiene usuario y resolución está firmada) -->
    @if($tieneUsuario && $resolucion->archivo_firmado)
    <div class="mt-3 pt-3 border-t-2 border-blue-200">
        <form action="{{ route('colaborador.resoluciones.asignar-cliente', [$resolucion, $persona->id_persona_resolucion_datos]) }}" 
              method="POST"
              onsubmit="return confirm('¿Confirmar asignación de esta resolución al cliente?\n\nPodrá verla en su módulo Mis Resoluciones.')">
            @csrf
            
            @if($persona->asignado_a_cliente ?? false)
            <div class="bg-green-100 border border-green-300 rounded-lg p-2 mb-2">
                <p class="text-xs text-green-800 font-semibold flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Ya asignado
                </p>
                @if($persona->fecha_asignacion)
                <p class="text-xs text-green-700 mt-1">
                    Asignado el {{ \Carbon\Carbon::parse($persona->fecha_asignacion)->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
            @endif
            
            <button type="submit" 
                    @if($persona->asignado_a_cliente ?? false) disabled @endif
                    class="w-full px-4 py-2 {{ ($persona->asignado_a_cliente ?? false) ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700' }} text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                {{ ($persona->asignado_a_cliente ?? false) ? 'Resolución Asignada' : 'Asignar Resolución al Cliente' }}
            </button>
        </form>
    </div>
    @elseif(!$tieneUsuario)
    <div class="mt-3 pt-3 border-t-2 border-blue-200">
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-2">
            <p class="text-xs text-gray-600 text-center">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Envía credenciales primero para poder asignar la resolución
            </p>
        </div>
    </div>
    @elseif(!$resolucion->archivo_firmado)
    <div class="mt-3 pt-3 border-t-2 border-blue-200">
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-2">
            <p class="text-xs text-gray-600 text-center">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                La resolución debe estar firmada antes de asignarla
            </p>
        </div>
    </div>
    @endif
</div>
@endsection