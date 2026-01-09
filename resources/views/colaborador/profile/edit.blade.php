{{-- filepath: resources/views/colaborador/profile/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.profile.show') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Mi Perfil</h1>
                <p class="text-gray-600 mt-1">Actualiza tu información personal</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <form method="POST" action="{{ route('colaborador.profile.update') }}">
        @csrf
        @method('PUT')

        <!-- Datos de Usuario -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                👤 Datos de Cuenta
            </h3>

            <div class="grid grid-cols-1 gap-6">
                <!-- Nombre Completo (readonly, se genera automáticamente) -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-gray-500">(Se genera automáticamente)</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           value="{{ old('nombres', $persona->nombres ?? '') }} {{ old('apellido_paterno', $persona->apellido_paterno ?? '') }} {{ old('apellido_materno', $persona->apellido_materno ?? '') }} "
                           readonly
                           class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Este campo se construye automáticamente con: Nombres + Apellido Paterno + Apellido Materno</p>
                </div>

                <!-- Email -->
                <div>
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
        @if($persona)
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                📋 Datos Personales
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo_documento" 
                            name="tipo_documento" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror">
                        <option value="">Seleccionar</option>
                        <option value="DNI" {{ old('tipo_documento', $persona->tipo_documento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="CE" {{ old('tipo_documento', $persona->tipo_documento) == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                        <option value="PASAPORTE" {{ old('tipo_documento', $persona->tipo_documento) == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="num_documento" 
                           name="num_documento" 
                           value="{{ old('num_documento', $persona->num_documento) }}"
                           required
                           maxlength="20"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('num_documento') border-red-500 @enderror">
                    @error('num_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombres" 
                           name="nombres" 
                           value="{{ old('nombres', $persona->nombres) }}"
                           required
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror">
                    @error('nombres')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Paterno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="apellido_paterno" 
                           name="apellido_paterno" 
                           value="{{ old('apellido_paterno', $persona->apellido_paterno) }}"
                           required
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_paterno') border-red-500 @enderror">
                    @error('apellido_paterno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Materno
                    </label>
                    <input type="text" 
                           id="apellido_materno" 
                           name="apellido_materno" 
                           value="{{ old('apellido_materno', $persona->apellido_materno) }}"
                           maxlength="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_materno') border-red-500 @enderror">
                    @error('apellido_materno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono', $persona->telefono) }}"
                           maxlength="20"
                           placeholder="999999999"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                        WhatsApp
                    </label>
                    <input type="text" 
                           id="whatsapp" 
                           name="whatsapp" 
                           value="{{ old('whatsapp', $persona->whatsapp) }}"
                           maxlength="20"
                           placeholder="999999999"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('whatsapp') border-red-500 @enderror">
                    @error('whatsapp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección
                    </label>
                    <textarea id="direccion" 
                              name="direccion" 
                              rows="2"
                              placeholder="Dirección completa"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion') border-red-500 @enderror">{{ old('direccion', $persona->direccion) }}</textarea>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Botones -->
        <div class="flex justify-end gap-4 mb-6">
            <a href="{{ route('colaborador.profile.show') }}" 
               class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                💾 Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection