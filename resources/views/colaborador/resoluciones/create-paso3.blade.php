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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Datos Básicos</span>
                </div>
                <div class="w-24 h-1 bg-green-600"></div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Número de Resolución</label>
                            <p class="text-gray-900 font-semibold text-lg mt-1">{{ $datosPaso1['num_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha de Resolución</label>
                            <p class="text-gray-900 font-semibold text-lg mt-1">
                                {{ isset($datosPaso1['fecha_resolucion']) ? \Carbon\Carbon::parse($datosPaso1['fecha_resolucion'])->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Estado</label>
                            <p class="text-gray-900 font-semibold mt-1">
                                @if(isset($datosPaso1['id_estado']))
                                    @php
                                        $estado = \App\Models\Estado::find($datosPaso1['id_estado']);
                                    @endphp
                                    {{ $estado->nombre_estado ?? 'N/A' }}
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
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo de Resolución</label>
                            <p class="text-gray-900 font-semibold mt-1">
                                @if(isset($datosPaso1['id_tipo_resolucion']))
                                    @php
                                        $tipo = \App\Models\TipoResolucion::find($datosPaso1['id_tipo_resolucion']);
                                    @endphp
                                    {{ $tipo->nombre_tipo_resolucion ?? 'N/A' }}
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
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dependencia</label>
                            <p class="text-gray-900 font-semibold mt-1">
                                @if(isset($datosPaso1['id_dependencia']))
                                    @php
                                        $dependencia = \App\Models\Dependencia::find($datosPaso1['id_dependencia']);
                                    @endphp
                                    {{ $dependencia->nombre_dependencia ?? 'N/A' }}
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
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Visto</label>
                            <p class="text-gray-700 mt-1 text-sm">{{ $datosPaso2['visto_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Asunto -->
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Asunto</label>
                            <p class="text-gray-700 mt-1 text-sm">{{ $datosPaso2['asunto_resolucion'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Archivo PDF -->
                    @if(isset($datosPaso2['archivo_resolucion']))
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Archivo Adjunto</label>
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-900 font-medium">Archivo PDF cargado</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen: Personas INTERNAS -->
        @if(isset($datosPaso1['personas_internas']) && count($datosPaso1['personas_internas']) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    👔 TRABAJADORES DRE ({{ count($datosPaso1['personas_internas']) }})
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">DNI</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre Completo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($datosPaso1['personas_internas'] as $index => $interno)
                            @php
                                $userInterno = \App\Models\User::find($interno['id_user'] ?? null);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">{{ $index + 1 }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 font-mono">{{ $interno['num_documento'] ?? 'N/A' }}</td>
                                <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ $interno['nombre_completo'] ?? 'N/A' }}</td>
                                <td class="px-3 py-4 text-sm text-gray-600">{{ $userInterno->username ?? ($interno['correo'] ?? 'N/A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-green-800">
                        ✓ Estos usuarios verán automáticamente esta resolución en su módulo "Mis Resoluciones"
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Resumen: Personas EXTERNAS -->
        @if(isset($datosPaso1['personas_externas']) && count($datosPaso1['personas_externas']) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    🌐 PERSONAS EXTERNAS ({{ count($datosPaso1['personas_externas']) }})
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Documento</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre Completo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Relación</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Origen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($datosPaso1['personas_externas'] as $index => $externa)
                            @php
                                $nombreCompleto = trim(
                                    ($externa['nombres'] ?? '') . ' ' . 
                                    ($externa['apellido_paterno'] ?? '') . ' ' . 
                                    ($externa['apellido_materno'] ?? '')
                                );
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">{{ $index + 1 }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900">{{ strtoupper($externa['tipo_documento'] ?? 'DNI') }}</span>
                                        <span class="text-gray-600 font-mono">{{ $externa['num_documento'] ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ $nombreCompleto }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $tipoRelacion = $externa['tipo_relacion'] ?? 'otro';
                                        $badgeClass = match($tipoRelacion) {
                                            'beneficiario' => 'bg-green-100 text-green-800',
                                            'afectado' => 'bg-red-100 text-red-800',
                                            'involucrado' => 'bg-blue-100 text-blue-800',
                                            'testigo' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                        $labelRelacion = match($tipoRelacion) {
                                            'beneficiario' => 'Beneficiario',
                                            'afectado' => 'Afectado',
                                            'involucrado' => 'Involucrado',
                                            'testigo' => 'Testigo',
                                            default => 'Otro',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $labelRelacion }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                    @if(isset($externa['obtenido_reniec']) && $externa['obtenido_reniec'])
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            RENIEC
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Manual
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-sm text-blue-800">
                        ℹ️ Las personas externas necesitarán ser asignadas manualmente para ver esta resolución
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Sin personas relacionadas -->
        @if(
            (!isset($datosPaso1['personas_internas']) || count($datosPaso1['personas_internas']) === 0) &&
            (!isset($datosPaso1['personas_externas']) || count($datosPaso1['personas_externas']) === 0)
        )
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    PERSONAS RELACIONADAS
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-gray-900">Sin personas relacionadas</h3>
                    <p class="mt-2 text-sm text-gray-500">No hay personas relacionadas con esta resolución</p>
                </div>
            </div>
        </div>
        @endif

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
                        Acepto que la información ingresada es correcta y verídica
                    </p>
                    <p class="text-xs text-yellow-700 mt-1">
                        Al guardar esta resolución, confirmo que he revisado todos los datos y son correctos
                    </p>
                </label>
            </div>
        </div>

        <!-- Botones de Navegación -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('colaborador.resoluciones.create-paso2') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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