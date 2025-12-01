{{-- filepath: resources/views/colaborador/areas/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Áreas')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Áreas</span>
</li>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestión de Áreas</h1>
                <p class="text-gray-600 mt-1">Administra las áreas del sistema</p>
            </div>
            @can('areas.crear')
            <a href="{{ route('colaborador.areas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nueva Área
            </a>
            @endcan
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('colaborador.areas.index') }}">
            <div class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar área..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Buscar
                </button>
                <a href="{{ route('colaborador.areas.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($areas as $area)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">#{{ $area->id_area }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $area->nombre_area }}</td>
                    <td class="px-6 py-4">
                        @if($area->i_active)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Activa</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactiva</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            @can('areas.editar')
                            <a href="{{ route('colaborador.areas.edit', $area) }}" class="text-yellow-600 hover:text-yellow-900">
                                Editar
                            </a>
                            @endcan
                            @can('areas.eliminar')
                            <form action="{{ route('colaborador.areas.destroy', $area) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        No se encontraron áreas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($areas->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $areas->links() }}
        </div>
        @endif
    </div>

</div>
@endsection