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
                                            {{ $personaData && $personaData->telefono ? $personaData->telefono : 'N/A' }}
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