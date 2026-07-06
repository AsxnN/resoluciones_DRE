{{-- filepath: resources/views/colaborador/direcciones/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Dirección')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.direcciones.index') }}"
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $direccion->nombre_direcciones }}</h1>
            </div>
            @can('direcciones.editar')
            <a href="{{ route('colaborador.direcciones.edit', $direccion) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Información de la Dirección</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $direccion->nombre_direcciones }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            @if($direccion->i_active)
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
                </dl>
            </div>

            @if($direccion->colaboradores->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Colaboradores en esta Dirección</h3>
                <div class="space-y-3">
                    @foreach($direccion->colaboradores as $colaborador)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $colaborador->persona->nombres ?? '' }} {{ $colaborador->persona->apellido_paterno ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $colaborador->persona->num_documento ?? '' }}</p>
                        </div>
                        <a href="{{ route('colaborador.colaboradores.show', $colaborador) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            Ver →
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div>
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Estadísticas</h3>
                <div>
                    <p class="text-sm opacity-90">Colaboradores</p>
                    <p class="text-3xl font-bold">{{ $stats['colaboradores'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
