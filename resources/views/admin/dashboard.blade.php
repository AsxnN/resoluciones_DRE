{{-- filepath: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header de Bienvenida -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">👋 Bienvenido, {{ Auth::user()->name }}</h1>
                    <p class="text-red-100 mt-2">Panel de Administración - DRE Huánuco</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Resoluciones -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Total Resoluciones</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_resoluciones'] ?? 0 }}</p>
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
            <div class="mt-4 flex items-center text-xs text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span>Ver todas las resoluciones</span>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Usuarios Activos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['usuarios_activos'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        De {{ $stats['total_usuarios'] ?? 0 }} totales
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Gestionar usuarios</span>
            </div>
        </div>

        <!-- Firmas Pendientes -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Firmas Pendientes</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['firmas_pendientes'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Requieren atención
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Ver firmas pendientes</span>
            </div>
        </div>

        <!-- Actividad Hoy -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Actividad Hoy</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['actividad_hoy'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Acciones registradas
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Ver registro de auditoría</span>
            </div>
        </div>
    </div>

    <!-- Gráficos y Stats Secundarios -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Resoluciones por Estado -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Resoluciones por Estado</h3>
            <div class="space-y-4">
                @foreach($stats['por_estado'] ?? [] as $estado => $cantidad)
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ $estado }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $cantidad }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($cantidad / max(array_values($stats['por_estado']))) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Actividad Reciente</h3>
            <div class="space-y-4">
                @forelse($actividades_recientes ?? [] as $actividad)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full 
                            {{ $actividad->accion === 'crear' ? 'bg-green-100 text-green-600' : 
                               ($actividad->accion === 'editar' ? 'bg-yellow-100 text-yellow-600' : 
                               'bg-red-100 text-red-600') }} 
                            flex items-center justify-center">
                            @if($actividad->accion === 'crear')
                                ➕
                            @elseif($actividad->accion === 'editar')
                                ✏️
                            @else
                                🗑️
                            @endif
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-gray-900">{{ $actividad->descripcion }}</p>
                        <div class="flex items-center mt-1 text-xs text-gray-500">
                            <span>{{ $actividad->usuario->name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $actividad->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No hay actividad reciente</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">⚡ Accesos Rápidos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Gestión de Usuarios -->
            <a href="{{ route('admin.usuarios.index') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Usuarios</h4>
                        <p class="text-xs text-gray-600">Gestionar usuarios</p>
                    </div>
                </div>
            </a>

            <!-- Permisos -->
            <a href="{{ route('admin.permisos.index') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Permisos</h4>
                        <p class="text-xs text-gray-600">Control de accesos</p>
                    </div>
                </div>
            </a>

            <!-- Módulos -->
            <a href="{{ route('admin.modulos.index') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Módulos</h4>
                        <p class="text-xs text-gray-600">Administrar módulos</p>
                    </div>
                </div>
            </a>

            <!-- Auditoría -->
            <a href="{{ route('admin.auditoria.index') }}" 
               class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-900">Auditoría</h4>
                        <p class="text-xs text-gray-600">Registro de actividad</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Estadísticas por Tipo -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Usuarios por Tipo -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Usuarios por Tipo</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <span class="text-sm font-medium text-red-900">Administradores</span>
                    <span class="px-3 py-1 bg-red-200 text-red-800 rounded-full text-sm font-bold">
                        {{ $stats['usuarios_admin'] ?? 0 }}
                    </span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-medium text-blue-900">Colaboradores</span>
                    <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm font-bold">
                        {{ $stats['usuarios_colaborador'] ?? 0 }}
                    </span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-green-900">Clientes</span>
                    <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-sm font-bold">
                        {{ $stats['usuarios_cliente'] ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Resoluciones por Mes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📅 Últimos 6 Meses</h3>
            <div class="space-y-2">
                @foreach($stats['ultimos_meses'] ?? [] as $mes => $cantidad)
                <div class="flex items-center">
                    <span class="text-xs text-gray-600 w-20">{{ $mes }}</span>
                    <div class="flex-1 mx-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($cantidad / 100) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-900 w-8 text-right">{{ $cantidad }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sistema -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Estado del Sistema</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-green-900">Base de Datos</span>
                    </div>
                    <span class="text-xs text-green-700">Conectado</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-green-900">Almacenamiento</span>
                    </div>
                    <span class="text-xs text-green-700">{{ $stats['espacio_usado'] ?? '45%' }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-blue-900">Versión</span>
                    </div>
                    <span class="text-xs text-blue-700">{{ config('app.version', '1.0.0') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    @if(isset($alertas) && $alertas->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Alertas del Sistema</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($alertas as $alerta)
                            <li>{{ $alerta->mensaje }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection