{{-- filepath: resources/views/admin/modulos/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestión de Módulos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📦 Gestión de Módulos</h1>
            <p class="text-gray-600 mt-1">Administración de módulos del sistema</p>
        </div>
        @can('crear_modulos')
        <button onclick="openModal('createModuloModal')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Módulo
        </button>
        @endcan
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Módulos</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $modulos->count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Activos</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['activos'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">Total Permisos</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_permisos'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Promedio Permisos</p>
                    <p class="text-2xl font-bold text-orange-600">
                        {{ $modulos->count() > 0 ? round($stats['total_permisos'] / $modulos->count(), 1) : 0 }}
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modulos as $modulo)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <!-- Header del Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    @if($modulo->activo)
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">✓ Activo</span>
                    @else
                        <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">✗ Inactivo</span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-white">{{ $modulo->nombre_modulo }}</h3>
                <p class="text-blue-100 text-sm mt-2">{{ Str::limit($modulo->descripcion_modulo, 80) }}</p>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <!-- Stats del Módulo -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $modulo->permisos->count() }}</p>
                        <p class="text-xs text-purple-800">Permisos</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $modulo->permisos->where('activo', true)->count() }}</p>
                        <p class="text-xs text-green-800">Activos</p>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Orden:</span>
                        <span class="font-semibold text-gray-900">{{ $modulo->orden }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Icono:</span>
                        <span class="font-semibold text-gray-900">{{ $modulo->icono ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Creado:</span>
                        <span class="font-semibold text-gray-900">{{ $modulo->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.modulos.show', $modulo) }}" 
                       class="flex-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center rounded-lg transition text-sm font-medium">
                        👁️ Ver
                    </a>
                    
                    @can('editar_modulos')
                    <button onclick="editModulo({{ $modulo->id_modulo }})" 
                            class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </button>
                    @endcan

                    @can('eliminar_modulos')
                    <form method="POST" 
                          action="{{ route('admin.modulos.destroy', $modulo) }}" 
                          onsubmit="return confirm('¿Está seguro de eliminar este módulo? Se eliminarán todos sus permisos.')"
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
        @endforeach
    </div>
</div>

<!-- Modal Crear Módulo -->
<div id="createModuloModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">➕ Crear Nuevo Módulo</h3>
            <button onclick="closeModal('createModuloModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.modulos.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre_modulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Módulo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_modulo" 
                           name="nombre_modulo" 
                           required
                           placeholder="Ej: Gestión de Resoluciones"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_modulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion_modulo" 
                              name="descripcion_modulo" 
                              rows="3"
                              placeholder="Breve descripción del módulo..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Icono -->
                    <div>
                        <label for="icono" class="block text-sm font-medium text-gray-700 mb-2">
                            Icono
                        </label>
                        <input type="text" 
                               id="icono" 
                               name="icono" 
                               placeholder="Ej: 📋"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Orden -->
                    <div>
                        <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="orden" 
                               name="orden" 
                               required
                               min="1"
                               value="1"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Activo -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="activo" class="ml-2 text-sm text-gray-700">Módulo Activo</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" 
                        onclick="closeModal('createModuloModal')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ✓ Crear Módulo
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

function editModulo(moduloId) {
    // Implementar edición
    alert('Editar módulo ID: ' + moduloId);
}
</script>
@endpush
@endsection