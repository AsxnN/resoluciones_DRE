{{-- filepath: resources/views/colaborador/colaboradores/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Colaborador')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $colaborador->persona->nombre_completo ?? 'N/A' }}</h1>
            @can('colaboradores.editar')
            <a href="{{ route('colaborador.colaboradores.edit', $colaborador) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                Editar
            </a>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Documento</p>
                <p class="text-gray-900">{{ $colaborador->persona->tipo_documento ?? '' }} {{ $colaborador->persona->num_documento ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Código de Colaborador</p>
                <p class="text-gray-900">{{ $colaborador->id_colab_dis }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Área</p>
                <p class="text-gray-900">{{ $colaborador->area->nombre_area ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Cargo</p>
                <p class="text-gray-900">{{ $colaborador->cargo->nombre_cargo ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Unidad</p>
                <p class="text-gray-900">{{ $colaborador->unidad->nombre_unidad ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Dirección</p>
                <p class="text-gray-900">{{ $colaborador->direccion->nombre_direcciones ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Dependencia</p>
                <p class="text-gray-900">{{ $colaborador->dependencia->nombre_dependencia ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Especialidad</p>
                <p class="text-gray-900">{{ $colaborador->especialidad->nombre_especialidad ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Tipo de Personal</p>
                <p class="text-gray-900">{{ $colaborador->tipoPersonal->nombre_tipo_personal ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Usuario del Sistema</p>
                <p class="text-gray-900">{{ $colaborador->usuario->username ?? 'Sin cuenta' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase font-semibold">Estado</p>
                @if($colaborador->i_active)
                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Activo</span>
                @else
                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-6 border-t mt-6">
            <a href="{{ route('colaborador.colaboradores.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection
