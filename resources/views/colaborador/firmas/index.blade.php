{{-- filepath: resources/views/colaborador/firma/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Firmas Digitales')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">✍️ Firmas Digitales</h1>
            <p class="text-gray-600 mt-1">Gestión de firmas electrónicas de resoluciones</p>
        </div>
        @can('crear_firmas')
        <a href="{{ route('colaborador.firma.firmar') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Firma
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.firma.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Número de resolución, firmante..."
                       class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado_firma" class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos</option>
                    <option value="Firmado" {{ request('estado_firma') === 'Firmado' ? 'selected' : '' }}>Firmado</option>
                    <option value="Pendiente" {{ request('estado_firma') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Rechazado" {{ request('estado_firma') === 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('colaborador.firma.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄 Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Firmas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Firmados</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['firmados'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pendientes'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Este Mes</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['mes_actual'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolución</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Firmante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Firma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validez</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($firmas as $firma)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xs">
                                        ✍️
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $firma->resolucion->num_resolucion }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($firma->resolucion->asunto_resolucion, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $firma->usuario->name }}</div>
                            <div class="text-sm text-gray-500">{{ $firma->usuario->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $firma->estado_firma === 'Firmado' ? 'bg-green-100 text-green-800' : 
                                   ($firma->estado_firma === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $firma->estado_firma }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $firma->fecha_firma ? $firma->fecha_firma->format('d/m/Y H:i') : 'Pendiente' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($firma->firma_valida)
                                <span class="text-green-600 text-xs font-semibold">✓ Válida</span>
                            @else
                                <span class="text-red-600 text-xs font-semibold">✗ No válida</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('colaborador.resoluciones.show', $firma->resolucion) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Ver Resolución">
                                    👁️
                                </a>

                                @if($firma->hash_firma)
                                <button onclick="copiarHash('{{ $firma->hash_firma }}')" 
                                        class="text-purple-600 hover:text-purple-900" title="Copiar Hash">
                                    📋
                                </button>
                                @endif

                                @can('eliminar_firmas')
                                <form method="POST" 
                                      action="{{ route('colaborador.firma.destroy', $firma) }}" 
                                      onsubmit="return confirm('¿Está seguro de eliminar esta firma?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2">No se encontraron firmas digitales</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        @if($firmas->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $firmas->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copiarHash(hash) {
    navigator.clipboard.writeText(hash).then(() => {
        alert('✅ Hash copiado al portapapeles');
    }).catch(err => {
        alert('❌ Error al copiar: ' + err);
    });
}
</script>
@endpush
@endsection