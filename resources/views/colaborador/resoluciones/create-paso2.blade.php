{{-- filepath: resources/views/colaborador/resoluciones/create-paso2.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Nueva Resolución - Paso 2')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.resoluciones.create') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">RESOLUCIONES</h1>
                <p class="text-gray-600 mt-1">Paso 2 de 3: Contenido de la resolución</p>
            </div>
        </div>
    </div>

    <!-- Número de Resolución -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">N° DE RESOLUCIÓN</p>
                <h2 class="text-3xl font-bold text-white">{{ $datosPaso1['num_resolucion'] ?? 'N/A' }}</h2>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Indicador de pasos -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Datos Básicos</span>
                </div>
                <div class="w-24 h-1 bg-blue-600"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        2
                    </div>
                    <span class="text-sm font-medium text-blue-600 mt-2">Contenido</span>
                </div>
                <div class="w-24 h-1 bg-gray-300"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-lg">
                        3
                    </div>
                    <span class="text-sm font-medium text-gray-500 mt-2">Confirmar</span>
                </div>
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

    <!-- Título con Dependencia -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">DEPENDENCIA-UGEL</h2>
            @if(isset($datosPaso1['id_dependencia']) && $datosPaso1['id_dependencia'])
                @php
                    $dependencia = \App\Models\Dependencia::find($datosPaso1['id_dependencia']);
                @endphp
                <span class="text-gray-600">{{ $dependencia ? $dependencia->nombre_dependencia : 'No especificada' }}</span>
            @else
                <span class="text-gray-400 italic">No especificada</span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('colaborador.resoluciones.store-paso2') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card: Selección de Resolución Dependiente -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <label for="id_resolucion_dependiente" class="block text-sm font-medium text-gray-700 mb-2">
                    N° RESOLUCIÓN (Dependiente - Opcional)
                </label>
                <select id="id_resolucion_dependiente" 
                        name="id_resolucion_dependiente"
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-------Seleccione resolución-------</option>
                    @if(isset($resoluciones))
                        @foreach($resoluciones as $resolucion)
                            <option value="{{ $resolucion->id_resolucion }}" 
                                    {{ old('id_resolucion_dependiente') == $resolucion->id_resolucion ? 'selected' : '' }}>
                                {{ $resolucion->num_resolucion }} - {{ Str::limit($resolucion->asunto_resolucion, 60) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <p class="mt-1 text-xs text-gray-500">💡 Seleccione si esta resolución depende o modifica una resolución anterior</p>
            </div>
        </div>

        <!-- Card: Visto -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <label for="visto_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                VISTO <span class="text-red-500">*</span>
            </label>
            <textarea id="visto_resolucion" 
                      name="visto_resolucion" 
                      required
                      rows="6"
                      class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('visto_resolucion') border-red-500 @enderror"
                      placeholder="Documentos y antecedentes revisados para la resolución...">{{ old('visto_resolucion') }}</textarea>
            @error('visto_resolucion')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">📋 Ingrese los documentos y antecedentes que fundamentan esta resolución</p>
        </div>

        <!-- Card: Asunto -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <label for="asunto_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                ASUNTO <span class="text-red-500">*</span>
            </label>
            <textarea id="asunto_resolucion" 
                      name="asunto_resolucion" 
                      required
                      rows="4"
                      maxlength="500"
                      class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('asunto_resolucion') border-red-500 @enderror"
                      placeholder="Breve descripción del asunto de la resolución...">{{ old('asunto_resolucion') }}</textarea>
            @error('asunto_resolucion')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="flex justify-between items-center mt-1">
                <p class="text-xs text-gray-500">📝 Describa brevemente el asunto principal de la resolución</p>
                <p class="text-xs text-gray-400" id="contador-caracteres">0 / 500 caracteres</p>
            </div>
        </div>

        <!-- Card: Subir PDF -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <label for="archivo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                PDF (Opcional)
            </label>
            
            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label for="archivo_resolucion" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span class="px-3 py-2 bg-blue-50 rounded-lg hover:bg-blue-100">Seleccionar archivo PDF</span>
                            <input id="archivo_resolucion" 
                                   name="archivo_resolucion" 
                                   type="file" 
                                   accept=".pdf,.doc,.docx"
                                   class="sr-only"
                                   onchange="mostrarNombreArchivo(this)">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">PDF, DOC, DOCX - Máximo 10MB</p>
                    <div id="archivo-info" class="mt-3 hidden">
                        <div class="flex items-center justify-center gap-2 text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span id="nombre-archivo" class="font-medium"></span>
                        </div>
                    </div>
                </div>
            </div>
            @error('archivo_resolucion')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Resumen de Datos del Paso 1 -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Datos del Paso 1</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-blue-600 font-medium">N° Resolución:</span>
                            <p class="text-blue-900">{{ $datosPaso1['num_resolucion'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Fecha:</span>
                            <p class="text-blue-900">{{ isset($datosPaso1['fecha_resolucion']) ? \Carbon\Carbon::parse($datosPaso1['fecha_resolucion'])->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Personas Relacionadas:</span>
                            <p class="text-blue-900">{{ isset($datosPaso1['personas_relacionadas']) ? count($datosPaso1['personas_relacionadas']) : 0 }} persona(s)</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Tipo:</span>
                            <p class="text-blue-900">
                                @if(isset($datosPaso1['id_tipo_resolucion']))
                                    @php
                                        $tipo = \App\Models\TipoResolucion::find($datosPaso1['id_tipo_resolucion']);
                                    @endphp
                                    {{ $tipo ? $tipo->nombre_tipo_resolucion : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Navegación -->
        <div class="flex items-center justify-between">
            <a href="{{ route('colaborador.resoluciones.create') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
            </a>

            <button type="submit" 
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg transition-colors text-lg shadow-lg flex items-center gap-2">
                Siguiente
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Contador de caracteres para el asunto
document.getElementById('asunto_resolucion').addEventListener('input', function() {
    const contador = document.getElementById('contador-caracteres');
    const longitud = this.value.length;
    contador.textContent = `${longitud} / 500 caracteres`;
    
    if (longitud > 450) {
        contador.classList.add('text-red-600', 'font-bold');
        contador.classList.remove('text-gray-400');
    } else {
        contador.classList.remove('text-red-600', 'font-bold');
        contador.classList.add('text-gray-400');
    }
});

// Mostrar información del archivo seleccionado
function mostrarNombreArchivo(input) {
    const archivoInfo = document.getElementById('archivo-info');
    const nombreArchivo = document.getElementById('nombre-archivo');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        nombreArchivo.textContent = `${fileName} (${fileSize} MB)`;
        archivoInfo.classList.remove('hidden');
    } else {
        archivoInfo.classList.add('hidden');
    }
}

// Validar tamaño del archivo
document.getElementById('archivo_resolucion').addEventListener('change', function() {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (this.files[0] && this.files[0].size > maxSize) {
        alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
        this.value = '';
        document.getElementById('archivo-info').classList.add('hidden');
    }
});
</script>
@endpush
@endsection