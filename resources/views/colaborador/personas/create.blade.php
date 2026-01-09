{{-- filepath: resources/views/colaborador/personas/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Registrar Nueva Persona')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.personas.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">➕ Registrar Nueva Persona</h1>
                <p class="text-gray-600 mt-1">Complete los datos de la persona</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <form method="POST" action="{{ route('colaborador.personas.store') }}">
        @csrf

        <!-- Datos Personales -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">📋 Datos Personales</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Persona -->
                <div class="md:col-span-2">
                    <label for="tipo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Persona <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo_persona" 
                            name="tipo_persona" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_persona') border-red-500 @enderror">
                        <option value="">Seleccionar</option>
                        <option value="colaborador" {{ old('tipo_persona') == 'colaborador' ? 'selected' : '' }}>👔 Trabajador</option>
                        <option value="cliente" {{ old('tipo_persona') == 'cliente' ? 'selected' : '' }}>👥 Cliente</option>
                    </select>
                    @error('tipo_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo_documento" 
                            name="tipo_documento" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror">
                        <option value="">Seleccionar</option>
                        <option value="DNI" {{ old('tipo_documento') == 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                        <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número Documento -->
                <div>
                    <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="num_documento" 
                           name="num_documento" 
                           value="{{ old('num_documento') }}"
                           required
                           maxlength="20"
                           placeholder="12345678"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('num_documento') border-red-500 @enderror">
                    @error('num_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombres -->
                <div>
                    <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombres" 
                           name="nombres" 
                           value="{{ old('nombres') }}"
                           required
                           maxlength="100"
                           placeholder="Ej: Juan Carlos"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror">
                    @error('nombres')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Paterno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="apellido_paterno" 
                           name="apellido_paterno" 
                           value="{{ old('apellido_paterno') }}"
                           required
                           maxlength="100"
                           placeholder="Ej: Pérez"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_paterno') border-red-500 @enderror">
                    @error('apellido_paterno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Materno
                    </label>
                    <input type="text" 
                           id="apellido_materno" 
                           name="apellido_materno" 
                           value="{{ old('apellido_materno') }}"
                           maxlength="100"
                           placeholder="Ej: García"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_materno') border-red-500 @enderror">
                    @error('apellido_materno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="correo" 
                           name="correo" 
                           value="{{ old('correo') }}"
                           maxlength="100"
                           placeholder="ejemplo@correo.com"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('correo') border-red-500 @enderror">
                    @error('correo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono') }}"
                           maxlength="20"
                           placeholder="999999999"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                        WhatsApp
                    </label>
                    <input type="text" 
                           id="whatsapp" 
                           name="whatsapp" 
                           value="{{ old('whatsapp') }}"
                           maxlength="20"
                           placeholder="999999999"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('whatsapp') border-red-500 @enderror">
                    @error('whatsapp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección
                    </label>
                    <textarea id="direccion" 
                              name="direccion" 
                              rows="2"
                              placeholder="Dirección completa"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion') border-red-500 @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 mb-6">
            <a href="{{ route('colaborador.personas.index') }}" 
               class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                💾 Guardar Persona
            </button>
        </div>
    </form>
</div>
@endsection