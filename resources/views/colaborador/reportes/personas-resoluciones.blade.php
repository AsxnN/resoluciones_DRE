{{-- filepath: resources/views/colaborador/reportes/personas-resoluciones.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Reporte: Personas con Más Resoluciones')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Personas con Más Resoluciones
        </h1>
        <p class="text-gray-600 mt-1">Análisis de participación en resoluciones por persona</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Personas Activas</p>
                    <p class="text-3xl font-bold mt-1">{{ $estadisticas['total_personas_activas'] }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Con Resoluciones</p>
                    <p class="text-3xl font-bold mt-1">{{ $estadisticas['personas_con_resoluciones'] }}</p>
                </div>
                <svg class="w-12 h-12 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Resoluciones</p>
                    <p class="text-3xl font-bold mt-1">{{ $estadisticas['total_resoluciones'] }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Promedio x Persona</p>
                    <p class="text-3xl font-bold mt-1">{{ $estadisticas['promedio_resoluciones_por_persona'] }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.reportes.personas-resoluciones') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Límite -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mostrar Top</label>
                    <select name="limite" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="10" {{ $limite == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ $limite == 20 ? 'selected' : '' }}>Top 20</option>
                        <option value="50" {{ $limite == 50 ? 'selected' : '' }}>Top 50</option>
                        <option value="100" {{ $limite == 100 ? 'selected' : '' }}>Top 100</option>
                    </select>
                </div>

                <!-- Tipo de Relación -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relación</label>
                    <select name="tipo_relacion" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Todas</option>
                        <option value="involucrado" {{ $tipoRelacion == 'involucrado' ? 'selected' : '' }}>Involucrado</option>
                        <option value="notificado" {{ $tipoRelacion == 'notificado' ? 'selected' : '' }}>Notificado</option>
                        <option value="firmante" {{ $tipoRelacion == 'firmante' ? 'selected' : '' }}>Firmante</option>
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                    <input type="date" name="fecha_desde" value="{{ $fechaDesde }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Fecha Hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Botones -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition">
                        Filtrar
                    </button>
                    <a href="{{ route('colaborador.reportes.personas-resoluciones') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-md hover:shadow-lg transition">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- Botón Exportar -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <form method="GET" action="{{ route('colaborador.reportes.personas-resoluciones.exportar') }}">
                <input type="hidden" name="fecha_desde" value="{{ $fechaDesde }}">
                <input type="hidden" name="fecha_hasta" value="{{ $fechaHasta }}">
                <input type="hidden" name="tipo_relacion" value="{{ $tipoRelacion }}">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar a Excel (CSV)
                </button>
            </form>
        </div>
    </div>

    <!-- Gráfica -->
    <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Personas</h3>
        <canvas id="chartPersonas" height="80"></canvas>
    </div>

    <!-- Tabla de Resultados -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Resoluciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($personas as $index => $persona)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} font-bold">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $persona->nombre_completo }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $persona->correo ?? 'Sin correo' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $persona->whatsapp ?? 'Sin WhatsApp' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-semibold bg-purple-100 text-purple-800 rounded-full">
                                {{ $persona->total_resoluciones }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">No se encontraron resultados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartPersonas');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($datosGrafica->pluck('nombre')) !!},
        datasets: [{
            label: 'Resoluciones',
            data: {!! json_encode($datosGrafica->pluck('total')) !!},
            backgroundColor: 'rgba(147, 51, 234, 0.8)',
            borderColor: 'rgba(147, 51, 234, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection