{{-- filepath: resources/views/colaborador/personas/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Personas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👥 Gestión de Personas</h1>
            <p class="text-gray-600 mt-1">Administración de clientes y trabajadores</p>
        </div>
        @can('personas.crear')
        <a href="{{ route('colaborador.personas.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Registrar Persona
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.personas.index') }}" class="space-y-4">
            <!-- Primera fila -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Buscar</label>
                    <input type="text" 
                           name="buscar" 
                           value="{{ request('buscar') }}"
                           placeholder="Documento, nombre, apellido..."
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">👤 Tipo de Persona</label>
                    <select name="tipo_persona" id="tipo_persona_filter" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="colaborador" {{ request('tipo_persona') == 'colaborador' ? 'selected' : '' }}>
                            👔 Trabajador
                        </option>
                        <option value="cliente" {{ request('tipo_persona') == 'cliente' ? 'selected' : '' }}>
                            👥 Cliente
                        </option>
                    </select>
                </div>

                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('colaborador.personas.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        🔄
                    </a>
                    <button type="button" onclick="exportData()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition" title="Exportar">
                        📊
                    </button>
                </div>
            </div>

            <!-- Segunda fila - Filtros solo para colaboradores -->
            <div id="filtros-colaborador" class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t {{ request('tipo_persona') == 'colaborador' || !request('tipo_persona') ? '' : 'hidden' }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">🏢 Dirección</label>
                    <select name="direccion_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach($direcciones as $direccion)
                        <option value="{{ $direccion->id_direcciones }}" {{ request('direccion_id') == $direccion->id_direcciones ? 'selected' : '' }}>
                            {{ $direccion->nombre_direcciones }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">🏛️ Dependencia</label>
                    <select name="dependencia_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia->id_dependencias }}" {{ request('dependencia_id') == $dependencia->id_dependencias ? 'selected' : '' }}>
                            {{ $dependencia->nombre_dependencia }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📁 Área</label>
                    <select name="area_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id_area }}" {{ request('area_id') == $area->id_area ? 'selected' : '' }}>
                            {{ $area->nombre_area }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">💼 Cargo</label>
                    <select name="cargo_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id_cargos }}" {{ request('cargo_id') == $cargo->id_cargos ? 'selected' : '' }}>
                            {{ $cargo->nombre_cargo }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Personas</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">👔 Trabajadores</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['colaboradores'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">👥 Clientes</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['clientes'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Personas -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Persona
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Documento
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Información
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contacto
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($personas as $persona)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                    {{ substr($persona->nombres, 0, 1) }}{{ substr($persona->apellido_paterno, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $persona->nombres }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $persona->tipo_documento }}</div>
                        <div class="text-sm text-gray-500">{{ $persona->num_documento }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($persona->tipo_persona == 'colaborador')
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full font-semibold">
                                👔 Trabajador
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full font-semibold">
                                👥 Cliente
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($persona->tipo_persona == 'colaborador' && $persona->colaborador)
                            <div class="space-y-1">
                                @if($persona->colaborador->direccion)
                                    <div class="text-xs text-gray-600">🏢 {{ $persona->colaborador->direccion->nombre_direcciones }}</div>
                                @endif
                                @if($persona->colaborador->dependencia)
                                    <div class="text-xs text-gray-600">🏛️ {{ $persona->colaborador->dependencia->nombre_dependencia }}</div>
                                @endif
                                @if($persona->colaborador->area)
                                    <div class="text-xs text-gray-600">📁 {{ $persona->colaborador->area->nombre_area }}</div>
                                @endif
                                @if($persona->colaborador->cargo)
                                    <div class="text-xs font-medium text-gray-800">💼 {{ $persona->colaborador->cargo->nombre_cargo }}</div>
                                @endif
                            </div>
                        @else
                            @if($persona->direccion)
                                <div class="text-xs text-gray-600">📍 {{ Str::limit($persona->direccion, 50) }}</div>
                            @else
                                <span class="text-xs text-gray-400">Sin información</span>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            @if($persona->correo)
                                <div class="text-xs text-gray-600 mb-1">✉️ {{ Str::limit($persona->correo, 25) }}</div>
                            @endif
                            @if($persona->telefono)
                                <div class="text-xs text-gray-600">📞 {{ $persona->telefono }}</div>
                            @endif
                            @if($persona->whatsapp)
                                <div class="text-xs text-green-600">💬 {{ $persona->whatsapp }}</div>
                            @endif
                            @if(!$persona->correo && !$persona->telefono && !$persona->whatsapp)
                                <span class="text-xs text-gray-400">Sin contacto</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            @can('personas.ver')
                            <a href="{{ route('colaborador.personas.show', $persona) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Ver">
                                👁️
                            </a>
                            @endcan
                            @can('personas.editar')
                            <a href="{{ route('colaborador.personas.edit', $persona) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                ✏️
                            </a>
                            @endcan
                            @can('personas.eliminar')
                            <form method="POST" 
                                  action="{{ route('colaborador.personas.destroy', $persona) }}" 
                                  onsubmit="return confirm('¿Está seguro de eliminar esta persona?')"
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay personas registradas</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza registrando una nueva persona</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($personas->hasPages())
    <div class="mt-6">
        {{ $personas->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
// Mostrar/ocultar filtros de colaborador según tipo seleccionado
document.getElementById('tipo_persona_filter').addEventListener('change', function() {
    const filtrosColaborador = document.getElementById('filtros-colaborador');
    if (this.value === 'colaborador' || this.value === '') {
        filtrosColaborador.classList.remove('hidden');
    } else {
        filtrosColaborador.classList.add('hidden');
    }
});

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("colaborador.personas.export") }}?' + params.toString();
}
</script>
@endpush
@endsection