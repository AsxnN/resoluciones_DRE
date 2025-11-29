{{-- filepath: resources/views/colaborador/usuarios/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Usuario')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.usuarios.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">➕ Crear Nuevo Usuario</h1>
                <p class="text-gray-600 mt-1">Complete el formulario para registrar un nuevo usuario</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.usuarios.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Datos de Acceso -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Datos de Acceso</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de Usuario <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Ej: Juan Pérez"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="usuario@drehuanuco.gob.pe"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                                DNI
                            </label>
                            <input type="text" 
                                   id="dni" 
                                   name="dni" 
                                   value="{{ old('dni') }}"
                                   maxlength="8"
                                   pattern="[0-9]{8}"
                                   placeholder="12345678"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('dni') border-red-500 @enderror">
                            @error('dni')
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
                                   placeholder="999999999"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   minlength="8"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   minlength="8"
                                   placeholder="Repetir contraseña"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Asociar Persona -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Asociar con Persona</h3>
                    
                    <div>
                        <label for="id_persona" class="block text-sm font-medium text-gray-700 mb-2">
                            Persona (Opcional)
                        </label>
                        <select id="id_persona" 
                                name="id_persona"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('id_persona') border-red-500 @enderror">
                            <option value="">Sin asociar</option>
                            @foreach($personas as $persona)
                            <option value="{{ $persona->id_persona }}" {{ old('id_persona') == $persona->id_persona ? 'selected' : '' }}>
                                {{ $persona->dni_persona }} - {{ $persona->nombres_persona }} {{ $persona->apellido_paterno_persona }} {{ $persona->apellido_materno_persona }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_persona')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Vincular el usuario con una persona registrada en el sistema</p>
                    </div>
                </div>

                <!-- Roles y Permisos -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔑 Roles y Permisos</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Asignar Roles <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($roles as $role)
                            <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           id="role_{{ $role->id }}" 
                                           name="roles[]" 
                                           value="{{ $role->name }}"
                                           {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                                </div>
                                <div class="ml-3">
                                    <label for="role_{{ $role->id }}" class="font-medium text-gray-900 cursor-pointer">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        @if($role->name === 'admin')
                                            Acceso total al sistema y gestión de usuarios
                                        @elseif($role->name === 'colaborador')
                                            Acceso para gestión de resoluciones y personal
                                        @elseif($role->name === 'director')
                                            Acceso para aprobación y firma de resoluciones
                                        @else
                                            {{ $role->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado y Configuración -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Configuración</h3>
                    
                    <div class="space-y-4">
                        <!-- Usuario Activo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   value="1"
                                   {{ old('activo', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                            <label for="activo" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Usuario Activo</span>
                                <p class="text-xs text-gray-500">El usuario podrá acceder al sistema</p>
                            </label>
                        </div>

                        <!-- Email Verificado -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   id="email_verified" 
                                   name="email_verified" 
                                   value="1"
                                   {{ old('email_verified', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500 h-5 w-5">
                            <label for="email_verified" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Email Verificado</span>
                                <p class="text-xs text-gray-500">Marcar el correo como verificado automáticamente</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.usuarios.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Crear Usuario
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Información sobre Usuarios</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>La contraseña debe tener al menos 8 caracteres</li>
                        <li>Es recomendable asociar el usuario con una persona del sistema</li>
                        <li>Puede asignar múltiples roles a un mismo usuario</li>
                        <li>Los usuarios inactivos no podrán acceder al sistema</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection