{{-- filepath: resources/views/colaborador/resoluciones/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.resoluciones.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Resolución</h1>
                    <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
                </div>
            </div>
            
            <!-- Badge de Estado -->
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $resolucion->estado->nombre_estado === 'Firmada' ? 'bg-green-100 text-green-800' : 
                   ($resolucion->estado->nombre_estado === 'Borrador' ? 'bg-yellow-100 text-yellow-800' : 
                   'bg-blue-100 text-blue-800') }}">
                {{ $resolucion->estado->nombre_estado }}
            </span>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.resoluciones.update', $resolucion) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Información Básica -->
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
                               value="{{ old('num_resolucion', $resolucion->num_resolucion) }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('num_resolucion') border-red-500 @enderror">
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
                               value="{{ old('fecha_resolucion', $resolucion->fecha_resolucion->format('Y-m-d')) }}"
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
                            @foreach($tiposResolucion as $tipo)
                                <option value="{{ $tipo->id_tipo_resolucion }}" 
                                    {{ old('id_tipo_resolucion', $resolucion->id_tipo_resolucion) == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
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
                            Estado
                        </label>
                        <select id="id_estado" 
                                name="id_estado" 
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" 
                                    {{ old('id_estado', $resolucion->id_estado) == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contenido -->
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
                           value="{{ old('asunto_resolucion', $resolucion->asunto_resolucion) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('asunto_resolucion') border-red-500 @enderror">
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
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('contenido_resolucion') border-red-500 @enderror">{{ old('contenido_resolucion', $resolucion->contenido_resolucion) }}</textarea>
                    @error('contenido_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Archivo Actual -->
                @if($resolucion->archivo_original)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Actual</label>
                    <div class="flex items-center justify-between">
                        <a href="{{ Storage::url($resolucion->archivo_original) }}" 
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ basename($resolucion->archivo_original) }}
                        </a>
                        <span class="text-sm text-gray-500">
                            {{ number_format(Storage::size($resolucion->archivo_original) / 1024, 2) }} KB
                        </span>
                    </div>
                </div>
                @endif

                <!-- Nuevo Archivo -->
                <div>
                    <label for="archivo_original" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $resolucion->archivo_original ? 'Reemplazar Archivo' : 'Subir Archivo' }} (PDF, DOCX)
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

            <!-- Observaciones -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                    Observaciones
                </h2>
                
                <textarea id="observaciones" 
                          name="observaciones" 
                          rows="4"
                          class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Notas internas, observaciones especiales...">{{ old('observaciones', $resolucion->observaciones) }}</textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('colaborador.resoluciones.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    💾 Actualizar Resolución
                </button>
            </div>
        </form>
    </div>
</div>
@endsection