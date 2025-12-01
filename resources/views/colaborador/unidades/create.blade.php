@extends('layouts.colaborador')

@section('title', 'Crear Unidad')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <a href="{{ route('colaborador.unidades.index') }}" class="text-gray-600 hover:text-gray-900 transition">Unidades</a>
</li>
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Crear</span>
</li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-100 rounded-lg">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-gray-900">Crear Nueva Unidad</h1>
                    <p class="text-sm text-gray-600 mt-1">Complete el formulario para registrar una nueva unidad organizacional</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('colaborador.unidades.store') }}" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Nombre de la Unidad -->
                <div>
                    <label for="nombre_unidades" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Unidad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_unidades"
                           name="nombre_unidades" 
                           value="{{ old('nombre_unidades') }}" 
                           required
                           maxlength="100"
                           placeholder="Ej: Unidad de Gestión Pedagógica"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nombre_unidades') border-red-500 @enderror">
                    @error('nombre_unidades')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Máximo 100 caracteres</p>
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Información</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                La unidad se creará con estado <strong>Activo</strong> por defecto.
                                Puede cambiar el estado posteriormente desde la opción de edición.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('colaborador.unidades.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Unidad
                </button>
            </div>
        </form>
    </div>
</div>
@endsection