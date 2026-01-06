{{-- filepath: resources/views/colaborador/resoluciones/create-paso1.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Nueva Resolución - Paso 1')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">RESOLUCIONES</h1>
                <p class="text-gray-600 mt-1">Paso 1 de 3: Datos básicos y personas relacionadas</p>
            </div>
        </div>
    </div>

    <!-- Indicador de pasos -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        1
                    </div>
                    <span class="text-sm font-medium text-blue-600 mt-2">Datos Básicos</span>
                </div>
                <div class="w-24 h-1 bg-gray-300"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-lg">
                        2
                    </div>
                    <span class="text-sm font-medium text-gray-500 mt-2">Contenido</span>
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

    <form method="POST" action="{{ route('colaborador.resoluciones.store-paso1') }}" class="space-y-6">
        @csrf

        <!-- Card: Información Básica -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- N° Resolución -->
                <div>
                    <label for="num_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        N° RESOLUCIÓN <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="num_resolucion" 
                           name="num_resolucion" 
                           value="{{ old('num_resolucion') }}"
                           required
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('num_resolucion') border-red-500 @enderror"
                           placeholder="RD-001-2025">
                    @error('num_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Resolución -->
                <div>
                    <label for="fecha_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        FECHA DE RESOLUCIÓN <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="fecha_resolucion" 
                           name="fecha_resolucion" 
                           value="{{ old('fecha_resolucion', now()->format('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_resolucion') border-red-500 @enderror">
                    @error('fecha_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="id_estado" class="block text-sm font-medium text-gray-700 mb-2">
                        ESTADO <span class="text-red-500">*</span>
                    </label>
                    <select id="id_estado" 
                            name="id_estado" 
                            required
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_estado') border-red-500 @enderror">
                        <option value="">Seleccione...</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ old('id_estado') == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Resolución -->
                <div>
                    <label for="id_tipo_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        TIPO DE RESOLUCIÓN <span class="text-red-500">*</span>
                    </label>
                    <select id="id_tipo_resolucion" 
                            name="id_tipo_resolucion" 
                            required
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_tipo_resolucion') border-red-500 @enderror">
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

                <!-- Dependencia UGEL -->
                <div class="md:col-span-2">
                    <label for="id_dependencia" class="block text-sm font-medium text-gray-700 mb-2">
                        DEPENDENCIA-UGEL
                    </label>
                    <select id="id_dependencia" 
                            name="id_dependencia"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione una dependencia...</option>
                        @foreach($dependencias as $dependencia)
                            <option value="{{ $dependencia->id_dependencias }}" {{ old('id_dependencia') == $dependencia->id_dependencias ? 'selected' : '' }}>
                                {{ $dependencia->nombre_dependencia }} ({{ $dependencia->cod_dependencia }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Card: Relacionar Personas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-gray-800 text-white px-4 py-3 rounded-t-lg -mx-6 -mt-6 mb-6">
                <h2 class="text-lg font-semibold">RELACIONAR</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Selector de personas -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        PERSONA
                    </label>
                    <select id="persona-select" 
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-4">
                        <option value="">Seleccione opción</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id_persona }}" 
                                    data-nombre="{{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}, {{ $persona->nombres }}"
                                    data-dni="{{ $persona->num_documento }}"
                                    data-telefono="{{ $persona->telefono ?? 'N/A' }}"
                                    data-email="{{ $persona->correo ?? 'N/A' }}">
                                {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}, {{ $persona->nombres }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" 
                           id="buscar-dni"
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-4"
                           placeholder="Buscar por DNI">

                    <button type="button" 
                            id="btn-agregar"
                            class="w-full px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors">
                        Agregar
                    </button>
                </div>

                <!-- Tabla de personas relacionadas -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        PERSONAS RELACIONADAS
                    </label>
                    <div class="bg-gray-50 rounded-lg border border-gray-300 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Id</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Persona</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">DNI</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Teléfono</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-personas" class="bg-white divide-y divide-gray-200">
                                <tr id="mensaje-vacio">
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        No hay personas relacionadas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor oculto para inputs de personas -->
        <div id="contenedor-personas-input"></div>

        <!-- Botón Siguiente -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg transition-colors text-lg shadow-lg">
                Siguiente
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let personasRelacionadas = [];
    const tablaPersonas = document.getElementById('tabla-personas');
    const btnAgregar = document.getElementById('btn-agregar');
    const personaSelect = document.getElementById('persona-select');
    const buscarDni = document.getElementById('buscar-dni');
    const contenedorInputs = document.getElementById('contenedor-personas-input');

    // Función para agregar persona
    btnAgregar.addEventListener('click', function() {
        const selectedOption = personaSelect.options[personaSelect.selectedIndex];
        
        if (!selectedOption.value) {
            alert('Por favor, seleccione una persona');
            return;
        }

        const persona = {
            id: selectedOption.value,
            nombre: selectedOption.dataset.nombre,
            dni: selectedOption.dataset.dni,
            telefono: selectedOption.dataset.telefono || 'N/A',
            email: selectedOption.dataset.email || 'N/A'
        };

        // Verificar si ya está agregada
        if (personasRelacionadas.some(p => p.id === persona.id)) {
            alert('Esta persona ya está en la lista');
            return;
        }

        personasRelacionadas.push(persona);
        actualizarTabla();
        actualizarInputsHidden();
        personaSelect.value = '';
    });

    // Buscar por DNI
    buscarDni.addEventListener('input', function() {
        const dni = this.value.toLowerCase();
        const options = personaSelect.options;

        for (let i = 1; i < options.length; i++) {
            const option = options[i];
            const optionDni = option.dataset.dni.toLowerCase();
            
            if (dni && optionDni.includes(dni)) {
                personaSelect.value = option.value;
                break;
            }
        }
    });

    // Función para actualizar tabla
    function actualizarTabla() {
        if (personasRelacionadas.length === 0) {
            tablaPersonas.innerHTML = '<tr id="mensaje-vacio"><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay personas relacionadas</td></tr>';
            return;
        }

        let html = '';
        personasRelacionadas.forEach((persona, index) => {
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">${index + 1}</td>
                    <td class="px-4 py-3 text-sm">${persona.nombre}</td>
                    <td class="px-4 py-3 text-sm">${persona.dni}</td>
                    <td class="px-4 py-3 text-sm">${persona.telefono}</td>
                    <td class="px-4 py-3 text-sm">${persona.email}</td>
                    <td class="px-4 py-3 text-center">
                        <button type="button" 
                                onclick="eliminarPersona(${index})"
                                class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });

        tablaPersonas.innerHTML = html;
    }

    // Función para actualizar inputs hidden
    function actualizarInputsHidden() {
        // Limpiar contenedor
        contenedorInputs.innerHTML = '';

        // Crear inputs hidden para cada persona
        personasRelacionadas.forEach((persona, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `personas_relacionadas[${index}][id_persona]`;
            input.value = persona.id;
            contenedorInputs.appendChild(input);
        });

        console.log('✅ Inputs hidden actualizados:', personasRelacionadas.length, 'personas');
        console.log('📋 Datos:', personasRelacionadas);
    }

    // Función global para eliminar persona
    window.eliminarPersona = function(index) {
        if (confirm('¿Está seguro de eliminar esta persona?')) {
            personasRelacionadas.splice(index, 1);
            actualizarTabla();
            actualizarInputsHidden();
        }
    };
});
</script>
@endpush
@endsection