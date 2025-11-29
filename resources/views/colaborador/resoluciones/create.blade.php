{{-- filepath: resources/views/colaborador/resoluciones/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nueva Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📝 Nueva Resolución</h1>
                <p class="text-gray-600 mt-1">Crear una nueva resolución directoral</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.resoluciones.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Sección 1: Información Básica -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">1</span>
                    Información Básica
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Número de Resolución -->
                    <div>
                        <label for="num_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Resolución <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="num_resolucion" 
                               name="num_resolucion" 
                               value="{{ old('num_resolucion') }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('num_resolucion') border-red-500 @enderror"
                               placeholder="RD-2024-001">
                        @error('num_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Resolución <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="fecha_resolucion" 
                               name="fecha_resolucion" 
                               value="{{ old('fecha_resolucion', now()->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('fecha_resolucion') border-red-500 @enderror">
                        @error('fecha_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Resolución -->
                    <div>
                        <label for="id_tipo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Resolución <span class="text-red-500">*</span>
                        </label>
                        <select id="id_tipo_resolucion" 
                                name="id_tipo_resolucion" 
                                required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('id_tipo_resolucion') border-red-500 @enderror">
                            <option value="">Seleccione...</option>
                            @foreach($tiposResolucion as $tipo)
                                <option value="{{ $tipo->id_tipo_resolucion }}" {{ old('id_tipo_resolucion') == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo_resolucion }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="id_estado" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado Inicial
                        </label>
                        <select id="id_estado" 
                                name="id_estado" 
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('id_estado', $estadoBorradorId) == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Contenido -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">2</span>
                    Contenido de la Resolución
                </h2>
                
                <!-- Asunto -->
                <div class="mb-6">
                    <label for="asunto_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Asunto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="asunto_resolucion" 
                           name="asunto_resolucion" 
                           value="{{ old('asunto_resolucion') }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('asunto_resolucion') border-red-500 @enderror"
                           placeholder="Describe brevemente el asunto de la resolución">
                    @error('asunto_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contenido -->
                <div class="mb-6">
                    <label for="contenido_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenido / Considerandos <span class="text-red-500">*</span>
                    </label>
                    <textarea id="contenido_resolucion" 
                              name="contenido_resolucion" 
                              required
                              rows="10"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('contenido_resolucion') border-red-500 @enderror"
                              placeholder="Redacte el contenido completo de la resolución...">{{ old('contenido_resolucion') }}</textarea>
                    @error('contenido_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">💡 Puede usar formato de texto enriquecido</p>
                </div>

                <!-- Archivo Original -->
                <div>
                    <label for="archivo_original" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo Original (PDF, DOCX)
                    </label>
                    <input type="file" 
                           id="archivo_original" 
                           name="archivo_original" 
                           accept=".pdf,.docx,.doc"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('archivo_original')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sección 3: Observaciones -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                    Observaciones (Opcional)
                </h2>
                
                <textarea id="observaciones" 
                          name="observaciones" 
                          rows="4"
                          class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Notas internas, observaciones especiales...">{{ old('observaciones') }}</textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('colaborador.resoluciones.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    💾 Guardar Resolución
                </button>
            </div>
        </form>
    </div>
</div>
@endsection