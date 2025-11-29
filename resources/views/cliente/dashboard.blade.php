{{-- filepath: resources/views/cliente/dashboard.blade.php --}}
@extends('layouts.cliente')

@section('title', 'Mi Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header de Bienvenida -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">👋 Bienvenido, {{ Auth::user()->name }}</h1>
                    <p class="text-blue-100 mt-2">Panel de consulta de resoluciones - DRE Huánuco</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Resoluciones -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Mis Resoluciones</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_resoluciones'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500">Total de resoluciones asociadas</p>
        </div>

        <!-- Última Consulta -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Última Consulta</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ $stats['ultima_consulta'] ? $stats['ultima_consulta']->diffForHumans() : 'N/A' }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500">Último acceso al sistema</p>
        </div>

        <!-- Búsquedas Realizadas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Búsquedas</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['busquedas'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500">Total de consultas realizadas</p>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Buscar Resoluciones -->
        <a href="{{ route('cliente.busqueda.index') }}" 
           class="block bg-white rounded-lg shadow-lg hover:shadow-xl transition p-6">
            <div class="flex items-center">
                <div class="p-4 bg-blue-100 rounded-lg">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-semibold text-gray-900">🔍 Buscar Resoluciones</h3>
                    <p class="text-gray-600 mt-1">Busca por número, DNI o nombre</p>
                </div>
            </div>
        </a>

        <!-- Mis Resoluciones -->
        <a href="{{ route('cliente.resoluciones.index') }}" 
           class="block bg-white rounded-lg shadow-lg hover:shadow-xl transition p-6">
            <div class="flex items-center">
                <div class="p-4 bg-green-100 rounded-lg">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-semibold text-gray-900">📄 Mis Resoluciones</h3>
                    <p class="text-gray-600 mt-1">Ver resoluciones asociadas a mi cuenta</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Resoluciones Recientes -->
    @if(isset($resolucionesRecientes) && $resolucionesRecientes->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Resoluciones Recientes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($resolucionesRecientes as $resolucion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $resolucion->num_resolucion }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($resolucion->asunto_resolucion, 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $resolucion->estado->nombre_estado === 'Publicada' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $resolucion->estado->nombre_estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('cliente.resoluciones.show', $resolucion) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Ver detalles →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('cliente.resoluciones.index') }}" 
               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver todas las resoluciones →
            </a>
        </div>
    </div>
    @endif

    <!-- Información de Ayuda -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-blue-900">💡 ¿Necesitas ayuda?</h3>
                <p class="text-blue-800 mt-2">
                    Puedes buscar resoluciones por número, DNI o nombre. También puedes descargar las resoluciones firmadas digitalmente.
                </p>
                <ul class="mt-3 space-y-2 text-sm text-blue-700">
                    <li>• Utiliza el buscador para encontrar resoluciones específicas</li>
                    <li>• Descarga tus resoluciones en formato PDF</li>
                    <li>• Verifica la autenticidad con el código QR</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection