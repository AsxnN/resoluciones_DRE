{{-- filepath: resources/views/colaborador/registro-firma-entrega/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Registro de Firmas para Entrega')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Registro de Firmas para Entrega
        </h1>
        <p class="text-gray-600 mt-1">Gestión de firmas digitales para entrega a personas externas</p>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nº resolución, persona, DNI..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="firmado" {{ request('estado') == 'firmado' ? 'selected' : '' }}>Firmado</option>
                    <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" 
                       name="fecha_desde" 
                       value="{{ request('fecha_desde') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" 
                       name="fecha_hasta" 
                       value="{{ request('fecha_hasta') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('colaborador.registro-firma-entrega.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de registros -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($registros->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolución</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinatario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($registros as $registro)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $registro->codigo_verificacion }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $registro->resolucion->num_resolucion }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $registro->resolucion->tipoResolucion->nombre_tipo_resolucion }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $registro->personaExterna->nombres }} {{ $registro->personaExterna->apellido_paterno }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $registro->personaExterna->tipo_documento }}: {{ $registro->personaExterna->num_documento }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $registro->fecha_solicitud->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $registro->fecha_solicitud->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $color = $registro->color_estado;
                                $bgClass = match($color) {
                                    'green' => 'bg-green-100 text-green-800',
                                    'blue' => 'bg-blue-100 text-blue-800',
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bgClass }}">
                                {{ $registro->estado_visual }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('colaborador.registro-firma-entrega.show', $registro) }}" 
                               class="text-indigo-600 hover:text-indigo-900 font-medium">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $registros->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No hay registros de firma</h3>
            <p class="text-gray-500">No se encontraron registros con los filtros aplicados.</p>
        </div>
        @endif
    </div>
</div>
@endsection