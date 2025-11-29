{{-- filepath: resources/views/colaborador/personas/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Ver Persona')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.personas.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👤 Detalles de Persona</h1>
                    <p class="text-gray-600 mt-1">Información completa del registro</p>
                </div>
            </div>
            
            @can('editar_personas')
            <a href="{{ route('colaborador.personas.edit', $persona) }}" 
               class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition">
                ✏️ Editar
            </a>
            @endcan
        </div>
    </div>

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-blue-600 text-3xl font-bold shadow-lg">
                    {{ substr($persona->nombres, 0, 1) }}{{ substr($persona->apellido_paterno, 0, 1) }}
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $persona->nombre_completo }}</h2>
                    <p class="text-blue-100 mt-1">{{ $persona->tipo_documento }}: {{ $persona->num_documento }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                            {{ ucfirst($persona->tipo_persona) }}
                        </span>
                        <span class="px-3 py-1 {{ $persona->i_active ? 'bg-green-500' : 'bg-red-500' }} rounded-full text-sm">
                            {{ $persona->i_active ? '✓ Activo' : '✗ Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <!-- Información Personal -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Información Personal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Documento</p>
                            <p class="text-gray-900 font-semibold">{{ $persona->tipo_documento }}: {{ $persona->num_documento }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Nombres Completos</p>
                            <p class="text-gray-900 font-semibold">{{ $persona->nombre_completo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Correo Electrónico</p>
                            <p class="text-gray-900 font-semibold">{{ $persona->correo }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Teléfono</p>
                            <p class="text-gray-900 font-semibold">{{ $persona->telefono ?: 'No registrado' }}</p>
                        </div>
                    </div>

                    @if($persona->direccion)
                    <div class="md:col-span-2 flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Dirección</p>
                            <p class="text-gray-900 font-semibold">{{ $persona->direccion }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">ID del Sistema:</span>
                        <span class="ml-2 font-mono text-gray-900">{{ $persona->id_persona }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Fecha de Registro:</span>
                        <span class="ml-2 text-gray-900">{{ $persona->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Última Actualización:</span>
                        <span class="ml-2 text-gray-900">{{ $persona->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Datos Completos:</span>
                        <span class="ml-2">
                            @if($persona->datos_completos)
                                <span class="text-green-600 font-semibold">✓ Sí</span>
                            @else
                                <span class="text-red-600 font-semibold">✗ No</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones adicionales -->
    <div class="mt-6 flex gap-4">
        @can('editar_personas')
        <a href="{{ route('colaborador.personas.edit', $persona) }}" 
           class="flex-1 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg text-center transition">
            ✏️ Editar Información
        </a>
        @endcan
        
        @can('eliminar_personas')
        <form method="POST" 
              action="{{ route('colaborador.personas.destroy', $persona) }}" 
              onsubmit="return confirm('¿Está seguro de eliminar esta persona? Esta acción no se puede deshacer.')"
              class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                🗑️ Eliminar Persona
            </button>
        </form>
        @endcan
    </div>
</div>
@endsection