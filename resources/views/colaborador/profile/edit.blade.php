{{-- filepath: resources/views/colaborador/profile/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.profile.show') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Perfil</h1>
                <p class="text-gray-600 mt-1">Actualiza tu información personal y laboral</p>
            </div>
        </div>
    </div>

    <!-- Alerta de datos incompletos -->
    @if($persona && !$persona->datos_completos)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    ⚠️ <strong>Completa todos los campos obligatorios</strong> para acceder a todas las funcionalidades del sistema.
                </p>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('colaborador.profile.update') }}">
        @csrf
        @method('PUT')

        <!-- Datos de Usuario -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Datos de Usuario
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Datos Personales
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo_documento" 
                            name="tipo_documento" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror">
                        <option value="">Seleccione...</option>
                        <option value="DNI" {{ old('tipo_documento', $persona->tipo_documento ?? '') == 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="CE" {{ old('tipo_documento', $persona->tipo_documento ?? '') == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                        <option value="PASAPORTE" {{ old('tipo_documento', $persona->tipo_documento ?? '') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de Documento -->
                <div>
                    <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="num_documento" 
                           name="num_documento" 
                           value="{{ old('num_documento', $persona->num_documento ?? '') }}"
                           required
                           maxlength="20"
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
                           value="{{ old('nombres', $persona->nombres ?? '') }}"
                           required
                           maxlength="100"
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
                           value="{{ old('apellido_paterno', $persona->apellido_paterno ?? '') }}"
                           required
                           maxlength="100"
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
                           value="{{ old('apellido_materno', $persona->apellido_materno ?? '') }}"
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_materno') border-red-500 @enderror">
                    @error('apellido_materno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono', $persona->telefono ?? '') }}"
                           required
                           maxlength="20"
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
                           value="{{ old('whatsapp', $persona->whatsapp ?? '') }}"
                           maxlength="20"
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
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion') border-red-500 @enderror">{{ old('direccion', $persona->direccion ?? '') }}</textarea>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>



        <!-- Botones -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('colaborador.profile.show') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                ❌ Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                ✓ Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection