{{-- filepath: resources/views/colaborador/registro-firma-entrega/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Solicitar Firma para Entrega')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Solicitar Firma para Entrega
                </h1>
                <p class="text-gray-600 mt-1">Crear registro de firma para entregar a persona externa</p>
            </div>
        </div>
    </div>

    <!-- Información de la Resolución -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Resolución
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-blue-700 font-medium mb-1">Número</p>
                <p class="font-bold text-blue-900 text-lg">{{ $resolucion->num_resolucion }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-700 font-medium mb-1">Tipo</p>
                <p class="font-semibold text-blue-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-700 font-medium mb-1">Fecha</p>
                <p class="font-semibold text-blue-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
            </div>
            <div class="md:col-span-3">
                <p class="text-xs text-blue-700 font-medium mb-1">Asunto</p>
                <p class="text-sm text-blue-900">{{ $resolucion->asunto_resolucion }}</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('colaborador.registro-firma-entrega.store', $resolucion) }}">
            @csrf

            <!-- Selección de Persona Externa -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-3">
                    <svg class="w-5 h-5 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Seleccione la Persona Externa Destinataria *
                </label>
                <p class="text-xs text-gray-500 mb-4">Elija a quién se le firmará y entregará esta resolución</p>

                <div class="space-y-3">
                    @foreach($personasExternas as $persona)
                    @php
                        // Contar registros existentes de esta persona
                        $registrosExistentes = \App\Models\RegistroFirmaEntrega::where('id_resolucion', $resolucion->id_resolucion)
                            ->where('id_persona_resolucion_datos', $persona->id_persona_resolucion_datos)
                            ->count();
                        
                        $tieneUsuario = $persona->id_user !== null;
                        $usuarioSistema = null;
                        if ($tieneUsuario) {
                            $usuarioSistema = \App\Models\User::find($persona->id_user);
                        }
                    @endphp
                    
                    <label class="flex items-start gap-4 p-4 border-2 border-gray-200 rounded-lg hover:border-purple-400 hover:bg-purple-50 cursor-pointer transition-all duration-200 group">
                        <input type="radio" 
                               name="id_persona_resolucion_datos" 
                               value="{{ $persona->id_persona_resolucion_datos }}"
                               required
                               class="mt-1 w-5 h-5 text-purple-600 focus:ring-purple-500 focus:ring-2">
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Avatar y nombre -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                            {{ strtoupper(substr($persona->nombres, 0, 1) . substr($persona->apellido_paterno, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 group-hover:text-purple-900 transition-colors">
                                                {{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $persona->tipo_documento }}: {{ $persona->num_documento }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="flex flex-wrap items-center gap-3 mt-2">
                                        @if($persona->tipo_relacion)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ $persona->tipo_relacion }}
                                        </span>
                                        @endif

                                        @if($tieneUsuario && $usuarioSistema)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Usuario registrado
                                        </span>
                                        @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                            Sin cuenta de usuario
                                        </span>
                                        @endif

                                        @if($registrosExistentes > 0)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">
                                            {{ $registrosExistentes }} {{ $registrosExistentes == 1 ? 'registro previo' : 'registros previos' }}
                                        </span>
                                        @endif
                                    </div>

                                    @if($persona->descripcion_relacion)
                                    <div class="mt-2 p-2 bg-gray-50 rounded text-xs text-gray-600 italic border-l-2 border-gray-300">
                                        "{{ $persona->descripcion_relacion }}"
                                    </div>
                                    @endif

                                    @if($tieneUsuario && $usuarioSistema)
                                    <div class="mt-2 flex items-center gap-1 text-xs text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $usuarioSistema->email }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                @error('id_persona_resolucion_datos')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Observaciones -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">
                    <svg class="w-5 h-5 inline mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Observaciones (Opcional)
                </label>
                <textarea name="observaciones" 
                          rows="3" 
                          maxlength="1000"
                          placeholder="Ingrese cualquier nota relevante sobre esta solicitud de firma..."
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">{{ old('observaciones') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres</p>
                @error('observaciones')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Información importante -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-blue-900 mb-1">Información importante</h4>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>• Se creará un registro de firma para la persona seleccionada</li>
                            <li>• El proceso será: Solicitud → Firma (manual/Firma Perú) → Entrega</li>
                            <li>• La persona puede solicitar múltiples firmas de la misma resolución</li>
                            <li>• Cada solicitud genera un código de verificación único</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Crear Solicitud de Firma
                </button>

                <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>

                <a href="{{ route('colaborador.registro-firma-entrega.index') }}" 
                   class="px-6 py-3 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Ver Todos los Registros
                </a>
            </div>
        </form>
    </div>
</div>
@endsection