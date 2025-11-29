{{-- filepath: resources/views/colaborador/especialidades/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Especialidad')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.especialidades.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Especialidad</h1>
                <p class="text-gray-600 mt-1">Modificar información de: <strong>{{ $especialidad->nombre_especialidad }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.especialidades.update', $especialidad) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Especialidad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_especialidad" 
                           name="nombre_especialidad" 
                           value="{{ old('nombre_especialidad', $especialidad->nombre_especialidad) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_especialidad') border-red-500 @enderror">
                    @error('nombre_especialidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Código -->
                <div>
                    <label for="codigo_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Código de la Especialidad
                    </label>
                    <input type="text" 
                           id="codigo_especialidad" 
                           name="codigo_especialidad" 
                           value="{{ old('codigo_especialidad', $especialidad->codigo_especialidad) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('codigo_especialidad') border-red-500 @enderror">
                    @error('codigo_especialidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nivel Académico -->
                <div>
                    <label for="nivel_academico" class="block text-sm font-medium text-gray-700 mb-2">
                        Nivel Académico
                    </label>
                    <select id="nivel_academico" 
                            name="nivel_academico" 
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nivel_academico') border-red-500 @enderror">
                        <option value="">Seleccionar nivel</option>
                        <option value="tecnico" {{ old('nivel_academico', $especialidad->nivel_academico) == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="bachiller" {{ old('nivel_academico', $especialidad->nivel_academico) == 'bachiller' ? 'selected' : '' }}>Bachiller</option>
                        <option value="licenciatura" {{ old('nivel_academico', $especialidad->nivel_academico) == 'licenciatura' ? 'selected' : '' }}>Licenciatura</option>
                        <option value="maestria" {{ old('nivel_academico', $especialidad->nivel_academico) == 'maestria' ? 'selected' : '' }}>Maestría</option>
                        <option value="doctorado" {{ old('nivel_academico', $especialidad->nivel_academico) == 'doctorado' ? 'selected' : '' }}>Doctorado</option>
                    </select>
                    @error('nivel_academico')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion_especialidad" 
                              name="descripcion_especialidad" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion_especialidad') border-red-500 @enderror">{{ old('descripcion_especialidad', $especialidad->descripcion_especialidad) }}</textarea>
                    @error('descripcion_especialidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activo -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           {{ old('activo', $especialidad->activo) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <label for="activo" class="ml-3">
                        <span class="text-sm font-medium text-gray-700">Especialidad Activa</span>
                        <p class="text-xs text-gray-500">La especialidad estará disponible en el sistema</p>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.especialidades.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Actualizar Especialidad
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Esta especialidad está asociada a:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>{{ $especialidad->personas_count ?? 0 }} Profesionales</li>
                        <li>{{ $especialidad->resoluciones_count ?? 0 }} Resoluciones</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection