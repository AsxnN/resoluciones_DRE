{{-- filepath: resources/views/colaborador/tipo-personal/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Tipos de Personal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👔 Gestión de Tipos de Personal</h1>
            <p class="text-gray-600 mt-1">Administración de clasificaciones de personal</p>
        </div>
        @can('crear_tipo_personal')
        <button onclick="openModal('createTipoPersonalModal')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Tipo
        </button>
        @endcan
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Tipos</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $tiposPersonal->count() }}</p>
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
                    <p class="text-sm text-gray-600">Nombrados</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['nombrados'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">Contratados</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['contratados'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">CAS</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['cas'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Tipos de Personal -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($tiposPersonal as $tipo)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition border-t-4 
            {{ $tipo->codigo === 'nombrado' ? 'border-green-500' : 
               ($tipo->codigo === 'contratado' ? 'border-yellow-500' : 
               ($tipo->codigo === 'cas' ? 'border-purple-500' : 'border-blue-500')) }}">
            
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">
                        @if($tipo->codigo === 'nombrado')
                            ✅
                        @elseif($tipo->codigo === 'contratado')
                            ⏱️
                        @elseif($tipo->codigo === 'cas')
                            📄
                        @else
                            👤
                        @endif
                    </div>
                    @if($tipo->activo)
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Activo</span>
                    @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Inactivo</span>
                    @endif
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $tipo->nombre }}</h3>
                
                @if($tipo->descripcion)
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($tipo->descripcion, 80) }}</p>
                @endif

                <!-- Stats -->
                <div class="mb-4 p-3 bg-gray-50 rounded text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $tipo->personas_count ?? 0 }}</p>
                    <p class="text-xs text-gray-600">Personas</p>
                </div>

                <!-- Características -->
                @if($tipo->beneficios || $tipo->requisitos)
                <div class="mb-4 space-y-2 text-xs">
                    @if($tipo->beneficios)
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-600">{{ Str::limit($tipo->beneficios, 50) }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Acciones -->
                <div class="flex gap-2">
                    @can('editar_tipo_personal')
                    <button onclick="editTipoPersonal({{ $tipo->id }})" 
                            class="flex-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center rounded-lg transition text-sm font-medium">
                        ✏️ Editar
                    </button>
                    @endcan

                    @can('eliminar_tipo_personal')
                    @if(($tipo->personas_count ?? 0) == 0)
                    <form method="POST" 
                          action="{{ route('colaborador.tipo-personal.destroy', $tipo) }}" 
                          onsubmit="return confirm('¿Está seguro de eliminar este tipo?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition text-sm font-medium">
                            🗑️
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tipos de personal</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo tipo</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Crear Tipo Personal -->
<div id="createTipoPersonalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">➕ Nuevo Tipo de Personal</h3>
            <button onclick="closeModal('createTipoPersonalModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('colaborador.tipo-personal.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Tipo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           required
                           placeholder="Ej: Nombrado"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Código -->
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="codigo" 
                           name="codigo" 
                           required
                           placeholder="Ej: nombrado"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Código único (sin espacios, minúsculas)</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              rows="3"
                              placeholder="Descripción del tipo de personal..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Beneficios -->
                <div>
                    <label for="beneficios" class="block text-sm font-medium text-gray-700 mb-2">
                        Beneficios
                    </label>
                    <textarea id="beneficios" 
                              name="beneficios" 
                              rows="2"
                              placeholder="Beneficios asociados..."
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
                    <label for="activo" class="ml-2 text-sm text-gray-700">Tipo Activo</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" 
                        onclick="closeModal('createTipoPersonalModal')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ✓ Crear Tipo
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

function editTipoPersonal(id) {
    window.location.href = `/colaborador/tipo-personal/${id}/edit`;
}
</script>
@endpush
@endsection