{{-- filepath: resources/views/admin/auditoria/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detalle de Auditoría #' . $auditoria->id_auditoria)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header con Breadcrumb -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.auditoria.index') }}" class="ml-1 text-gray-600 hover:text-gray-900">
                            Auditoría
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Detalle #{{ $auditoria->id_auditoria }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔍 Detalle de Auditoría</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Información completa del registro #{{ $auditoria->id_auditoria }}
                </p>
            </div>
            <a href="{{ route('admin.auditoria.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring ring-gray-300 transition">
                ← Volver
            </a>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <!-- Header del Card -->
        <div class="px-6 py-4 bg-gradient-to-r from-{{ $auditoria->color_nivel }}-500 to-{{ $auditoria->color_nivel }}-600 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-4xl">{{ $auditoria->icono_nivel }}</span>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $auditoria->accion_formateada }}</h2>
                        <p class="text-sm text-white opacity-90">{{ $auditoria->descripcion }}</p>
                    </div>
                </div>
                <span class="px-4 py-2 bg-white bg-opacity-20 text-white text-xs font-semibold rounded-full">
                    {{ ucfirst($auditoria->nivel) }}
                </span>
            </div>
        </div>

        <!-- Detalles Principales -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- ID y Fecha -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ID de Registro</label>
                        <p class="text-lg font-semibold text-gray-900">#{{ $auditoria->id_auditoria }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fecha y Hora</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $auditoria->fecha_hora->format('d/m/Y H:i:s') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            ({{ $auditoria->fecha_hora->diffForHumans() }})
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tipo de Evento</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ ucfirst($auditoria->tipo) }}
                        </span>
                    </div>
                </div>

                <!-- Usuario y Módulo -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Usuario</label>
                        @if($auditoria->usuario)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($auditoria->usuario->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $auditoria->usuario->name }}</p>
                                <p class="text-sm text-gray-500">{{ $auditoria->usuario->email }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500 italic">Sistema Automático</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Módulo</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $auditoria->modulo ? ucfirst($auditoria->modulo) : 'N/A' }}
                        </p>
                    </div>

                    @if($auditoria->tabla_afectada)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tabla Afectada</label>
                        <p class="text-sm text-gray-700 font-mono bg-gray-100 px-3 py-2 rounded">
                            {{ $auditoria->tabla_afectada }}
                            @if($auditoria->id_registro)
                                <span class="text-gray-500">→ ID: {{ $auditoria->id_registro }}</span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Información Técnica -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🔧 Información Técnica</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Dirección IP</label>
                    <p class="text-sm text-gray-700 font-mono bg-gray-100 px-3 py-2 rounded">
                        {{ $auditoria->ip_address ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Método HTTP</label>
                    <p class="text-sm text-gray-700 font-mono bg-gray-100 px-3 py-2 rounded">
                        {{ $auditoria->metodo_http ?? 'N/A' }}
                    </p>
                </div>

                @if($auditoria->url)
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">URL</label>
                    <p class="text-sm text-gray-700 font-mono bg-gray-100 px-3 py-2 rounded break-all">
                        {{ $auditoria->url }}
                    </p>
                </div>
                @endif

                @if($auditoria->user_agent)
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">User Agent</label>
                    <p class="text-xs text-gray-600 bg-gray-100 px-3 py-2 rounded break-all">
                        {{ $auditoria->user_agent }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Datos Anteriores y Nuevos -->
    @if($auditoria->datos_anteriores || $auditoria->datos_nuevos)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Datos Anteriores -->
        @if($auditoria->datos_anteriores)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h3 class="text-lg font-semibold text-red-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Datos Anteriores
                </h3>
            </div>
            <div class="p-6">
                <pre class="text-xs bg-gray-100 p-4 rounded overflow-x-auto">{{ json_encode($auditoria->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        <!-- Datos Nuevos -->
        @if($auditoria->datos_nuevos)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                <h3 class="text-lg font-semibold text-green-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Datos Nuevos
                </h3>
            </div>
            <div class="p-6">
                <pre class="text-xs bg-gray-100 p-4 rounded overflow-x-auto">{{ json_encode($auditoria->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection