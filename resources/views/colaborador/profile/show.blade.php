{{-- filepath: resources/views/colaborador/profile/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">👤 Mi Perfil</h1>
        <p class="text-gray-600 mt-1">Información personal y configuración de cuenta</p>
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
                    ⚠️ <strong>Completa tu perfil:</strong> Algunos datos están pendientes. Por favor, edita tu perfil para completar tu información.
                </p>
            </div>
            <div class="ml-auto pl-3">
                <a href="{{ route('colaborador.profile.edit') }}" class="text-yellow-700 hover:text-yellow-600 font-medium">
                    Completar ahora →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Información del Usuario -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-20 w-20">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                             alt="{{ $user->name }}"
                             class="h-20 w-20 rounded-full object-cover border-4 border-blue-500">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold border-4 border-blue-500">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @if($persona && $persona->datos_completos)
                        <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Perfil Completo
                        </span>
                    @else
                        <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            ⚠ Perfil Incompleto
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('colaborador.profile.edit') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                ✏️ Editar Perfil
            </a>
        </div>

        <!-- Datos Personales -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Datos Personales</h3>
            
            @if($persona)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipo de Documento</label>
                    <p class="text-gray-900">{{ $persona->tipo_documento ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Número de Documento</label>
                    <p class="text-gray-900">{{ $persona->num_documento ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Nombres</label>
                    <p class="text-gray-900">{{ $persona->nombres ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Apellido Paterno</label>
                    <p class="text-gray-900">{{ $persona->apellido_paterno ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Apellido Materno</label>
                    <p class="text-gray-900">{{ $persona->apellido_materno ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Teléfono</label>
                    <p class="text-gray-900">{{ $persona->telefono ?? 'Sin especificar' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">WhatsApp</label>
                    <p class="text-gray-900">{{ $persona->whatsapp ?? 'Sin especificar' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">Dirección</label>
                    <p class="text-gray-900">{{ $persona->direccion ?? 'Sin especificar' }}</p>
                </div>
            </div>
            @else
            <p class="text-gray-500 italic">No hay datos de persona registrados</p>
            @endif
        </div>
    </div>

    <!-- Cambiar Contraseña -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔒 Cambiar Contraseña</h3>
        
        <form method="POST" action="{{ route('colaborador.profile.update-password') }}" class="max-w-md">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña Actual
                    </label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nueva Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Nueva Contraseña
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit" 
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection