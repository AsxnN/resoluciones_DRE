{{-- filepath: resources/views/colaborador/colaboradores/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Colaboradores')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Colaboradores</span>
</li>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestión de Colaboradores</h1>
                <p class="text-gray-600 mt-1">Administra los colaboradores del sistema</p>
            </div>
            @can('colaboradores.crear')
            <a href="{{ route('colaborador.colaboradores.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nuevo Colaborador
            </a>
            @endcan
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('colaborador.colaboradores.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar colaborador..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg">
                
                <select name="id_cargos" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todos los cargos</option>
                    @foreach($cargos as $cargo)
                    <option value="{{ $cargo->id_cargo }}" {{ request('id_cargos') == $cargo->id_cargo ? 'selected' : '' }}>
                        {{ $cargo->nombre_cargo }}
                    </option>
                    @endforeach
                </select>

                <select name="id_direcciones" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todas las direcciones</option>
                    @foreach($direcciones as $direccion)
                    <option value="{{ $direccion->id_direcciones }}" {{ request('id_direcciones') == $direccion->id_direcciones ? 'selected' : '' }}>
                        {{ $direccion->nombre_direcciones }}
                    </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Buscar
                    </button>
                    <a href="{{ route('colaborador.colaboradores.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cargo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($colaboradores as $colaborador)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">#{{ $colaborador->id_colab_dis }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $colaborador->persona->nombre_completo ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $colaborador->persona->dni ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $colaborador->cargo->nombre_cargo ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $colaborador->unidad->nombre_unidades ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $colaborador->direccion->nombre_direcciones ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($colaborador->i_active)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Activo</span>
                            @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @can('colaboradores.editar')
                                <a href="{{ route('colaborador.colaboradores.edit', $colaborador) }}" class="text-yellow-600 hover:text-yellow-900">
                                    Editar
                                </a>
                                @endcan
                                @can('colaboradores.eliminar')
                                <form action="{{ route('colaborador.colaboradores.destroy', $colaborador) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No se encontraron colaboradores
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($colaboradores->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $colaboradores->links() }}
        </div>
        @endif
    </div>

</div>
@endsection