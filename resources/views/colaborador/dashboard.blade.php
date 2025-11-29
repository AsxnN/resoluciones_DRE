{{-- filepath: resources/views/colaborador/dashboard.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Panel de Colaborador')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header de Bienvenida -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">👋 Bienvenido, {{ Auth::user()->name }}</h1>
                    <p class="text-blue-100 mt-2">Panel de Colaborador - DRE Huánuco</p>
                    <div class="flex items-center mt-4 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Resoluciones Creadas -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Resoluciones Creadas</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['resoluciones_creadas'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600 font-semibold">+{{ $stats['resoluciones_mes'] ?? 0 }}</span> este mes
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('colaborador.resoluciones.index') }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                Ver todas →
            </a>
        </div>

        <!-- Firmas Pendientes -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Firmas Pendientes</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['firmas_pendientes'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Requieren atención</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('colaborador.firma.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center">
                Ver pendientes →
            </a>
        </div>

        <!-- Personas Registradas -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Personas Registradas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['personas_registradas'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">En el sistema</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('colaborador.personas.index') }}" class="text-xs text-green-600 hover:text-green-800 flex items-center">
                Ver listado →
            </a>
        </div>

        <!-- Áreas Activas -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Áreas Activas</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['areas_activas'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Disponibles</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('colaborador.areas.index') }}" class="text-xs text-purple-600 hover:text-purple-800 flex items-center">
                Ver áreas →
            </a>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">⚡ Accesos Rápidos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Nueva Resolución -->
            <a href="{{ route('colaborador.resoluciones.create') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Nueva Resolución</h4>
                        <p class="text-xs text-gray-600">Crear nueva resolución</p>
                    </div>
                </div>
            </a>

            <!-- Registrar Persona -->
            <a href="{{ route('colaborador.personas.create') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Registrar Persona</h4>
                        <p class="text-xs text-gray-600">Agregar nueva persona</p>
                    </div>
                </div>
            </a>

            <!-- Gestionar Áreas -->
            <a href="{{ route('colaborador.areas.index') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Gestionar Áreas</h4>
                        <p class="text-xs text-gray-600">Administrar áreas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Gráficos y Actividad -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Resoluciones Recientes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📄 Resoluciones Recientes</h3>
            <div class="space-y-4">
                @forelse($resoluciones_recientes ?? [] as $resolucion)
                <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900">{{ $resolucion->numero_resolucion }}</h4>
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($resolucion->asunto, 60) }}</p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $resolucion->fecha_emision->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <span class="ml-4 px-2 py-1 rounded text-xs font-medium
                        {{ $resolucion->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                           ($resolucion->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                           'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($resolucion->estado) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm">No hay resoluciones recientes</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Mi Actividad</h3>
            <div class="space-y-4">
                @forelse($actividades ?? [] as $actividad)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full 
                            {{ $actividad->tipo === 'crear' ? 'bg-green-100 text-green-600' : 
                               ($actividad->tipo === 'editar' ? 'bg-yellow-100 text-yellow-600' : 
                               'bg-blue-100 text-blue-600') }} 
                            flex items-center justify-center">
                            @if($actividad->tipo === 'crear')
                                ➕
                            @elseif($actividad->tipo === 'editar')
                                ✏️
                            @else
                                ✓
                            @endif
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-gray-900">{{ $actividad->descripcion }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $actividad->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <p class="text-sm">No hay actividad reciente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection