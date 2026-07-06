{{-- filepath: resources/views/colaborador/cargos/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Cargo')

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
                <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Cargo</h1>
                <p class="text-gray-600 mt-1">Complete el formulario para registrar un nuevo cargo</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.cargos.store') }}">
            @csrf

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
                               value="{{ old('codigo_cargo') }}"
                               required
                               maxlength="20"
                               placeholder="Ej: CARGO-001"
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
                               value="{{ old('nombre_cargo') }}"
                               required
                               maxlength="100"
                               placeholder="Ej: Director General"
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
                              placeholder="Describe las funciones y responsabilidades del cargo..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.cargos.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    Crear Cargo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection