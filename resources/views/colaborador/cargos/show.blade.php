{{-- filepath: resources/views/colaborador/cargos/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Ver Cargo')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.cargos.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">💼 {{ $cargo->nombre_cargo }}</h1>
                    <p class="text-gray-600 mt-1">Código: {{ $cargo->codigo_cargo }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                @can('cargos.editar')
                <a href="{{ route('colaborador.cargos.edit', $cargo) }}" 
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">
                    ✏️ Editar
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">📋 Información del Cargo</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Código</dt>
                        <dd class="mt-1 text-lg font-mono text-gray-900">{{ $cargo->codigo_cargo }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $cargo->nombre_cargo }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="mt-1 text-gray-900">{{ $cargo->descripcion ?? 'Sin descripción' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            @if($cargo->i_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                    ✓ Activo
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                    ✗ Inactivo
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Colaboradores -->
            @if($cargo->colaboradores->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">👥 Colaboradores con este Cargo</h3>
                
                <div class="space-y-3">
                    @foreach($cargo->colaboradores as $colaborador)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $colaborador->persona->nombres }} {{ $colaborador->persona->apellido_paterno }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $colaborador->persona->num_documento }}</p>
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

        <!-- Sidebar -->
        <div>
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">📊 Estadísticas</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm opacity-90">Colaboradores</p>
                        <p class="text-3xl font-bold">{{ $cargo->colaboradores->count() }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm opacity-90">Creado</p>
                        <p class="text-sm">{{ $cargo->fecha_creacion->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm opacity-90">Última actualización</p>
                        <p class="text-sm">{{ $cargo->fecha_actualizacion->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection