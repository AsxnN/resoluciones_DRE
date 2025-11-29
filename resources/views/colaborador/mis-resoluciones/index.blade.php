{{-- filepath: resources/views/colaborador/mis-resoluciones/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Mis Resoluciones')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📑 Mis Resoluciones</h1>
                <p class="text-gray-600 mt-1">Resoluciones creadas por mí</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Filtro rápido -->
                <div class="flex items-center gap-2 bg-white rounded-lg shadow px-4 py-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Avanzados -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Número, asunto o persona..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado->id_estado }}" {{ request('estado') == $estado->id_estado ? 'selected' : '' }}>
                        {{ $estado->nombre_estado }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id_tipo_resolucion }}" {{ request('tipo') == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
                        {{ $tipo->nombre_tipo_resolucion }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                <select name="periodo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todo el tiempo</option>
                    <option value="hoy" {{ request('periodo') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                    <option value="semana" {{ request('periodo') == 'semana' ? 'selected' : '' }}>Esta semana</option>
                    <option value="mes" {{ request('periodo') == 'mes' ? 'selected' : '' }}>Este mes</option>
                    <option value="trimestre" {{ request('periodo') == 'trimestre' ? 'selected' : '' }}>Este trimestre</option>
                    <option value="año" {{ request('periodo') == 'año' ? 'selected' : '' }}>Este año</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                    🔍 Filtrar
                </button>
                <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                    🔄 Limpiar
                </a>
                <button type="button" 
                        onclick="window.print()" 
                        class="ml-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                    🖨️ Imprimir
                </button>
            </div>
        </form>
    </div>

    <!-- Estadísticas Personales -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-medium">Total</p>
                    <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-400">
                <p class="text-xs text-blue-100">Resoluciones creadas</p>
            </div>
        </div>

        <!-- Borradores -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Borradores</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['borradores'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pendientes</p>
                </div>
            </div>
        </div>

        <!-- En Revisión -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">En Revisión</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['revision'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Proceso</p>
                </div>
            </div>
        </div>

        <!-- Firmadas -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Firmadas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['firmadas'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Completadas</p>
                </div>
            </div>
        </div>

        <!-- Este Mes -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm font-medium">Este Mes</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['mes_actual'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vista de Tarjetas (Cards) -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">📋 Mis Resoluciones Recientes</h2>
        <div class="flex gap-2">
            <button onclick="toggleView('cards')" id="btn-cards" 
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                🎴 Cards
            </button>
            <button onclick="toggleView('table')" id="btn-table" 
                    class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
                📊 Tabla
            </button>
        </div>
    </div>

    <!-- Vista Cards -->
    <div id="cards-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($resoluciones as $resolucion)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-t-4 
            {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'border-green-500' : 
               ($resolucion->estado->nombre_estado === 'Borrador' ? 'border-yellow-500' : 'border-blue-500') }}">
            
            <!-- Header -->
            <div class="p-6 bg-gradient-to-r 
                {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'from-green-500 to-green-600' : 
                   ($resolucion->estado->nombre_estado === 'Borrador' ? 'from-yellow-500 to-yellow-600' : 'from-blue-500 to-blue-600') }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-3 py-1 bg-white bg-opacity-30 backdrop-blur-sm rounded-full text-white text-xs font-bold">
                        {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                    </span>
                    <span class="px-3 py-1 bg-white text-gray-900 rounded-full text-xs font-bold">
                        {{ $resolucion->estado->nombre_estado }}
                    </span>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">{{ $resolucion->num_resolucion }}</h3>
                <p class="text-white text-opacity-90 text-sm">
                    📅 {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                </p>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Asunto -->
                <div class="mb-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Asunto</h4>
                    <p class="text-sm text-gray-900 line-clamp-2">{{ $resolucion->asunto_resolucion }}</p>
                </div>

                <!-- Persona -->
                @if($resolucion->persona)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        @if($resolucion->persona->foto_persona)
                            <img src="{{ asset('storage/' . $resolucion->persona->foto_persona) }}" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-300"
                                 alt="{{ $resolucion->persona->nombres_persona }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($resolucion->persona->nombres_persona, 0, 2) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $resolucion->persona->nombres_persona }} {{ $resolucion->persona->apellido_paterno_persona }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $resolucion->persona->cargo_persona }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Detalles -->
                <div class="grid grid-cols-2 gap-3 text-xs mb-4">
                    <div class="bg-blue-50 p-2 rounded">
                        <p class="text-blue-800 font-semibold">Dirección</p>
                        <p class="text-blue-600">{{ Str::limit($resolucion->direccion->nombre_direccion ?? 'N/A', 15) }}</p>
                    </div>
                    <div class="bg-purple-50 p-2 rounded">
                        <p class="text-purple-800 font-semibold">Dependencia</p>
                        <p class="text-purple-600">{{ Str::limit($resolucion->dependencia->nombre_dependencia ?? 'N/A', 15) }}</p>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="text-xs text-gray-500 space-y-1 mb-4">
                    <p>⏰ Creado: {{ $resolucion->created_at->format('d/m/Y H:i') }}</p>
                    @if($resolucion->fecha_firma)
                    <p>✅ Firmado: {{ $resolucion->fecha_firma->format('d/m/Y H:i') }}</p>
                    @endif
                </div>

                <!-- Acciones -->
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                       class="flex-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    
                    @can('editar_resoluciones')
                    <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" 
                       class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </a>
                    @endcan

                    @if($resolucion->archivo_firmado)
                    <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                       target="_blank"
                       class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition text-sm font-medium">
                        📥
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes resoluciones</h3>
            <p class="text-gray-500 mb-4">Aún no has creado ninguna resolución</p>
            @can('crear_resoluciones')
            <a href="{{ route('colaborador.resoluciones.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                ➕ Crear Primera Resolución
            </a>
            @endcan
        </div>
        @endforelse
    </div>

    <!-- Vista Tabla (oculta por defecto) -->
    <div id="table-view" class="hidden bg-white rounded-lg shadow overflow-hidden mb-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Resolución</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($resoluciones as $resolucion)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $resolucion->num_resolucion }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($resolucion->asunto_resolucion, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($resolucion->persona)
                        <div class="text-sm text-gray-900">{{ $resolucion->persona->nombres_persona }} {{ $resolucion->persona->apellido_paterno_persona }}</div>
                        @else
                        <span class="text-xs text-gray-400">Sin persona</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-100 text-green-800' : 
                               ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $resolucion->estado->nombre_estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" class="text-blue-600 hover:text-blue-900">👁️</a>
                            @can('editar_resoluciones')
                            <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}" class="text-yellow-600 hover:text-yellow-900">✏️</a>
                            @endcan
                            @if($resolucion->archivo_firmado)
                            <a href="{{ Storage::url($resolucion->archivo_firmado) }}" target="_blank" class="text-green-600 hover:text-green-900">📥</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($resoluciones->hasPages())
    <div class="bg-white rounded-lg shadow p-4">
        {{ $resoluciones->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleView(view) {
    const cardsView = document.getElementById('cards-view');
    const tableView = document.getElementById('table-view');
    const btnCards = document.getElementById('btn-cards');
    const btnTable = document.getElementById('btn-table');

    if (view === 'cards') {
        cardsView.classList.remove('hidden');
        tableView.classList.add('hidden');
        btnCards.classList.add('bg-blue-600', 'text-white');
        btnCards.classList.remove('bg-gray-200', 'text-gray-700');
        btnTable.classList.add('bg-gray-200', 'text-gray-700');
        btnTable.classList.remove('bg-blue-600', 'text-white');
    } else {
        cardsView.classList.add('hidden');
        tableView.classList.remove('hidden');
        btnTable.classList.add('bg-blue-600', 'text-white');
        btnTable.classList.remove('bg-gray-200', 'text-gray-700');
        btnCards.classList.add('bg-gray-200', 'text-gray-700');
        btnCards.classList.remove('bg-blue-600', 'text-white');
    }
}
</script>
@endpush
@endsection