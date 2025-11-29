{{-- filepath: resources/views/colaborador/areas/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Áreas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🏢 Gestión de Áreas</h1>
            <p class="text-gray-600 mt-1">Administración de áreas de la DRE Huánuco</p>
        </div>
        @can('crear_areas')
        <button onclick="openModal('createAreaModal')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Área
        </button>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.areas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre del área..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('colaborador.areas.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄 Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Áreas</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $areas->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Activas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['activas'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Con Personal</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['con_personal'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Áreas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($areas as $area)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-white">{{ $area->nombre_area }}</h3>
                    @if($area->activo)
                        <span class="px-2 py-1 bg-green-500 text-white rounded text-xs font-semibold">✓ Activa</span>
                    @else
                        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs font-semibold">✗ Inactiva</span>
                    @endif
                </div>
                @if($area->codigo_area)
                <p class="text-blue-100 text-sm">Código: {{ $area->codigo_area }}</p>
                @endif
            </div>

            <div class="p-6">
                <!-- Descripción -->
                @if($area->descripcion_area)
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($area->descripcion_area, 100) }}</p>
                @endif

                <!-- Stats del Área -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="text-center p-2 bg-blue-50 rounded">
                        <p class="text-lg font-bold text-blue-600">{{ $area->personas_count ?? 0 }}</p>
                        <p class="text-xs text-blue-800">Personal</p>
                    </div>
                    <div class="text-center p-2 bg-green-50 rounded">
                        <p class="text-lg font-bold text-green-600">{{ $area->resoluciones_count ?? 0 }}</p>
                        <p class="text-xs text-green-800">Resoluciones</p>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="space-y-2 text-xs text-gray-600 mb-4">
                    @if($area->responsable)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $area->responsable }}</span>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Creada: {{ $area->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex gap-2">
                    <a href="{{ route('colaborador.areas.show', $area) }}" 
                       class="flex-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    
                    @can('editar_areas')
                    <button onclick="editArea({{ $area->id_area }})" 
                            class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </button>
                    @endcan

                    @can('eliminar_areas')
                    <form method="POST" 
                          action="{{ route('colaborador.areas.destroy', $area) }}" 
                          onsubmit="return confirm('¿Está seguro de eliminar esta área?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition text-sm font-medium">
                            🗑️
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay áreas</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva área</p>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($areas->hasPages())
    <div class="mt-6">
        {{ $areas->links() }}
    </div>
    @endif
</div>

<!-- Modal Crear Área -->
<div id="createAreaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">➕ Nueva Área</h3>
            <button onclick="closeModal('createAreaModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('colaborador.areas.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Área <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_area" 
                           name="nombre_area" 
                           required
                           placeholder="Ej: Dirección de Gestión Pedagógica"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Código -->
                <div>
                    <label for="codigo_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Código del Área
                    </label>
                    <input type="text" 
                           id="codigo_area" 
                           name="codigo_area" 
                           placeholder="Ej: DGP-001"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion_area" 
                              name="descripcion_area" 
                              rows="3"
                              placeholder="Breve descripción del área..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Responsable -->
                <div>
                    <label for="responsable" class="block text-sm font-medium text-gray-700 mb-2">
                        Responsable
                    </label>
                    <input type="text" 
                           id="responsable" 
                           name="responsable" 
                           placeholder="Nombre del responsable del área"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Activo -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="activo" class="ml-2 text-sm text-gray-700">Área Activa</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" 
                        onclick="closeModal('createAreaModal')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ✓ Crear Área
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editArea(areaId) {
    window.location.href = `/colaborador/areas/${areaId}/edit`;
}
</script>
@endpush
@endsection