{{-- filepath: resources/views/admin/permisos/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestión de Permisos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🔐 Gestión de Permisos</h1>
            <p class="text-gray-600 mt-1">Administración de permisos del sistema</p>
        </div>
        @can('crear_permisos')
        <button onclick="openModal('createPermisoModal')" 
                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Permiso
        </button>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('admin.permisos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre del permiso..."
                       class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Módulo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Módulo</label>
                <select name="modulo" class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todos</option>
                    @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id_modulo }}" {{ request('modulo') == $modulo->id_modulo ? 'selected' : '' }}>
                            {{ $modulo->nombre_modulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('admin.permisos.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
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
                    <p class="text-sm text-gray-600">Total Permisos</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">Módulos</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['modulos'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">Usuarios con Permisos</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['usuarios_con_permisos'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Permisos por Módulo -->
    @foreach($modulos as $modulo)
    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
        <!-- Header del Módulo -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h3 class="text-xl font-bold">{{ $modulo->nombre_modulo }}</h3>
                        <p class="text-purple-100 text-sm">{{ $modulo->descripcion_modulo }}</p>
                    </div>
                </div>
                <div class="text-white">
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                        {{ $modulo->permisos->count() }} Permisos
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabla de Permisos -->
        @if($modulo->permisos->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permiso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clave</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuarios</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($modulo->permisos as $permiso)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $permiso->nombre_permiso }}</div>
                            <div class="text-sm text-gray-500">{{ $permiso->descripcion_permiso }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="px-2 py-1 bg-gray-100 text-purple-600 rounded text-xs font-mono">
                            {{ $permiso->clave_permiso }}
                        </code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($permiso->activo)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✓ Activo</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">✗ Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ $permiso->usuarios->count() }} usuarios
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            @can('editar_permisos')
                            <button onclick="editPermiso({{ $permiso->id_permiso }})" 
                                    class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                ✏️
                            </button>
                            @endcan

                            @can('eliminar_permisos')
                            <form method="POST" 
                                  action="{{ route('admin.permisos.destroy', $permiso) }}" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este permiso? Se eliminará de todos los usuarios.')"
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
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No hay permisos en este módulo</p>
        </div>
        @endif
    </div>
    @endforeach
</div>

<!-- Modal Crear Permiso -->
<div id="createPermisoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">➕ Crear Nuevo Permiso</h3>
            <button onclick="closeModal('createPermisoModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.permisos.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Módulo -->
                <div>
                    <label for="id_modulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Módulo <span class="text-red-500">*</span>
                    </label>
                    <select id="id_modulo" 
                            name="id_modulo" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Seleccione un módulo...</option>
                        @foreach($modulos as $modulo)
                            <option value="{{ $modulo->id_modulo }}">{{ $modulo->nombre_modulo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre_permiso" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Permiso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_permiso" 
                           name="nombre_permiso" 
                           required
                           placeholder="Ej: Crear Resoluciones"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Clave -->
                <div>
                    <label for="clave_permiso" class="block text-sm font-medium text-gray-700 mb-2">
                        Clave del Permiso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="clave_permiso" 
                           name="clave_permiso" 
                           required
                           placeholder="Ej: crear_resoluciones"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                    <p class="mt-1 text-xs text-gray-500">Usar snake_case (minúsculas con guiones bajos)</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_permiso" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion_permiso" 
                              name="descripcion_permiso" 
                              rows="3"
                              placeholder="Breve descripción del permiso..."
                              class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>

                <!-- Activo -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           checked
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <label for="activo" class="ml-2 text-sm text-gray-700">Permiso Activo</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" 
                        onclick="closeModal('createPermisoModal')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                    ✓ Crear Permiso
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

function editPermiso(permisoId) {
    // Implementar edición
    alert('Editar permiso ID: ' + permisoId);
}
</script>
@endpush
@endsection