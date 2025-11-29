{{-- filepath: resources/views/colaborador/dependencias/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Dependencia')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.dependencias.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Dependencia</h1>
                <p class="text-gray-600 mt-1">Modificar información de: <strong>{{ $dependencia->nombre_dependencia }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.dependencias.update', $dependencia) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nombre de la Dependencia -->
                <div>
                    <label for="nombre_dependencia" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Dependencia <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombre_dependencia" 
                           name="nombre_dependencia" 
                           value="{{ old('nombre_dependencia', $dependencia->nombre_dependencia) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombre_dependencia') border-red-500 @enderror">
                    @error('nombre_dependencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Siglas -->
                <div>
                    <label for="siglas_dependencia" class="block text-sm font-medium text-gray-700 mb-2">
                        Siglas de la Dependencia
                    </label>
                    <input type="text" 
                           id="siglas_dependencia" 
                           name="siglas_dependencia" 
                           value="{{ old('siglas_dependencia', $dependencia->siglas_dependencia) }}"
                           maxlength="20"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('siglas_dependencia') border-red-500 @enderror">
                    @error('siglas_dependencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion_dependencia" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción de la Dependencia
                    </label>
                    <textarea id="descripcion_dependencia" 
                              name="descripcion_dependencia" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('descripcion_dependencia') border-red-500 @enderror">{{ old('descripcion_dependencia', $dependencia->descripcion_dependencia) }}</textarea>
                    @error('descripcion_dependencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección Física
                    </label>
                    <input type="text" 
                           id="direccion" 
                           name="direccion" 
                           value="{{ old('direccion', $dependencia->direccion) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono de Contacto
                    </label>
                    <input type="text" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono', $dependencia->telefono) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $dependencia->email) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" 
                           id="activo" 
                           name="activo" 
                           value="1"
                           {{ old('activo', $dependencia->activo) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <label for="activo" class="ml-3">
                        <span class="text-sm font-medium text-gray-700">Dependencia Activa</span>
                        <p class="text-xs text-gray-500">La dependencia estará disponible en el sistema</p>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.dependencias.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Actualizar Dependencia
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Los cambios en esta dependencia pueden afectar a:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>{{ $dependencia->resoluciones_count ?? 0 }} Resoluciones asociadas</li>
                        <li>{{ $dependencia->direcciones_count ?? 0 }} Direcciones vinculadas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection