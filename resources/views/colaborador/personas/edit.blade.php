{{-- filepath: resources/views/colaborador/personas/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Persona')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Persona</h1>
                <p class="text-gray-600 mt-1">Modificar datos de: <strong>{{ $persona->nombres_persona }} {{ $persona->apellido_paterno_persona }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <form method="POST" action="{{ route('colaborador.personas.update', $persona) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Foto Actual -->
        @if($persona->foto_persona)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . $persona->foto_persona) }}" 
                     alt="{{ $persona->nombres_persona }}"
                     class="w-24 h-24 rounded-full object-cover border-4 border-blue-500">
                <div>
                    <h3 class="font-semibold text-gray-900">Fotografía Actual</h3>
                    <p class="text-sm text-gray-500">Puede cargar una nueva foto si desea actualizar</p>
                </div>
            </div>
        </div>
        @endif

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
                <!-- DNI -->
                <div>
                    <label for="dni_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        DNI <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="dni_persona" 
                           name="dni_persona" 
                           value="{{ old('dni_persona', $persona->dni_persona) }}"
                           required
                           maxlength="8"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('dni_persona') border-red-500 @enderror">
                    @error('dni_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombres -->
                <div>
                    <label for="nombres_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombres_persona" 
                           name="nombres_persona" 
                           value="{{ old('nombres_persona', $persona->nombres_persona) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombres_persona') border-red-500 @enderror">
                    @error('nombres_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="apellido_paterno_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Paterno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="apellido_paterno_persona" 
                           name="apellido_paterno_persona" 
                           value="{{ old('apellido_paterno_persona', $persona->apellido_paterno_persona) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_paterno_persona') border-red-500 @enderror">
                    @error('apellido_paterno_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="apellido_materno_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido Materno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="apellido_materno_persona" 
                           name="apellido_materno_persona" 
                           value="{{ old('apellido_materno_persona', $persona->apellido_materno_persona) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_materno_persona') border-red-500 @enderror">
                    @error('apellido_materno_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sexo -->
                <div>
                    <label for="sexo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Sexo <span class="text-red-500">*</span>
                    </label>
                    <select id="sexo_persona" 
                            name="sexo_persona" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('sexo_persona') border-red-500 @enderror">
                        <option value="">Seleccionar</option>
                        <option value="M" {{ old('sexo_persona', $persona->sexo_persona) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo_persona', $persona->sexo_persona) == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Nacimiento -->
                <div>
                    <label for="fecha_nacimiento_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Nacimiento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="fecha_nacimiento_persona" 
                           name="fecha_nacimiento_persona" 
                           value="{{ old('fecha_nacimiento_persona', $persona->fecha_nacimiento_persona) }}"
                           required
                           max="{{ date('Y-m-d') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('fecha_nacimiento_persona') border-red-500 @enderror">
                    @error('fecha_nacimiento_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email_persona" 
                           name="email_persona" 
                           value="{{ old('email_persona', $persona->email_persona) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email_persona') border-red-500 @enderror">
                    @error('email_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           id="telefono_persona" 
                           name="telefono_persona" 
                           value="{{ old('telefono_persona', $persona->telefono_persona) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono_persona') border-red-500 @enderror">
                    @error('telefono_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="direccion_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección
                    </label>
                    <input type="text" 
                           id="direccion_persona" 
                           name="direccion_persona" 
                           value="{{ old('direccion_persona', $persona->direccion_persona) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion_persona') border-red-500 @enderror">
                    @error('direccion_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Datos Laborales -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">💼 Datos Laborales</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Personal -->
                <div>
                    <label for="id_tipo_personal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Personal <span class="text-red-500">*</span>
                    </label>
                    <select id="id_tipo_personal" 
                            name="id_tipo_personal" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('id_tipo_personal') border-red-500 @enderror">
                        <option value="">Seleccionar tipo</option>
                        @foreach($tiposPersonal as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('id_tipo_personal', $persona->id_tipo_personal) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_tipo_personal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="id_direccion" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <select id="id_direccion" 
                            name="id_direccion" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('id_direccion') border-red-500 @enderror">
                        <option value="">Seleccionar dirección</option>
                        @foreach($direcciones as $direccion)
                        <option value="{{ $direccion->id_direccion }}" {{ old('id_direccion', $persona->id_direccion) == $direccion->id_direccion ? 'selected' : '' }}>
                            {{ $direccion->nombre_direccion }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Especialidad -->
                <div>
                    <label for="id_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Especialidad <span class="text-red-500">*</span>
                    </label>
                    <select id="id_especialidad" 
                            name="id_especialidad" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('id_especialidad') border-red-500 @enderror">
                        <option value="">Seleccionar especialidad</option>
                        @foreach($especialidades as $especialidad)
                        <option value="{{ $especialidad->id_especialidad }}" {{ old('id_especialidad', $persona->id_especialidad) == $especialidad->id_especialidad ? 'selected' : '' }}>
                            {{ $especialidad->nombre_especialidad }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_especialidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cargo -->
                <div>
                    <label for="cargo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="cargo_persona" 
                           name="cargo_persona" 
                           value="{{ old('cargo_persona', $persona->cargo_persona) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('cargo_persona') border-red-500 @enderror">
                    @error('cargo_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Ingreso -->
                <div>
                    <label for="fecha_ingreso_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Ingreso <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="fecha_ingreso_persona" 
                           name="fecha_ingreso_persona" 
                           value="{{ old('fecha_ingreso_persona', $persona->fecha_ingreso_persona) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('fecha_ingreso_persona') border-red-500 @enderror">
                    @error('fecha_ingreso_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nivel Educativo -->
                <div>
                    <label for="nivel_educativo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Nivel Educativo
                    </label>
                    <select id="nivel_educativo_persona" 
                            name="nivel_educativo_persona"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nivel_educativo_persona') border-red-500 @enderror">
                        <option value="">Seleccionar nivel</option>
                        <option value="secundaria" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'secundaria' ? 'selected' : '' }}>Secundaria</option>
                        <option value="tecnico" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="bachiller" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'bachiller' ? 'selected' : '' }}>Bachiller</option>
                        <option value="licenciatura" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'licenciatura' ? 'selected' : '' }}>Licenciatura</option>
                        <option value="maestria" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'maestria' ? 'selected' : '' }}>Maestría</option>
                        <option value="doctorado" {{ old('nivel_educativo_persona', $persona->nivel_educativo_persona) == 'doctorado' ? 'selected' : '' }}>Doctorado</option>
                    </select>
                    @error('nivel_educativo_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Datos Adicionales -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">📄 Datos Adicionales</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- RUC -->
                <div>
                    <label for="ruc_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        RUC
                    </label>
                    <input type="text" 
                           id="ruc_persona" 
                           name="ruc_persona" 
                           value="{{ old('ruc_persona', $persona->ruc_persona) }}"
                           maxlength="11"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('ruc_persona') border-red-500 @enderror">
                    @error('ruc_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado Civil -->
                <div>
                    <label for="estado_civil_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado Civil
                    </label>
                    <select id="estado_civil_persona" 
                            name="estado_civil_persona"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('estado_civil_persona') border-red-500 @enderror">
                        <option value="">Seleccionar</option>
                        <option value="soltero" {{ old('estado_civil_persona', $persona->estado_civil_persona) == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                        <option value="casado" {{ old('estado_civil_persona', $persona->estado_civil_persona) == 'casado' ? 'selected' : '' }}>Casado/a</option>
                        <option value="viudo" {{ old('estado_civil_persona', $persona->estado_civil_persona) == 'viudo' ? 'selected' : '' }}>Viudo/a</option>
                        <option value="divorciado" {{ old('estado_civil_persona', $persona->estado_civil_persona) == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                        <option value="conviviente" {{ old('estado_civil_persona', $persona->estado_civil_persona) == 'conviviente' ? 'selected' : '' }}>Conviviente</option>
                    </select>
                    @error('estado_civil_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="md:col-span-2">
                    <label for="foto_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Nueva Fotografía
                    </label>
                    <input type="file" 
                           id="foto_persona" 
                           name="foto_persona" 
                           accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('foto_persona') border-red-500 @enderror">
                    @error('foto_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Deje en blanco si no desea cambiar la foto actual</p>
                </div>

                <!-- Observaciones -->
                <div class="md:col-span-2">
                    <label for="observaciones_persona" class="block text-sm font-medium text-gray-700 mb-2">
                        Observaciones
                    </label>
                    <textarea id="observaciones_persona" 
                              name="observaciones_persona" 
                              rows="3"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('observaciones_persona') border-red-500 @enderror">{{ old('observaciones_persona', $persona->observaciones_persona) }}</textarea>
                    @error('observaciones_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="md:col-span-2">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <input type="checkbox" 
                               id="activo" 
                               name="activo" 
                               value="1"
                               {{ old('activo', $persona->activo) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                        <label for="activo" class="ml-3">
                            <span class="text-sm font-medium text-gray-700">Persona Activa</span>
                            <p class="text-xs text-gray-500">La persona estará disponible en el sistema</p>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Resoluciones -->
        @if($persona->resoluciones_count > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Esta persona tiene <strong>{{ $persona->resoluciones_count }}</strong> resoluciones asociadas.</p>
                        <p class="mt-1">Los cambios en los datos pueden afectar la información de las resoluciones existentes.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Botones -->
        <div class="flex justify-end gap-4 mb-6">
            <a href="{{ route('colaborador.personas.index') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                ❌ Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                ✓ Actualizar Persona
            </button>
        </div>
    </form>
</div>
@endsection