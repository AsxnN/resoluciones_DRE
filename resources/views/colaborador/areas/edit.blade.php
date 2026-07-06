{{-- filepath: resources/views/colaborador/areas/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Área')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.areas.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Área</h1>
                <p class="text-gray-600 mt-1">Modificar información de: <strong>{{ $area->nombre_area }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.areas.update', $area) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Área <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_area" 
                           name="nombre_area" 
                           value="{{ old('nombre_area', $area->nombre_area) }}"
                           required
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_area') border-red-500 @enderror">
                    @error('nombre_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" 
                           id="i_active" 
                           name="i_active" 
                           value="1"
                           {{ old('i_active', $area->i_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <label for="i_active" class="ml-3">
                        <span class="text-sm font-medium text-gray-700">Área Activa</span>
                        <p class="text-xs text-gray-500">El área estará disponible para asignar</p>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.areas.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    Actualizar Área
                </button>
            </div>
        </form>
    </div>
</div>
@endsection