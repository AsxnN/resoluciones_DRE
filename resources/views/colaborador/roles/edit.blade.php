{{-- filepath: resources/views/colaborador/roles/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Rol Organizacional')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.roles.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Rol Organizacional</h1>
                <p class="text-gray-600 mt-1">Modificar: <strong>{{ $role->nombre_rol }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.roles.update', $role->id_rol) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nombre del Rol -->
                <div>
                    <label for="nombre_rol" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Rol <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_rol" 
                           name="nombre_rol" 
                           value="{{ old('nombre_rol', $role->nombre_rol) }}"
                           required
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_rol') border-red-500 @enderror">
                    @error('nombre_rol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              rows="4"
                              maxlength="255"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $role->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="i_active" 
                               value="1"
                               {{ old('i_active', $role->i_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.roles.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection