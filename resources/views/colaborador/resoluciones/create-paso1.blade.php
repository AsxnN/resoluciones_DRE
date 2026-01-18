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
                        N° Resolución <span class="text-red-500">*</span>
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
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="fecha_resolucion" 
                           name="fecha_resolucion" 
                           value="{{ old('fecha_resolucion', date('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_resolucion') border-red-500 @enderror">
                    @error('fecha_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="id_estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado <span class="text-red-500">*</span>
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
                        Tipo <span class="text-red-500">*</span>
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
                        Dependencia UGEL
                    </label>
                    <select id="id_dependencia" 
                            name="id_dependencia"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sin dependencia</option>
                        @foreach($dependencias as $dependencia)
                            <option value="{{ $dependencia->id_dependencias }}" {{ old('id_dependencia') == $dependencia->id_dependencias ? 'selected' : '' }}>
                                {{ $dependencia->nombre_dependencia }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Card: Personas Relacionadas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">👥 Personas Relacionadas</h2>
                <button type="button" 
                        id="btn-toggle-formulario"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar Persona
                </button>
            </div>

            <!-- Formulario de Persona (Oculto inicialmente) -->
            <div id="formulario-persona" class="hidden mb-6 border-2 border-blue-200 rounded-lg p-6 bg-blue-50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-blue-900">Nueva Persona</h3>
                    <button type="button" 
                            id="btn-cerrar-formulario"
                            class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Selector: Interna / Externa -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Persona</label>
                    <div class="flex gap-4">
                        <button type="button" 
                                id="btn-persona-interna"
                                class="flex-1 px-6 py-4 border-2 border-green-500 bg-green-50 text-green-700 rounded-lg font-semibold hover:bg-green-100 transition-colors">
                            👔 Interna (Trabaja en DRE)
                        </button>
                        <button type="button" 
                                id="btn-persona-externa"
                                class="flex-1 px-6 py-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            🌐 Externa (No trabaja en DRE)
                        </button>
                    </div>
                </div>

                <!-- Formulario Persona INTERNA -->
                <div id="formulario-interna" class="space-y-4">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <p class="text-sm text-green-800">
                            💡 Busque al trabajador por DNI o nombre para agregarlo
                        </p>
                    </div>

                    <!-- Búsqueda -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar por DNI</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       id="buscar_dni_interno"
                                       placeholder="Ingrese DNI"
                                       maxlength="8"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <button type="button" 
                                        id="btn-buscar-dni-interno"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                                    Buscar
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar por Nombre</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       id="buscar_nombre_interno"
                                       placeholder="Ingrese nombre"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <button type="button" 
                                        id="btn-buscar-nombre-interno"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado búsqueda -->
                    <div id="resultado-busqueda-interna" class="hidden"></div>

                    <!-- Tipo de Relación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de Relación <span class="text-red-500">*</span>
                        </label>
                        <select id="tipo_relacion_interna" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="beneficiario">Beneficiario</option>
                            <option value="afectado">Afectado</option>
                            <option value="involucrado">Involucrado</option>
                            <option value="testigo">Testigo</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Relación</label>
                        <input type="text" 
                               id="descripcion_relacion_interna" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               placeholder="Breve descripción (opcional)">
                    </div>
                </div>

                <!-- Formulario Persona EXTERNA -->
                <div id="formulario-externa" class="hidden space-y-4">
                    <!-- Tabs: Manual / RENIEC -->
                    <div class="mb-4">
                        <div class="flex border-b border-gray-300">
                            <button type="button" 
                                    id="tab-manual"
                                    class="tab-button px-6 py-2 font-medium text-blue-600 border-b-2 border-blue-600">
                                ✍️ Manual
                            </button>
                            <button type="button" 
                                    id="tab-reniec"
                                    class="tab-button px-6 py-2 font-medium text-gray-500 hover:text-blue-600">
                                🆔 Consultar RENIEC
                            </button>
                        </div>
                    </div>

                    <!-- Contenido Tab Manual -->
                    <div id="contenido-manual" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Documento</label>
                                <select id="tipo_documento_manual" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="DNI">DNI</option>
                                    <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">N° Documento (opcional)</label>
                                <input type="text" 
                                       id="num_documento_manual"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nombres_manual"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="Nombres">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Apellido Paterno <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="apellido_paterno_manual"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="Apellido Paterno">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                                <input type="text" 
                                       id="apellido_materno_manual"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="Apellido Materno">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Relación <span class="text-red-500">*</span></label>
                                <select id="tipo_relacion_manual" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="beneficiario">Beneficiario</option>
                                    <option value="afectado">Afectado</option>
                                    <option value="involucrado">Involucrado</option>
                                    <option value="testigo">Testigo</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Relación</label>
                            <input type="text" 
                                   id="descripcion_relacion_manual" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Breve descripción (opcional)">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" 
                                    id="btn-agregar-manual"
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                ✓ Agregar Persona Externa
                            </button>
                        </div>
                    </div>

                    <!-- Contenido Tab RENIEC -->
                    <div id="contenido-reniec" class="hidden space-y-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <p class="text-sm text-yellow-800">
                                💡 Ingrese el DNI y los datos se completarán automáticamente desde RENIEC
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="dni_reniec"
                                           maxlength="8"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ingrese DNI de 8 dígitos">
                                    <button type="button" 
                                            id="btn-consultar-reniec"
                                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                        Consultar
                                    </button>
                                </div>
                                <div id="loading-reniec" class="hidden mt-2 text-sm text-blue-600">
                                    ⏳ Consultando RENIEC...
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                                <input type="text" 
                                       id="nombres_reniec"
                                       readonly
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
                                <input type="text" 
                                       id="apellido_paterno_reniec"
                                       readonly
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                                <input type="text" 
                                       id="apellido_materno_reniec"
                                       readonly
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Relación <span class="text-red-500">*</span></label>
                                <select id="tipo_relacion_reniec" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="beneficiario">Beneficiario</option>
                                    <option value="afectado">Afectado</option>
                                    <option value="involucrado">Involucrado</option>
                                    <option value="testigo">Testigo</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Relación</label>
                            <input type="text" 
                                   id="descripcion_relacion_reniec" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Breve descripción (opcional)">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" 
                                    id="btn-agregar-reniec"
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                ✓ Agregar Persona Externa
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla Personas INTERNAS -->
            <div class="mb-6">
                <h3 class="text-md font-semibold text-green-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    👔 Personas Internas (Trabajadores DRE)
                </h3>
                <div class="overflow-x-auto max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Completo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Relación</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-personas-internas" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    No hay personas internas agregadas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Personas EXTERNAS -->
            <div>
                <h3 class="text-md font-semibold text-blue-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    🌐 Personas Externas
                </h3>
                <div class="overflow-x-auto max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Completo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Relación</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Origen</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-personas-externas" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No hay personas externas agregadas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contenedor oculto para inputs de personas -->
        <div id="contenedor-personas-input"></div>

        <!-- Botón Siguiente -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg transition-colors text-lg shadow-lg">
                Siguiente →
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let personasInternas = [];
    let personasExternas = [];
    let tipoPersonaActual = 'interna'; // 'interna' o 'externa'
    let usuarioInternoSeleccionado = null;

    const formularioPersona = document.getElementById('formulario-persona');
    const formularioInterna = document.getElementById('formulario-interna');
    const formularioExterna = document.getElementById('formulario-externa');
    
    // Toggle formulario principal
    document.getElementById('btn-toggle-formulario').addEventListener('click', function() {
        formularioPersona.classList.toggle('hidden');
        if (!formularioPersona.classList.contains('hidden')) {
            // Por defecto mostrar interna
            mostrarFormularioInterna();
        }
    });
    
    document.getElementById('btn-cerrar-formulario').addEventListener('click', function() {
        formularioPersona.classList.add('hidden');
        limpiarFormularios();
    });

    // Botones Interna/Externa
    document.getElementById('btn-persona-interna').addEventListener('click', function() {
        tipoPersonaActual = 'interna';
        mostrarFormularioInterna();
    });

    document.getElementById('btn-persona-externa').addEventListener('click', function() {
        tipoPersonaActual = 'externa';
        mostrarFormularioExterna();
    });

    function mostrarFormularioInterna() {
        document.getElementById('btn-persona-interna').className = 'flex-1 px-6 py-4 border-2 border-green-500 bg-green-50 text-green-700 rounded-lg font-semibold hover:bg-green-100 transition-colors';
        document.getElementById('btn-persona-externa').className = 'flex-1 px-6 py-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors';
        formularioInterna.classList.remove('hidden');
        formularioExterna.classList.add('hidden');
    }

    function mostrarFormularioExterna() {
        document.getElementById('btn-persona-externa').className = 'flex-1 px-6 py-4 border-2 border-blue-500 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition-colors';
        document.getElementById('btn-persona-interna').className = 'flex-1 px-6 py-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors';
        formularioExterna.classList.remove('hidden');
        formularioInterna.classList.add('hidden');
    }

    // ==================== PERSONA INTERNA ====================

    // Buscar por DNI
    document.getElementById('btn-buscar-dni-interno').addEventListener('click', async function() {
        const dni = document.getElementById('buscar_dni_interno').value.trim();
        
        if (dni.length !== 8) {
            alert('Por favor ingrese un DNI válido de 8 dígitos');
            return;
        }

        buscarUsuarioInterno('dni', dni);
    });

    // Buscar por Nombre
    document.getElementById('btn-buscar-nombre-interno').addEventListener('click', async function() {
        const nombre = document.getElementById('buscar_nombre_interno').value.trim();
        
        if (nombre.length < 3) {
            alert('Por favor ingrese al menos 3 caracteres');
            return;
        }

        buscarUsuarioInterno('nombre', nombre);
    });

    async function buscarUsuarioInterno(tipo, valor) {
        try {
            const url = tipo === 'dni' 
                ? `/colaborador/resoluciones/buscar-usuario?dni=${valor}`
                : `/colaborador/resoluciones/buscar-usuario?nombre=${valor}`;

            const response = await fetch(url);
            const data = await response.json();

            if (response.ok) {
                mostrarResultadoInterno(data);
            } else {
                alert(data.message || 'No se encontró el usuario');
                document.getElementById('resultado-busqueda-interna').classList.add('hidden');
            }
        } catch (error) {
            alert('Error al buscar: ' + error.message);
        }
    }

    function mostrarResultadoInterno(usuario) {
        const contenedor = document.getElementById('resultado-busqueda-interna');
        usuarioInternoSeleccionado = usuario;
        
        const html = `
            <div class="bg-white border-2 border-green-300 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                            ${usuario.iniciales || usuario.nombre_completo.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">${usuario.nombre_completo}</p>
                            <p class="text-sm text-gray-600">DNI: ${usuario.num_documento}</p>
                            <p class="text-sm text-green-600 font-medium">✓ Trabajador de la DRE</p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="agregarPersonaInterna()"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                        ✓ Agregar
                    </button>
                </div>
            </div>
        `;
        
        contenedor.innerHTML = html;
        contenedor.classList.remove('hidden');
    }

    window.agregarPersonaInterna = function() {
        if (!usuarioInternoSeleccionado) {
            alert('No hay usuario seleccionado');
            return;
        }

        // Verificar duplicado
        if (personasInternas.some(p => p.num_documento === usuarioInternoSeleccionado.num_documento)) {
            alert('Esta persona ya está en la lista de internas');
            return;
        }

        const persona = {
            id_user: usuarioInternoSeleccionado.id_user,
            num_documento: usuarioInternoSeleccionado.num_documento,
            nombre_completo: usuarioInternoSeleccionado.nombre_completo,
            correo: usuarioInternoSeleccionado.correo,
            tipo_relacion: document.getElementById('tipo_relacion_interna').value,
            descripcion_relacion: document.getElementById('descripcion_relacion_interna').value.trim(),
            es_interna: true
        };

        personasInternas.push(persona);
        actualizarTablaInternas();
        actualizarInputsHidden();
        limpiarFormularios();
        formularioPersona.classList.add('hidden');
        
        alert('✅ Trabajador agregado correctamente');
    };

    // ==================== PERSONA EXTERNA ====================

    // Tabs Manual/RENIEC
    const tabManual = document.getElementById('tab-manual');
    const tabReniec = document.getElementById('tab-reniec');
    const contenidoManual = document.getElementById('contenido-manual');
    const contenidoReniec = document.getElementById('contenido-reniec');

    tabManual.addEventListener('click', function() {
        tabManual.className = 'tab-button px-6 py-2 font-medium text-blue-600 border-b-2 border-blue-600';
        tabReniec.className = 'tab-button px-6 py-2 font-medium text-gray-500 hover:text-blue-600';
        contenidoManual.classList.remove('hidden');
        contenidoReniec.classList.add('hidden');
    });

    tabReniec.addEventListener('click', function() {
        tabReniec.className = 'tab-button px-6 py-2 font-medium text-blue-600 border-b-2 border-blue-600';
        tabManual.className = 'tab-button px-6 py-2 font-medium text-gray-500 hover:text-blue-600';
        contenidoReniec.classList.remove('hidden');
        contenidoManual.classList.add('hidden');
    });

    // Agregar Manual
    document.getElementById('btn-agregar-manual').addEventListener('click', function() {
        const nombres = document.getElementById('nombres_manual').value.trim();
        const apellidoPaterno = document.getElementById('apellido_paterno_manual').value.trim();
        const apellidoMaterno = document.getElementById('apellido_materno_manual').value.trim();
        const tipoDocumento = document.getElementById('tipo_documento_manual').value;
        const numDocumento = document.getElementById('num_documento_manual').value.trim();
        const tipoRelacion = document.getElementById('tipo_relacion_manual').value;
        const descripcionRelacion = document.getElementById('descripcion_relacion_manual').value.trim();

        if (!nombres || !apellidoPaterno) {
            alert('Por favor, complete los campos obligatorios (Nombres, Apellido Paterno)');
            return;
        }

        const docFinal = numDocumento || `TEMP-${Date.now()}`;

        // Verificar duplicado solo si hay documento
        if (numDocumento && personasExternas.some(p => p.num_documento === numDocumento)) {
            alert('Esta persona ya está en la lista de externas');
            return;
        }

        const persona = {
            tipo_documento: tipoDocumento,
            num_documento: docFinal,
            nombres: nombres,
            apellido_paterno: apellidoPaterno,
            apellido_materno: apellidoMaterno,
            tipo_relacion: tipoRelacion,
            descripcion_relacion: descripcionRelacion,
            obtenido_reniec: false,
            es_interna: false
        };

        personasExternas.push(persona);
        actualizarTablaExternas();
        actualizarInputsHidden();
        limpiarFormularios();
        formularioPersona.classList.add('hidden');
        
        alert('✅ Persona externa agregada correctamente');
    });

    // Consultar RENIEC
    document.getElementById('btn-consultar-reniec').addEventListener('click', async function() {
        const dni = document.getElementById('dni_reniec').value.trim();
        const loadingReniec = document.getElementById('loading-reniec');
        
        if (dni.length !== 8 || isNaN(dni)) {
            alert('Por favor, ingrese un DNI válido de 8 dígitos');
            return;
        }

        loadingReniec.classList.remove('hidden');
        this.disabled = true;
        
        try {
            const response = await fetch('{{ route("colaborador.reniec.consultar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ dni: dni })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('nombres_reniec').value = data.nombres || '';
                document.getElementById('apellido_paterno_reniec').value = data.apellido_paterno || '';
                document.getElementById('apellido_materno_reniec').value = data.apellido_materno || '';
                
                loadingReniec.innerHTML = '✅ Datos obtenidos correctamente';
                loadingReniec.classList.add('text-green-600');
                loadingReniec.classList.remove('text-blue-600');
            } else {
                alert(data.message || 'No se encontraron datos en RENIEC');
                document.getElementById('nombres_reniec').value = '';
                document.getElementById('apellido_paterno_reniec').value = '';
                document.getElementById('apellido_materno_reniec').value = '';
            }
        } catch (error) {
            alert('Error al consultar RENIEC. Por favor, intente nuevamente.');
        } finally {
            setTimeout(() => {
                loadingReniec.classList.add('hidden');
                loadingReniec.innerHTML = '⏳ Consultando RENIEC...';
                loadingReniec.classList.remove('text-green-600');
                loadingReniec.classList.add('text-blue-600');
            }, 2000);
            this.disabled = false;
        }
    });

    // Agregar RENIEC
    document.getElementById('btn-agregar-reniec').addEventListener('click', function() {
        const dni = document.getElementById('dni_reniec').value.trim();
        const nombres = document.getElementById('nombres_reniec').value.trim();
        const apellidoPaterno = document.getElementById('apellido_paterno_reniec').value.trim();
        const apellidoMaterno = document.getElementById('apellido_materno_reniec').value.trim();
        const tipoRelacion = document.getElementById('tipo_relacion_reniec').value;
        const descripcionRelacion = document.getElementById('descripcion_relacion_reniec').value.trim();

        if (!dni || !nombres || !apellidoPaterno) {
            alert('Por favor, consulte el DNI primero y asegúrese de que los datos estén completos');
            return;
        }

        // Verificar duplicado
        if (personasExternas.some(p => p.num_documento === dni)) {
            alert('Esta persona ya está en la lista de externas');
            return;
        }

        const persona = {
            tipo_documento: 'DNI',
            num_documento: dni,
            nombres: nombres,
            apellido_paterno: apellidoPaterno,
            apellido_materno: apellidoMaterno,
            tipo_relacion: tipoRelacion,
            descripcion_relacion: descripcionRelacion,
            obtenido_reniec: true,
            es_interna: false
        };

        personasExternas.push(persona);
        actualizarTablaExternas();
        actualizarInputsHidden();
        limpiarFormularios();
        formularioPersona.classList.add('hidden');
        
        alert('✅ Persona externa agregada correctamente desde RENIEC');
    });

    // ==================== ACTUALIZAR TABLAS ====================

    function actualizarTablaInternas() {
        const tabla = document.getElementById('tabla-personas-internas');
        
        if (personasInternas.length === 0) {
            tabla.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No hay personas internas agregadas</td></tr>';
            return;
        }

        let html = '';
        personasInternas.forEach((persona, index) => {
            const badgeRelacion = {
                'beneficiario': 'bg-green-100 text-green-800',
                'afectado': 'bg-red-100 text-red-800',
                'involucrado': 'bg-blue-100 text-blue-800',
                'testigo': 'bg-yellow-100 text-yellow-800',
                'otro': 'bg-gray-100 text-gray-800'
            };

            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${persona.num_documento}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="font-medium">${persona.nombre_completo}</div>
                        ${persona.descripcion_relacion ? `<div class="text-xs text-gray-500">${persona.descripcion_relacion}</div>` : ''}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${badgeRelacion[persona.tipo_relacion] || 'bg-gray-100 text-gray-800'}">
                            ${persona.tipo_relacion.charAt(0).toUpperCase() + persona.tipo_relacion.slice(1)}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button type="button" 
                                onclick="eliminarPersonaInterna(${index})"
                                class="text-red-600 hover:text-red-800 font-medium">
                            🗑️ Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });

        tabla.innerHTML = html;
    }

    function actualizarTablaExternas() {
        const tabla = document.getElementById('tabla-personas-externas');
        
        if (personasExternas.length === 0) {
            tabla.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay personas externas agregadas</td></tr>';
            return;
        }

        let html = '';
        personasExternas.forEach((persona, index) => {
            const nombreCompleto = `${persona.nombres} ${persona.apellido_paterno} ${persona.apellido_materno}`.trim();
            const badgeRelacion = {
                'beneficiario': 'bg-green-100 text-green-800',
                'afectado': 'bg-red-100 text-red-800',
                'involucrado': 'bg-blue-100 text-blue-800',
                'testigo': 'bg-yellow-100 text-yellow-800',
                'otro': 'bg-gray-100 text-gray-800'
            };

            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="font-medium">${persona.tipo_documento}</div>
                        <div class="text-gray-500">${persona.num_documento}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="font-medium">${nombreCompleto}</div>
                        ${persona.descripcion_relacion ? `<div class="text-xs text-gray-500">${persona.descripcion_relacion}</div>` : ''}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${badgeRelacion[persona.tipo_relacion] || 'bg-gray-100 text-gray-800'}">
                            ${persona.tipo_relacion.charAt(0).toUpperCase() + persona.tipo_relacion.slice(1)}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        ${persona.obtenido_reniec ? '<span class="text-blue-600 font-medium">🆔 RENIEC</span>' : '<span class="text-gray-600">✍️ Manual</span>'}
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button type="button" 
                                onclick="eliminarPersonaExterna(${index})"
                                class="text-red-600 hover:text-red-800 font-medium">
                            🗑️ Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });

        tabla.innerHTML = html;
    }

    // Actualizar inputs hidden
    function actualizarInputsHidden() {
        const contenedor = document.getElementById('contenedor-personas-input');
        contenedor.innerHTML = '';

        // Personas Internas
        personasInternas.forEach((persona, index) => {
            const fields = ['id_user', 'num_documento', 'nombre_completo', 'correo', 'tipo_relacion', 'descripcion_relacion', 'es_interna'];
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `personas_internas[${index}][${field}]`;
                // Manejar valores booleanos correctamente
                input.value = persona[field] !== undefined && persona[field] !== null ? persona[field] : '';
                contenedor.appendChild(input);
            });
        });

        // Personas Externas
        personasExternas.forEach((persona, index) => {
            const fields = ['tipo_documento', 'num_documento', 'nombres', 'apellido_paterno', 'apellido_materno', 'tipo_relacion', 'descripcion_relacion', 'obtenido_reniec', 'es_interna'];
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `personas_externas[${index}][${field}]`;
                // Manejar valores booleanos correctamente
                input.value = persona[field] !== undefined && persona[field] !== null ? persona[field] : '';
                contenedor.appendChild(input);
            });
        });

        console.log('✅ Inputs actualizados:', personasInternas.length, 'internas,', personasExternas.length, 'externas');
    }

    // Limpiar formularios
    function limpiarFormularios() {
        // Interna
        document.getElementById('buscar_dni_interno').value = '';
        document.getElementById('buscar_nombre_interno').value = '';
        document.getElementById('tipo_relacion_interna').value = 'beneficiario';
        document.getElementById('descripcion_relacion_interna').value = '';
        document.getElementById('resultado-busqueda-interna').classList.add('hidden');
        usuarioInternoSeleccionado = null;

        // Externa - Manual
        document.getElementById('tipo_documento_manual').value = 'DNI';
        document.getElementById('num_documento_manual').value = '';
        document.getElementById('nombres_manual').value = '';
        document.getElementById('apellido_paterno_manual').value = '';
        document.getElementById('apellido_materno_manual').value = '';
        document.getElementById('tipo_relacion_manual').value = 'beneficiario';
        document.getElementById('descripcion_relacion_manual').value = '';

        // Externa - RENIEC
        document.getElementById('dni_reniec').value = '';
        document.getElementById('nombres_reniec').value = '';
        document.getElementById('apellido_paterno_reniec').value = '';
        document.getElementById('apellido_materno_reniec').value = '';
        document.getElementById('tipo_relacion_reniec').value = 'beneficiario';
        document.getElementById('descripcion_relacion_reniec').value = '';
    }

    // Funciones globales para eliminar
    window.eliminarPersonaInterna = function(index) {
        if (confirm('¿Está seguro de eliminar este trabajador?')) {
            personasInternas.splice(index, 1);
            actualizarTablaInternas();
            actualizarInputsHidden();
        }
    };

    window.eliminarPersonaExterna = function(index) {
        if (confirm('¿Está seguro de eliminar esta persona?')) {
            personasExternas.splice(index, 1);
            actualizarTablaExternas();
            actualizarInputsHidden();
        }
    };
});
</script>
@endpush
@endsection