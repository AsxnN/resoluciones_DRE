{{-- filepath: resources/views/colaborador/resoluciones-firmadas/historial.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle de Firma - ' . $resolucion->num_resolucion)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.resoluciones-firmadas.index') }}"
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Detalle de Firma y Entrega</h1>
                <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
            </div>
        </div>

        <!-- Información de la Resolución -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-purple-500 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Número</p>
                    <p class="font-bold text-purple-900 text-lg">{{ $resolucion->num_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Tipo</p>
                    <p class="font-semibold text-purple-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Fecha de Resolución</p>
                    <p class="font-semibold text-purple-900">{{ $resolucion->fecha_resolucion ? $resolucion->fecha_resolucion->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Total de entregas</p>
                    <p class="font-bold text-purple-900 text-lg">{{ $entregas->count() }}</p>
                </div>
                <div class="md:col-span-4">
                    <p class="text-xs text-purple-700 font-medium mb-1">Asunto</p>
                    <p class="text-sm text-purple-900">{{ $resolucion->asunto_resolucion }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-900 mb-4">Historial de entregas</h3>
    <p class="text-sm text-gray-500 mb-4">Esta resolución puede entregarse varias veces — a la misma persona o a distintos clientes. Cada entrega queda registrada por separado.</p>

    <div class="space-y-4">
        @forelse($entregas as $entrega)
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Datos de la firma -->
                <div>
                    <h4 class="text-sm font-bold text-green-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Firmado por
                    </h4>
                    <p class="text-sm text-gray-900">{{ $entrega->usuarioFirma->persona->nombres ?? $entrega->usuarioFirma->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $entrega->fecha_entrega->format('d/m/Y H:i') }}</p>
                    @if($entrega->archivo_firmado)
                    <a href="{{ Storage::url($entrega->archivo_firmado) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 mt-2 text-xs text-green-700 hover:text-green-900 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Ver Documento Firmado
                    </a>
                    @endif
                </div>

                <!-- Datos del receptor -->
                <div>
                    <h4 class="text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Entregado a
                    </h4>
                    @if($entrega->personaEntrega)
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $entrega->personaEntrega->nombres }} {{ $entrega->personaEntrega->apellido_paterno }} {{ $entrega->personaEntrega->apellido_materno }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $entrega->personaEntrega->tipo_documento }}: {{ $entrega->personaEntrega->num_documento }}</p>
                    <p class="text-xs text-gray-500">✉️ {{ $entrega->correo_entrega ?? $entrega->personaEntrega->correo ?? 'N/A' }}</p>
                    @if($entrega->cuenta_creada)
                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-full">🆕 Cuenta creada en esta entrega</span>
                    @endif
                    @else
                    <p class="text-gray-400 text-sm">N/A</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <p class="text-gray-500">Todavía no hay entregas registradas para esta resolución.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}"
           class="px-6 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-lg transition-colors">
            Ver Resolución Completa
        </a>
    </div>
</div>
@endsection
