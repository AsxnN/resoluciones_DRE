{{-- filepath: resources/views/colaborador/areas/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Área')

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
                <h1 class="text-3xl font-bold text-gray-900">➕ Crear Nueva Área</h1>
                <p class="text-gray-600 mt-1">Complete el formulario para registrar una nueva área</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.areas.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Nombre del Área -->
                <div>
                    <label for="nombre_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Área <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_area" 
                           name="nombre_area" 
                           value="{{ old('nombre_area') }}"
                           required
                           placeholder="Ej: Dirección de Gestión Pedagógica"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_area') border-red-500 @enderror">
                    @error('nombre_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Código del Área -->
                <div>
                    <label for="codigo_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Código del Área
                    </label>
                    <input type="text" 
                           id="codigo_area" 
                           name="codigo_area" 
                           value="{{ old('codigo_area') }}"
                           placeholder="Ej: DGP-001"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('codigo_area') border-red-500 @enderror">
                    @error('codigo_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Código único de identificación del área (opcional)</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción del Área
                    </label>
                    <textarea id="descripcion_area" 
                              name="descripcion_area" 
                              rows="4"
                              placeholder="Descripción detallada de las funciones y responsabilidades del área..."
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion_area') border-red-500 @enderror">{{ old('descripcion_area') }}</textarea>
                    @error('descripcion_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Responsable del Área -->
                <div>
                    <label for="responsable" class="block text-sm font-medium text-gray-700 mb-2">
                        Responsable del Área
                    </label>
                    <input type="text" 
                           id="responsable" 
                           name="responsable" 
                           value="{{ old('responsable') }}"
                           placeholder="Nombre completo del responsable del área"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('responsable') border-red-500 @enderror">
                    @error('responsable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           {{ old('activo', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <label for="activo" class="ml-3">
                        <span class="text-sm font-medium text-gray-700">Área Activa</span>
                        <p class="text-xs text-gray-500">El área estará disponible para asignar personal y generar resoluciones</p>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.areas.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Crear Área
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Información sobre Áreas</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Las áreas se utilizan para organizar el personal de la DRE Huánuco</li>
                        <li>Cada área puede tener un responsable asignado</li>
                        <li>El código del área es opcional pero recomendado para identificación rápida</li>
                        <li>Solo las áreas activas estarán disponibles para asignaciones</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection