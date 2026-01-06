{{-- filepath: resources/views/colaborador/cargos/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Cargo')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.cargos.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Cargo</h1>
                <p class="text-gray-600 mt-1">Modificar información de: <strong>{{ $cargo->nombre_cargo }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.cargos.update', $cargo) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código -->
                    <div>
                        <label for="codigo_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="codigo_cargo" 
                               name="codigo_cargo" 
                               value="{{ old('codigo_cargo', $cargo->codigo_cargo) }}"
                               required
                               maxlength="20"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('codigo_cargo') border-red-500 @enderror">
                        @error('codigo_cargo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Cargo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre_cargo" 
                               name="nombre_cargo" 
                               value="{{ old('nombre_cargo', $cargo->nombre_cargo) }}"
                               required
                               maxlength="100"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_cargo') border-red-500 @enderror">
                        @error('nombre_cargo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $cargo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" 
                           id="i_active" 
                           name="i_active" 
                           value="1"
                           {{ old('i_active', $cargo->i_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <label for="i_active" class="ml-3">
                        <span class="text-sm font-medium text-gray-700">Cargo Activo</span>
                        <p class="text-xs text-gray-500">El cargo estará disponible para asignar</p>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.cargos.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Actualizar Cargo
                </button>
            </div>
        </form>
    </div>

    <!-- Info adicional -->
    @if($cargo->colaboradores->count() > 0)
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Colaboradores Asignados</h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Este cargo tiene <strong>{{ $cargo->colaboradores->count() }}</strong> colaboradores asignados.
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection