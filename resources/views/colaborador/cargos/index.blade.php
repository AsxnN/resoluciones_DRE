{{-- filepath: resources/views/colaborador/cargos/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Cargos')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Cargos</span>
</li>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestión de Cargos</h1>
                <p class="text-gray-600 mt-1">Administra los cargos del sistema</p>
            </div>
            @can('cargos.crear')
            <a href="{{ route('colaborador.cargos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nuevo Cargo
            </a>
            @endcan
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('colaborador.cargos.index') }}">
            <div class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar cargo..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Buscar
                </button>
                <a href="{{ route('colaborador.cargos.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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
                @forelse($cargos as $cargo)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">#{{ $cargo->id_cargo }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $cargo->nombre_cargo }}</td>
                    <td class="px-6 py-4">
                        @if($cargo->i_active)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Activo</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            @can('cargos.editar')
                            <a href="{{ route('colaborador.cargos.edit', $cargo) }}" class="text-yellow-600 hover:text-yellow-900">
                                Editar
                            </a>
                            @endcan
                            @can('cargos.eliminar')
                            <form action="{{ route('colaborador.cargos.destroy', $cargo) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
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
                        No se encontraron cargos
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($cargos->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $cargos->links() }}
        </div>
        @endif
    </div>

</div>
@endsection