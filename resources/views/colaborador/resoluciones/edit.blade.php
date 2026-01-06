{{-- filepath: resources/views/colaborador/resoluciones/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Resolución
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
                </div>
            </div>
            
            <!-- Badge de Estado -->
            @php
                $estadoNombre = $resolucion->estado->nombre_estado ?? 'Desconocido';
                $estadoClasses = match($estadoNombre) {
                    'Firmada', 'Firmado', 'Aprobada', 'Aprobado' => 'bg-green-100 text-green-800',
                    'Borrador' => 'bg-yellow-100 text-yellow-800',
                    'Pendiente', 'En Proceso' => 'bg-blue-100 text-blue-800',
                    default => 'bg-gray-100 text-gray-800',
                };
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $estadoClasses }}">
                {{ $estadoNombre }}
            </span>
        </div>
    </div>

    <!-- Alertas -->
    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="text-red-700 font-medium mb-2">Por favor, corrija los siguientes errores:</p>
                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulario -->
    <form method="POST" action="{{ route('colaborador.resoluciones.update', $resolucion) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card: Información Básica -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <span class="bg-white bg-opacity-20 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">1</span>
                    Información Básica
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de Resolución -->
                    <div>
                        <label for="id_tipo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Resolución <span class="text-red-500">*</span>
                        </label>
                        <select id="id_tipo_resolucion" 
                                name="id_tipo_resolucion" 
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('id_tipo_resolucion') border-red-500 @enderror">
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
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('num_resolucion') border-red-500 @enderror">
                        @error('num_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Resolución -->
                    <div>
                        <label for="fecha_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Resolución <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="fecha_resolucion" 
                               name="fecha_resolucion" 
                               value="{{ old('fecha_resolucion', $resolucion->fecha_resolucion->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('fecha_resolucion') border-red-500 @enderror">
                        @error('fecha_resolucion')
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
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
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
        </div>

        <!-- Card: Contenido -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <span class="bg-white bg-opacity-20 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">2</span>
                    Contenido de la Resolución
                </h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Asunto -->
                <div>
                    <label for="asunto_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Asunto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="asunto_resolucion" 
                           name="asunto_resolucion" 
                           value="{{ old('asunto_resolucion', $resolucion->asunto_resolucion) }}"
                           required
                           maxlength="500"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('asunto_resolucion') border-red-500 @enderror">
                    @error('asunto_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visto -->
                <div>
                    <label for="visto_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Visto <span class="text-red-500">*</span>
                    </label>
                    <textarea id="visto_resolucion" 
                              name="visto_resolucion" 
                              required
                              rows="4"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('visto_resolucion') border-red-500 @enderror">{{ old('visto_resolucion', $resolucion->visto_resolucion) }}</textarea>
                    @error('visto_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Archivo Actual -->
                @if($resolucion->archivo_resolucion && Storage::disk('public')->exists($resolucion->archivo_resolucion))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Actual</label>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ basename($resolucion->archivo_resolucion) }}</p>
                                <p class="text-xs text-gray-500">{{ number_format(Storage::disk('public')->size($resolucion->archivo_resolucion) / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($resolucion->archivo_resolucion) }}" 
                           target="_blank"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Ver archivo
                        </a>
                    </div>
                </div>
                @endif

                <!-- Nuevo Archivo -->
                <div>
                    <label for="archivo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $resolucion->archivo_resolucion ? 'Reemplazar Archivo' : 'Subir Archivo' }} (Opcional)
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-yellow-400 transition-colors">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="archivo_resolucion" class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-600 hover:text-yellow-500">
                                    <span>Seleccionar nuevo archivo</span>
                                    <input id="archivo_resolucion" 
                                           name="archivo_resolucion" 
                                           type="file" 
                                           accept=".pdf,.doc,.docx"
                                           class="sr-only"
                                           onchange="mostrarNombreArchivo(this)">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX hasta 10MB</p>
                            <p id="nombre-archivo" class="text-sm text-yellow-600 font-medium"></p>
                        </div>
                    </div>
                    @error('archivo_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Personas Involucradas -->
                <div>
                    <label for="personas_involucradas" class="block text-sm font-medium text-gray-700 mb-2">
                        Personas Involucradas (Opcional)
                    </label>
                    <select id="personas_involucradas" 
                            name="personas_involucradas[]" 
                            multiple
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            size="5">
                        @foreach($personasInvolucradas as $idPersona)
                            <option value="{{ $idPersona }}" selected>Persona ID: {{ $idPersona }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">💡 Mantenga presionado Ctrl para seleccionar múltiples personas</p>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-end gap-4 bg-white rounded-lg shadow-md px-6 py-4">
            <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
               class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Actualizar Resolución
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function mostrarNombreArchivo(input) {
    const nombreDiv = document.getElementById('nombre-archivo');
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
        nombreDiv.textContent = `📄 ${fileName} (${fileSize} MB)`;
    } else {
        nombreDiv.textContent = '';
    }
}
</script>
@endpush
@endsection