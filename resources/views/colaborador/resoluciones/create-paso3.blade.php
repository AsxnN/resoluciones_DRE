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
                    <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                        #
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Documento
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Nombre Completo
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Relación
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">
                                        Origen
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($datosPaso1['personas_relacionadas'] as $index => $persona)
                                @php
                                    $nombreCompleto = trim(
                                        ($persona['nombres'] ?? '') . ' ' . 
                                        ($persona['apellido_paterno'] ?? '') . ' ' . 
                                        ($persona['apellido_materno'] ?? '')
                                    );
                                    
                                    $badgeRelacion = [
                                        'beneficiario' => 'bg-green-100 text-green-800 border border-green-200',
                                        'afectado' => 'bg-red-100 text-red-800 border border-red-200',
                                        'involucrado' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                        'testigo' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        'otro' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                    ];
                                    
                                    $tipoRelacionLabel = [
                                        'beneficiario' => 'Beneficiario',
                                        'afectado' => 'Afectado',
                                        'involucrado' => 'Involucrado',
                                        'testigo' => 'Testigo',
                                        'otro' => 'Otro',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ strtoupper($persona['tipo_documento'] ?? 'DNI') }}</span>
                                            <span class="text-gray-600 font-mono">{{ $persona['num_documento'] ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ $nombreCompleto }}</span>
                                            @if(!empty($persona['descripcion_relacion']))
                                            <span class="text-gray-500 text-xs mt-1 italic">{{ $persona['descripcion_relacion'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeRelacion[$persona['tipo_relacion']] ?? 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                            {{ $tipoRelacionLabel[$persona['tipo_relacion']] ?? 'Otro' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        @if(isset($persona['obtenido_reniec']) && $persona['obtenido_reniec'])
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                RENIEC
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
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
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-semibold text-gray-900">Sin personas relacionadas</h3>
                        <p class="mt-2 text-sm text-gray-500">No hay personas relacionadas con esta resolución</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Resumen: Personas INTERNAS -->
        @if(isset($datosPaso1['personas_internas']) && count($datosPaso1['personas_internas']) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    👔 TRABAJADORES DRE ({{ count($datosPaso1['personas_internas']) }})
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">Correo</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">Tipo Relación</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider">Descripción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($datosPaso1['personas_internas'] as $index => $persona)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">{{ $persona['num_documento'] }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $persona['nombre_completo'] }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $persona['correo'] ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $badgeColors = [
                                            'beneficiario' => 'bg-green-100 text-green-800',
                                            'afectado' => 'bg-red-100 text-red-800',
                                            'involucrado' => 'bg-yellow-100 text-yellow-800',
                                            'testigo' => 'bg-blue-100 text-blue-800',
                                            'otro' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $badgeColors[$persona['tipo_relacion']] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($persona['tipo_relacion']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ $persona['descripcion_relacion'] ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-green-800">
                        <strong>📌 Nota:</strong> Estas personas tienen cuenta de usuario y podrán ver esta resolución en su módulo "Mis Resoluciones"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    🌐 PERSONAS EXTERNAS ({{ count($datosPaso1['personas_externas']) }})
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Tipo Doc.</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">N° Documento</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Tipo Relación</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Descripción</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Fuente</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($datosPaso1['personas_externas'] as $index => $persona)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $persona['tipo_documento'] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                    {{ $persona['num_documento'] ?? 'Sin documento' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                    {{ $persona['nombres'] }} {{ $persona['apellido_paterno'] }} {{ $persona['apellido_materno'] ?? '' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $badgeColors = [
                                            'beneficiario' => 'bg-green-100 text-green-800',
                                            'afectado' => 'bg-red-100 text-red-800',
                                            'involucrado' => 'bg-yellow-100 text-yellow-800',
                                            'testigo' => 'bg-blue-100 text-blue-800',
                                            'otro' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $badgeColors[$persona['tipo_relacion']] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($persona['tipo_relacion']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ $persona['descripcion_relacion'] ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if(isset($persona['obtenido_reniec']) && $persona['obtenido_reniec'])
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                            🏛️ RENIEC
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            ✍️ Manual
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
                        <strong>📌 Nota:</strong> Estas personas NO tienen cuenta de usuario en el sistema
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

        <!-- Notificar por Correo -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 012 2z"/>
                    </svg>
                    NOTIFICAR POR CORREO
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Busca usuarios por DNI para enviarles una notificación de esta resolución por correo electrónico
                </p>

                <!-- Formulario de búsqueda -->
                <div class="mb-6 bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label for="dni_buscar" class="block text-sm font-medium text-gray-700 mb-2">
                                Buscar por DNI
                            </label>
                            <input type="text" 
                                   id="dni_buscar" 
                                   placeholder="Ingrese DNI de 8 dígitos"
                                   maxlength="8"
                                   class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex items-end">
                            <button type="button" 
                                    id="btn-buscar-usuario"
                                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Resultado de búsqueda -->
                    <div id="resultado-busqueda" class="hidden mt-4 p-4 bg-white border border-gray-300 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="usuario-nombre"></p>
                                    <p class="text-sm text-gray-600" id="usuario-dni"></p>
                                    <p class="text-sm text-gray-500" id="usuario-email"></p>
                                </div>
                            </div>
                            <button type="button" 
                                    id="btn-agregar-usuario"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </div>

                    <!-- Mensaje de no encontrado -->
                    <div id="mensaje-no-encontrado" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2 text-red-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                            <span class="font-medium">No se encontró usuario con ese DNI</span>
                        </div>
                    </div>
                </div>

                <!-- Lista de usuarios seleccionados -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">
                        Usuarios a notificar (<span id="contador-usuarios">0</span>)
                    </h4>
                    <div id="lista-usuarios-notificar" class="space-y-2">
                        <!-- Se llenará dinámicamente con JavaScript -->
                    </div>
                    <div id="mensaje-sin-usuarios" class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm">No hay usuarios seleccionados para notificar</p>
                    </div>
                </div>

                <!-- Inputs ocultos para enviar los IDs -->
                <div id="inputs-usuarios-notificar"></div>
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

@push('scripts')
<script>
// Array para almacenar usuarios seleccionados
let usuariosNotificar = [];

// Buscar usuario por DNI
document.getElementById('btn-buscar-usuario').addEventListener('click', async function() {
    const dni = document.getElementById('dni_buscar').value.trim();
    
    if (!dni) {
        alert('⚠️ Ingrese un DNI para buscar');
        return;
    }
    
    if (dni.length !== 8 || !/^\d+$/.test(dni)) {
        alert('⚠️ El DNI debe tener 8 dígitos numéricos');
        return;
    }
    
    // Ocultar mensajes previos
    document.getElementById('resultado-busqueda').classList.add('hidden');
    document.getElementById('mensaje-no-encontrado').classList.add('hidden');
    
    try {
        const response = await fetch(`{{ route('colaborador.resoluciones.buscar-usuario') }}?dni=${dni}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Verificar si ya está agregado
            if (usuariosNotificar.find(u => u.id === data.usuario.id)) {
                alert('⚠️ Este usuario ya fue agregado a la lista');
                return;
            }
            
            // Mostrar resultado
            document.getElementById('usuario-nombre').textContent = data.usuario.nombre_completo;
            document.getElementById('usuario-dni').textContent = `DNI: ${data.usuario.dni}`;
            document.getElementById('usuario-email').textContent = `📧 ${data.usuario.email}`;
            document.getElementById('resultado-busqueda').classList.remove('hidden');
            
            // Guardar temporalmente el usuario encontrado
            document.getElementById('btn-agregar-usuario').setAttribute('data-usuario', JSON.stringify(data.usuario));
        } else {
            document.getElementById('mensaje-no-encontrado').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al buscar usuario. Intente nuevamente.');
    }
});

// Agregar usuario a la lista
document.getElementById('btn-agregar-usuario').addEventListener('click', function() {
    const usuarioData = this.getAttribute('data-usuario');
    if (!usuarioData) return;
    
    const usuario = JSON.parse(usuarioData);
    
    // Agregar al array
    usuariosNotificar.push(usuario);
    
    // Actualizar vista
    actualizarListaUsuarios();
    
    // Limpiar búsqueda
    document.getElementById('dni_buscar').value = '';
    document.getElementById('resultado-busqueda').classList.add('hidden');
});

// Actualizar lista de usuarios
function actualizarListaUsuarios() {
    const lista = document.getElementById('lista-usuarios-notificar');
    const mensaje = document.getElementById('mensaje-sin-usuarios');
    const contador = document.getElementById('contador-usuarios');
    
    contador.textContent = usuariosNotificar.length;
    
    if (usuariosNotificar.length === 0) {
        lista.innerHTML = '';
        mensaje.classList.remove('hidden');
        actualizarInputsOcultos();
        return;
    }
    
    mensaje.classList.add('hidden');
    
    lista.innerHTML = usuariosNotificar.map((usuario, index) => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                    ${usuario.nombre_completo.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">${usuario.nombre_completo}</p>
                    <div class="flex gap-3 text-sm text-gray-600">
                        <span>DNI: ${usuario.dni}</span>
                        <span>•</span>
                        <span>📧 ${usuario.email}</span>
                    </div>
                </div>
            </div>
            <button type="button" 
                    onclick="eliminarUsuario(${index})"
                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    title="Eliminar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `).join('');
    
    actualizarInputsOcultos();
}

// Eliminar usuario de la lista
function eliminarUsuario(index) {
    usuariosNotificar.splice(index, 1);
    actualizarListaUsuarios();
}

// Actualizar inputs ocultos para enviar con el formulario
function actualizarInputsOcultos() {
    const container = document.getElementById('inputs-usuarios-notificar');
    container.innerHTML = usuariosNotificar.map((usuario, index) => 
        `<input type="hidden" name="usuarios_notificar[${index}]" value="${usuario.id}">`
    ).join('');
}

// Limpiar en caso de error de validación (mantener usuarios si hay old())
@if(old('usuarios_notificar'))
    usuariosNotificar = @json(old('usuarios_notificar', [])).map(id => ({id: id}));
    actualizarListaUsuarios();
@endif
</script>
@endpush