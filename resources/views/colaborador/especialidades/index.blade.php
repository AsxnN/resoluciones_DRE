{{-- filepath: resources/views/colaborador/especialidades/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Especialidades')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🎓 Gestión de Especialidades</h1>
            <p class="text-gray-600 mt-1">Administración de especialidades profesionales</p>
        </div>
        @can('crear_especialidades')
        <button onclick="openModal('createEspecialidadModal')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Especialidad
        </button>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre de la especialidad..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="activo" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('colaborador.especialidades.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Especialidades</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $especialidades->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
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
                    <p class="text-sm text-gray-600">Profesionales</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['con_profesionales'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Especialidades -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($especialidades as $especialidad)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-white">{{ $especialidad->nombre_especialidad }}</h3>
                    @if($especialidad->activo)
                        <span class="px-2 py-1 bg-green-500 text-white rounded text-xs font-semibold">✓</span>
                    @else
                        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs font-semibold">✗</span>
                    @endif
                </div>
                @if($especialidad->codigo_especialidad)
                <p class="text-blue-100 text-sm font-mono">{{ $especialidad->codigo_especialidad }}</p>
                @endif
            </div>

            <div class="p-6">
                @if($especialidad->descripcion_especialidad)
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($especialidad->descripcion_especialidad, 100) }}</p>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="text-center p-2 bg-blue-50 rounded">
                        <p class="text-lg font-bold text-blue-600">{{ $especialidad->personas_count ?? 0 }}</p>
                        <p class="text-xs text-blue-800">Profesionales</p>
                    </div>
                    <div class="text-center p-2 bg-green-50 rounded">
                        <p class="text-lg font-bold text-green-600">{{ $especialidad->resoluciones_count ?? 0 }}</p>
                        <p class="text-xs text-green-800">Resoluciones</p>
                    </div>
                </div>

                <!-- Nivel Académico -->
                @if($especialidad->nivel_academico)
                <div class="mb-4 p-3 bg-purple-50 rounded">
                    <p class="text-xs text-purple-800 font-semibold">Nivel Académico</p>
                    <p class="text-sm text-purple-900">{{ ucfirst($especialidad->nivel_academico) }}</p>
                </div>
                @endif

                <!-- Acciones -->
                <div class="flex gap-2">
                    @can('editar_especialidades')
                    <button onclick="editEspecialidad({{ $especialidad->id_especialidad }})" 
                            class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </button>
                    @endcan

                    @can('eliminar_especialidades')
                    <form method="POST" 
                          action="{{ route('colaborador.especialidades.destroy', $especialidad) }}" 
                          onsubmit="return confirm('¿Está seguro de eliminar esta especialidad?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition text-sm font-medium">
                            🗑️ Eliminar
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay especialidades</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva especialidad</p>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($especialidades->hasPages())
    <div class="mt-6">
        {{ $especialidades->links() }}
    </div>
    @endif
</div>

<!-- Modal Crear Especialidad -->
<div id="createEspecialidadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">➕ Nueva Especialidad</h3>
            <button onclick="closeModal('createEspecialidadModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('colaborador.especialidades.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Especialidad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_especialidad" 
                           name="nombre_especialidad" 
                           required
                           placeholder="Ej: Educación Inicial"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Código -->
                <div>
                    <label for="codigo_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Código de la Especialidad
                    </label>
                    <input type="text" 
                           id="codigo_especialidad" 
                           name="codigo_especialidad" 
                           placeholder="Ej: ESP-INI-001"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Nivel Académico -->
                <div>
                    <label for="nivel_academico" class="block text-sm font-medium text-gray-700 mb-2">
                        Nivel Académico
                    </label>
                    <select id="nivel_academico" 
                            name="nivel_academico" 
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar nivel</option>
                        <option value="tecnico">Técnico</option>
                        <option value="bachiller">Bachiller</option>
                        <option value="licenciatura">Licenciatura</option>
                        <option value="maestria">Maestría</option>
                        <option value="doctorado">Doctorado</option>
                    </select>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion_especialidad" 
                              name="descripcion_especialidad" 
                              rows="3"
                              placeholder="Descripción de la especialidad..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Activo -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="activo" class="ml-2 text-sm text-gray-700">Especialidad Activa</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" 
                        onclick="closeModal('createEspecialidadModal')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ✓ Crear Especialidad
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

function editEspecialidad(id) {
    window.location.href = `/colaborador/especialidades/${id}/edit`;
}
</script>
@endpush
@endsection