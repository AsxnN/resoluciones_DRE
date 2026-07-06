{{-- filepath: resources/views/colaborador/tipos-resolucion/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Tipos de Resolución')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📄 Gestión de Tipos de Resolución</h1>
            <p class="text-gray-600 mt-1">Administración de tipos de resolución</p>
        </div>
        @can('tipos-resolucion.crear')
        <a href="{{ route('colaborador.tipos-resolucion.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Tipo
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre del tipo..."
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="i_active" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('i_active') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('i_active') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('colaborador.tipos-resolucion.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    🔄
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tiposResolucion as $tipo)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">#{{ $tipo->id_tipo_resolucion }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $tipo->nombre_tipo_resolucion }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($tipo->i_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                ✓ Activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                ✗ Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            @can('tipos-resolucion.editar')
                            <a href="{{ route('colaborador.tipos-resolucion.edit', $tipo) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                ✏️
                            </a>
                            @endcan
                            @can('tipos-resolucion.eliminar')
                            <form method="POST" 
                                  action="{{ route('colaborador.tipos-resolucion.destroy', $tipo) }}" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este tipo?')"
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
                    <td colspan="4" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tipos de resolución</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo tipo</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($tiposResolucion->hasPages())
    <div class="mt-6">
        {{ $tiposResolucion->links() }}
    </div>
    @endif
</div>
@endsection