{{-- filepath: resources/views/colaborador/resoluciones/create-paso3.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Nueva Resolución - Paso 3')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.resoluciones.create-paso2') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">RESOLUCIONES</h1>
                <p class="text-gray-600 mt-1">Paso 3 de 3: Confirmar y guardar</p>
            </div>
        </div>
    </div>

    <!-- Indicador de pasos -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Datos Básicos</span>
                </div>
                <div class="w-24 h-1 bg-green-600"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Contenido</span>
                </div>
                <div class="w-24 h-1 bg-blue-600"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        3
                    </div>
                    <span class="text-sm font-medium text-blue-600 mt-2">Confirmar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner con N° de Resolución -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h2 class="text-white text-sm font-medium mb-1">N° DE RESOLUCIÓN</h2>
            <p class="text-white text-4xl font-bold">{{ $datosPaso1['num_resolucion'] ?? 'N/A' }}</p>
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

    <form method="POST" action="{{ route('colaborador.resoluciones.store-final') }}" class="space-y-6">
        @csrf

        <!-- Resumen: Datos de la Resolución -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    DATOS DE LA RESOLUCIÓN
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- N° Resolución -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">N° Resolución</p>
                            <p class="text-gray-900 font-medium">{{ $datosPaso1['num_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Fecha</p>
                            <p class="text-gray-900 font-medium">
                                {{ isset($datosPaso1['fecha_resolucion']) ? \Carbon\Carbon::parse($datosPaso1['fecha_resolucion'])->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Estado</p>
                            <p class="text-gray-900 font-medium">
                                @if(isset($datosPaso1['id_estado']))
                                    @php
                                        $estado = \App\Models\Estado::find($datosPaso1['id_estado']);
                                    @endphp
                                    {{ $estado ? $estado->nombre_estado : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Tipo de Resolución</p>
                            <p class="text-gray-900 font-medium">
                                @if(isset($datosPaso1['id_tipo_resolucion']))
                                    @php
                                        $tipo = \App\Models\TipoResolucion::find($datosPaso1['id_tipo_resolucion']);
                                    @endphp
                                    {{ $tipo ? $tipo->nombre_tipo_resolucion : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Dependencia -->
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Dependencia - UGEL</p>
                            <p class="text-gray-900 font-medium">
                                @if(isset($datosPaso1['id_dependencia']))
                                    @php
                                        $dependencia = \App\Models\Dependencia::find($datosPaso1['id_dependencia']);
                                    @endphp
                                    {{ $dependencia ? $dependencia->nombre_dependencia : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Visto -->
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Visto</p>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $datosPaso2['visto_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Asunto -->
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Asunto</p>
                            <p class="text-gray-900">{{ $datosPaso2['asunto_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Archivo PDF -->
                    @if(isset($datosPaso2['archivo_resolucion']))
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Archivo adjunto</p>
                            <div class="flex items-center gap-2 text-green-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">PDF incluido</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen: Total de Personas Relacionadas -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    TOTAL: {{ isset($datosPaso1['personas_relacionadas']) ? count($datosPaso1['personas_relacionadas']) : 0 }} PERSONA(S) RELACIONADA(S)
                </h3>
            </div>
            <div class="p-6">
                @if(isset($datosPaso1['personas_relacionadas']) && count($datosPaso1['personas_relacionadas']) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-300">
                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">DNI</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Nombre Completo</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Teléfono</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($datosPaso1['personas_relacionadas'] as $index => $persona)
                                    @php
                                        $personaData = \App\Models\Persona::find($persona['id_persona']);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                            {{ $personaData ? $personaData->num_documento : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($personaData)
                                                {{ $personaData->apellido_paterno }} {{ $personaData->apellido_materno }}, {{ $personaData->nombres }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $personaData && $personaData->celular ? $personaData->celular : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $personaData && $personaData->correo ? $personaData->correo : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No hay personas relacionadas en esta resolución</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Opciones de Notificación -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                OPCIONES DE NOTIFICACIÓN
            </h3>
            
            <div class="space-y-4">
                <!-- WhatsApp -->
                <div class="flex items-center p-4 bg-green-50 border-2 border-green-200 rounded-lg hover:border-green-400 transition-colors">
                    <input type="checkbox" 
                           id="enviar_whatsapp" 
                           name="enviar_whatsapp" 
                           value="1"
                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="enviar_whatsapp" class="ml-3 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Enviar por WhatsApp</p>
                            <p class="text-sm text-gray-600">Notificar a las personas relacionadas vía WhatsApp</p>
                        </div>
                    </label>
                </div>

                <!-- Correo Electrónico -->
                <div class="flex items-center p-4 bg-blue-50 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition-colors">
                    <input type="checkbox" 
                           id="enviar_correo" 
                           name="enviar_correo" 
                           value="1"
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="enviar_correo" class="ml-3 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Enviar por Correo Electrónico</p>
                            <p class="text-sm text-gray-600">Notificar a las personas relacionadas vía Email</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Términos y Condiciones -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-start">
                <input type="checkbox" 
                       id="aceptar_terminos" 
                       name="aceptar_terminos" 
                       required
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                <label for="aceptar_terminos" class="ml-3 cursor-pointer">
                    <p class="text-sm font-medium text-yellow-800">
                        Acepto que los datos ingresados son correctos y completos <span class="text-red-600">*</span>
                    </p>
                    <p class="text-xs text-yellow-700 mt-1">
                        Al marcar esta casilla, confirmo que he revisado todos los datos y son verídicos. La resolución será registrada en el sistema.
                    </p>
                </label>
            </div>
        </div>

        <!-- Botones de Navegación -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('colaborador.resoluciones.create-paso2') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
            </a>

            <button type="submit" 
                    class="px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg transition-all text-lg shadow-xl flex items-center gap-2 transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                GUARDAR RESOLUCIÓN
            </button>
        </div>
    </form>
</div>
@endsection