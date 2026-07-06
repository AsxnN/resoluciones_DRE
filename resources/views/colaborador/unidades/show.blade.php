{{-- filepath: resources/views/colaborador/unidades/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Unidad')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.unidades.index') }}"
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $unidad->nombre_unidad }}</h1>
            </div>
            @can('unidades.editar')
            <a href="{{ route('colaborador.unidades.edit', $unidad) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $unidad->nombre_unidad }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="mt-1">
                    @if($unidad->i_active)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                            ✓ Activa
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                            ✗ Inactiva
                        </span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Colaboradores en esta unidad</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $unidad->colaboradores->count() }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
