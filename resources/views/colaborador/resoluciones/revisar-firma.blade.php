{{-- filepath: resources/views/colaborador/resoluciones/revisar-firma.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Revisar Firma de Resoluciones')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('colaborador.resoluciones.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Revisar Firma de Resoluciones</h1>
        </div>
        <p class="text-gray-600">Revisa las resoluciones seleccionadas antes de firmar</p>
    </div>

    <!-- Resumen -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $resoluciones->count() }} Resolución(es) Seleccionadas</h2>
                <p class="text-blue-100">Revisa la información y selecciona las opciones de notificación</p>
            </div>
            <div class="text-6xl font-bold opacity-20">
                {{ $resoluciones->count() }}
            </div>
        </div>
    </div>

    <!-- Formulario de Firma -->
    <form method="POST" action="{{ route('colaborador.resoluciones.firmarMasivo') }}" id="formFirmar">
        @csrf
        <input type="hidden" name="resoluciones_ids" value="{{ json_encode($resoluciones->pluck('id_resolucion')->toArray()) }}">

        <!-- Lista de Resoluciones -->
        <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Resoluciones a Firmar
                </h3>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($resoluciones as $resolucion)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h4>
                                    <p class="text-sm text-gray-600">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $resolucion->estado->nombre_estado === 'Aprobado' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $resolucion->estado->nombre_estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $resolucion->estado->nombre_estado === 'Rechazado' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $resolucion->estado->nombre_estado }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 mb-3">{{ $resolucion->asunto_resolucion }}</p>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium text-gray-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Creado por:</span>
                                    <span class="font-medium text-gray-900">{{ $resolucion->usuarioCreador->persona->nombre_persona ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if($resolucion->personasInvolucradas && $resolucion->personasInvolucradas->isNotEmpty())
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Personas a notificar:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($resolucion->personasInvolucradas as $persona)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $persona->nombre_persona }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 italic">Sin personas involucradas</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Opciones de Notificación -->
        <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Opciones de Notificación
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-gray-700 mb-4">Selecciona cómo deseas notificar a las personas involucradas:</p>

                <!-- WhatsApp -->
                <div class="flex items-center p-4 bg-green-50 border-2 border-green-200 rounded-lg hover:border-green-400 transition-colors cursor-pointer" onclick="toggleCheckbox('enviar_whatsapp')">
                    <input type="checkbox" 
                           name="enviar_whatsapp" 
                           id="enviar_whatsapp" 
                           value="1"
                           class="w-6 h-6 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="enviar_whatsapp" class="ml-4 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div>
                            <span class="block font-bold text-gray-900 text-lg">Enviar por WhatsApp</span>
                            <span class="block text-sm text-gray-600">Notificar a las personas con número de WhatsApp registrado</span>
                        </div>
                    </label>
                </div>

                <!-- Correo Electrónico -->
                <div class="flex items-center p-4 bg-blue-50 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition-colors cursor-pointer" onclick="toggleCheckbox('enviar_correo')">
                    <input type="checkbox" 
                           name="enviar_correo" 
                           id="enviar_correo" 
                           value="1"
                           class="w-6 h-6 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="enviar_correo" class="ml-4 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <span class="block font-bold text-gray-900 text-lg">Enviar por Correo Electrónico</span>
                            <span class="block text-sm text-gray-600">Notificar a las personas con correo electrónico registrado</span>
                        </div>
                    </label>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Las notificaciones son opcionales</p>
                            <p class="text-xs text-yellow-700 mt-1">Puedes firmar sin enviar notificaciones si así lo deseas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Firmar {{ $resoluciones->count() }} Resolución(es)
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleCheckbox(id) {
    const checkbox = document.getElementById(id);
    checkbox.checked = !checkbox.checked;
}
</script>
@endpush
@endsection