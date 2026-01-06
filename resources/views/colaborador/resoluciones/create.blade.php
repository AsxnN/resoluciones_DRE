{{-- filepath: resources/views/colaborador/resoluciones/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Nueva Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Nueva Resolución
                </h1>
                <p class="text-gray-600 mt-1">Crear una nueva resolución directoral</p>
            </div>
        </div>
    </div>

    <!-- Alertas de error -->
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
    <form method="POST" action="{{ route('colaborador.resoluciones.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card: Información Básica -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
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
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_tipo_resolucion') border-red-500 @enderror">
                            <option value="">Seleccione un tipo...</option>
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

                    <!-- Número de Resolución -->
                    <div>
                        <label for="num_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Resolución <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="num_resolucion" 
                                   name="num_resolucion" 
                                   value="{{ old('num_resolucion') }}"
                                   required
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('num_resolucion') border-red-500 @enderror"
                                   placeholder="Ej: RD-001-2025">
                            <button type="button" 
                                    onclick="generarNumero()"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                        @error('num_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">💡 Haga clic en el botón para generar automáticamente</p>
                    </div>

                    <!-- Fecha de Resolución -->
                    <div>
                        <label for="fecha_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Resolución <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="fecha_resolucion" 
                               name="fecha_resolucion" 
                               value="{{ old('fecha_resolucion', now()->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_resolucion') border-red-500 @enderror">
                        @error('fecha_resolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado (opcional, se asigna automáticamente) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado Inicial
                        </label>
                        <div class="flex items-center h-10 px-3 bg-gray-50 border border-gray-300 rounded-lg">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                                Borrador
                            </span>
                            <span class="ml-2 text-sm text-gray-600">(Se asigna automáticamente)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Contenido -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
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
                           value="{{ old('asunto_resolucion') }}"
                           required
                           maxlength="500"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('asunto_resolucion') border-red-500 @enderror"
                           placeholder="Breve descripción del asunto de la resolución">
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
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('visto_resolucion') border-red-500 @enderror"
                              placeholder="Documentos y antecedentes revisados para la resolución...">{{ old('visto_resolucion') }}</textarea>
                    @error('visto_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">📋 Documentos y antecedentes que fundamentan la resolución</p>
                </div>

                <!-- Archivo PDF/DOCX -->
                <div>
                    <label for="archivo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo de la Resolución (PDF, DOC, DOCX)
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="archivo_resolucion" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Seleccionar archivo</span>
                                    <input id="archivo_resolucion" 
                                           name="archivo_resolucion" 
                                           type="file" 
                                           accept=".pdf,.doc,.docx"
                                           class="sr-only"
                                           onchange="mostrarNombreArchivo(this)">
                                </label>
                                <p class="pl-1">o arrastrar aquí</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX hasta 10MB</p>
                            <p id="nombre-archivo" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    @error('archivo_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror>
                </div>

                <!-- Personas Involucradas (opcional) -->
                <div>
                    <label for="personas_involucradas" class="block text-sm font-medium text-gray-700 mb-2">
                        Personas Involucradas (Opcional)
                    </label>
                    <select id="personas_involucradas" 
                            name="personas_involucradas[]" 
                            multiple
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            size="5">
                        <!-- Se puede llenar dinámicamente con JavaScript o desde el controlador -->
                    </select>
                    <p class="mt-1 text-xs text-gray-500">💡 Mantenga presionado Ctrl para seleccionar múltiples personas</p>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-end gap-4 bg-white rounded-lg shadow-md px-6 py-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                Guardar Resolución
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

async function generarNumero() {
    const tipoSelect = document.getElementById('id_tipo_resolucion');
    const numInput = document.getElementById('num_resolucion');
    
    if (!tipoSelect.value) {
        alert('Por favor, seleccione primero el tipo de resolución');
        tipoSelect.focus();
        return;
    }
    
    try {
        const response = await fetch('{{ route("colaborador.resoluciones.generarNumero") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tipo_resolucion_id: tipoSelect.value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            numInput.value = data.num_resolucion;
        } else {
            alert('Error al generar el número de resolución');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al conectar con el servidor');
    }
}
</script>
@endpush
@endsection