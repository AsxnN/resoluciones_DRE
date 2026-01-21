{{-- filepath: resources/views/colaborador/resoluciones-firmadas/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Registro de Firmas para Entrega')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Registro de Firmas para Entrega
            </h1>
            <p class="text-gray-600 mt-1">Resoluciones con registros de firma para personas externas</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('colaborador.registro-firma-entrega.index') }}" 
               class="inline-flex items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Ver Todos los Registros
            </a>
            
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="inline-flex items-center px-5 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Resoluciones
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.resoluciones-firmadas.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Número de resolución, asunto..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>

                <!-- Fecha Desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <input type="date" 
                           name="fecha_desde" 
                           value="{{ request('fecha_desde') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>

                <!-- Fecha Hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <input type="date" 
                           name="fecha_hasta" 
                           value="{{ request('fecha_hasta') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </button>
                <a href="{{ route('colaborador.resoluciones-firmadas.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Registros</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalRegistros }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Este Mes</p>
                    <p class="text-3xl font-bold mt-2">{{ $registrosEsteMes }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Esta Semana</p>
                    <p class="text-3xl font-bold mt-2">{{ $registrosEstaSemana }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Hoy</p>
                    <p class="text-3xl font-bold mt-2">{{ $registrosHoy }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de resoluciones con registros -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolución</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Registros</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asunto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($resoluciones as $resolucion)
                    <tr class="hover:bg-purple-50 transition cursor-pointer" onclick="window.location='{{ route('colaborador.resoluciones-firmadas.historial-firmas', $resolucion) }}'">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-purple-600">{{ $resolucion->num_resolucion }}</div>
                                    <div class="text-xs text-gray-500">{{ $resolucion->fecha_resolucion ? $resolucion->fecha_resolucion->format('d/m/Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $resolucion->fecha_creacion ? $resolucion->fecha_creacion->format('d/m/Y') : 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $resolucion->fecha_creacion ? $resolucion->fecha_creacion->diffForHumans() : '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $resolucion->estado->nombre_estado === 'Aprobado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $resolucion->estado->nombre_estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $resolucion->estado->nombre_estado === 'Rechazado' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $resolucion->estado->nombre_estado === 'Borrador' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $resolucion->estado->nombre_estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold">
                                    {{ $resolucion->registros_firma_entrega_count }} Total
                                </span>
                                <div class="flex gap-1 text-xs">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">
                                        {{ $resolucion->registros_firmados_count }} ✓
                                    </span>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">
                                        {{ $resolucion->registros_entregados_count }} 📦
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($resolucion->asunto_resolucion, 60) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('colaborador.resoluciones-firmadas.historial-firmas', $resolucion) }}" 
                                   class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-100 transition" 
                                   title="Ver Historial de Firmas">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition" 
                                   title="Ver Resolución">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('colaborador.registro-firma-entrega.create', $resolucion) }}" 
                                   class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition" 
                                   title="Nueva Firma">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">No hay resoluciones con registros de firma</p>
                                <p class="text-sm mt-1">Crea un registro de firma para entrega desde el listado de resoluciones</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($resoluciones->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $resoluciones->links() }}
        </div>
        @endif
    </div>
</div>
@endsection