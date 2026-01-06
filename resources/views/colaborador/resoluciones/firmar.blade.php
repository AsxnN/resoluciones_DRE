{{-- filepath: resources/views/colaborador/resoluciones/firmar.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Firmar Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Firmar Resolución Digitalmente
                </h1>
                <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
            </div>
        </div>
    </div>

    <!-- Alerta de advertencia -->
    @if($resolucion->archivo_firmado)
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-yellow-700 font-medium">Esta resolución ya ha sido firmada</p>
                <p class="text-yellow-600 text-sm mt-1">Firmada el {{ $resolucion->fecha_firma->format('d/m/Y H:i') }} por {{ $resolucion->usuarioFirmante->name }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario de Firma - 2 columnas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST" action="{{ route('colaborador.resoluciones.procesarFirma', $resolucion) }}" enctype="multipart/form-data" id="firmaForm">
                    @csrf

                    <!-- Información de la Resolución -->
                    <div class="mb-8 pb-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Documento a Firmar</h2>
                        <div class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                    {{ strtoupper(substr($resolucion->num_resolucion, 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h3>
                                    <p class="text-gray-700 mt-2">{{ $resolucion->asunto_resolucion }}</p>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-gray-700">
                                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                                        </span>
                                        <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-gray-700">
                                            {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Firma -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">1</span>
                            Seleccione el Tipo de Firma
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Firma Digital -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tipo_firma" value="digital" class="peer sr-only" checked>
                                <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-green-400 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="peer-checked:block hidden w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Firma Digital con Certificado</h3>
                                    <p class="text-sm text-gray-600">Utiliza tu certificado digital para firmar electrónicamente</p>
                                </div>
                            </label>

                            <!-- Firma Simple -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tipo_firma" value="simple" class="peer sr-only">
                                <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-green-400 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="peer-checked:block hidden w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Firma Simple Electrónica</h3>
                                    <p class="text-sm text-gray-600">Firma con tu contraseña y datos de usuario</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Opciones según el tipo de firma -->
                    <div id="opcionesDigital" class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">2</span>
                            Certificado Digital
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Seleccione su Certificado Digital (.pfx, .p12)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       name="certificado" 
                                       accept=".pfx,.p12"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                                @error('certificado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_certificado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña del Certificado
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_certificado" 
                                       name="password_certificado"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Ingrese la contraseña de su certificado">
                                @error('password_certificado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="opcionesSimple" class="mb-8 hidden">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">2</span>
                            Verificación de Identidad
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password_usuario" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirme su Contraseña
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_usuario" 
                                       name="password_usuario"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Ingrese su contraseña de usuario">
                                <p class="mt-1 text-xs text-gray-500">Para confirmar su identidad, ingrese su contraseña de usuario</p>
                                @error('password_usuario')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">3</span>
                            Observaciones (Opcional)
                        </h2>
                        
                        <textarea name="observaciones_firma" 
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Ingrese observaciones sobre la firma (opcional)">{{ old('observaciones_firma') }}</textarea>
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="mb-8">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" 
                                   name="acepta_terminos" 
                                   required
                                   class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">Acepto los términos y condiciones.</span> 
                                Al firmar este documento declaro que he revisado su contenido y acepto la responsabilidad legal de mi firma electrónica.
                            </span>
                        </label>
                        @error('acepta_terminos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                                id="btnFirmar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Firmar Resolución
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel de Información - 1 columna -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Importante</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Validez Legal</h4>
                            <p class="text-xs text-gray-600 mt-1">La firma electrónica tiene la misma validez legal que una firma manuscrita según la legislación vigente.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Seguridad</h4>
                            <p class="text-xs text-gray-600 mt-1">El proceso de firma es seguro y está encriptado. Tu información está protegida.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Responsabilidad</h4>
                            <p class="text-xs text-gray-600 mt-1">Al firmar, asumes la responsabilidad del contenido del documento.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Irreversible</h4>
                            <p class="text-xs text-gray-600 mt-1">Una vez firmado, el documento no podrá ser modificado.</p>
                        </div>
                    </div>
                </div>

                <!-- Datos del Firmante -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Firmante</h4>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500">Fecha y hora de firma</p>
                    <p class="font-semibold text-gray-900" id="fechaHoraActual">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioDigital = document.querySelector('input[value="digital"]');
    const radioSimple = document.querySelector('input[value="simple"]');
    const opcionesDigital = document.getElementById('opcionesDigital');
    const opcionesSimple = document.getElementById('opcionesSimple');
    
    // Alternar entre opciones de firma
    radioDigital.addEventListener('change', function() {
        if (this.checked) {
            opcionesDigital.classList.remove('hidden');
            opcionesSimple.classList.add('hidden');
        }
    });
    
    radioSimple.addEventListener('change', function() {
        if (this.checked) {
            opcionesDigital.classList.add('hidden');
            opcionesSimple.classList.remove('hidden');
        }
    });
    
    // Actualizar fecha y hora en tiempo real
    setInterval(function() {
        const now = new Date();
        const formatted = now.toLocaleString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('fechaHoraActual').textContent = formatted;
    }, 1000);
    
    // Confirmación antes de firmar
    document.getElementById('firmaForm').addEventListener('submit', function(e) {
        const confirmacion = confirm(
            '¿Está seguro de firmar esta resolución?\n\n' +
            'Esta acción es irreversible y tiene validez legal.\n\n' +
            'Resolución: {{ $resolucion->num_resolucion }}'
        );
        
        if (!confirmacion) {
            e.preventDefault();
        } else {
            // Deshabilitar el botón para evitar doble clic
            document.getElementById('btnFirmar').disabled = true;
            document.getElementById('btnFirmar').innerHTML = `
                <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 {{-- filepath: resources/views/colaborador/resoluciones/firmar.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Firmar Resolución')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Firmar Resolución Digitalmente
                </h1>
                <p class="text-gray-600 mt-1">{{ $resolucion->num_resolucion }}</p>
            </div>
        </div>
    </div>

    <!-- Alerta de advertencia -->
    @if($resolucion->archivo_firmado)
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-yellow-700 font-medium">Esta resolución ya ha sido firmada</p>
                <p class="text-yellow-600 text-sm mt-1">Firmada el {{ $resolucion->fecha_firma->format('d/m/Y H:i') }} por {{ $resolucion->usuarioFirmante->name }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario de Firma - 2 columnas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST" action="{{ route('colaborador.resoluciones.procesarFirma', $resolucion) }}" enctype="multipart/form-data" id="firmaForm">
                    @csrf

                    <!-- Información de la Resolución -->
                    <div class="mb-8 pb-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Documento a Firmar</h2>
                        <div class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                    {{ strtoupper(substr($resolucion->num_resolucion, 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h3>
                                    <p class="text-gray-700 mt-2">{{ $resolucion->asunto_resolucion }}</p>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-gray-700">
                                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                                        </span>
                                        <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-gray-700">
                                            {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Firma -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">1</span>
                            Seleccione el Tipo de Firma
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Firma Digital -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tipo_firma" value="digital" class="peer sr-only" checked>
                                <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-green-400 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="peer-checked:block hidden w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Firma Digital con Certificado</h3>
                                    <p class="text-sm text-gray-600">Utiliza tu certificado digital para firmar electrónicamente</p>
                                </div>
                            </label>

                            <!-- Firma Simple -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tipo_firma" value="simple" class="peer sr-only">
                                <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-green-400 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="peer-checked:block hidden w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Firma Simple Electrónica</h3>
                                    <p class="text-sm text-gray-600">Firma con tu contraseña y datos de usuario</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Opciones según el tipo de firma -->
                    <div id="opcionesDigital" class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">2</span>
                            Certificado Digital
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Seleccione su Certificado Digital (.pfx, .p12)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       name="certificado" 
                                       accept=".pfx,.p12"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                                @error('certificado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_certificado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña del Certificado
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_certificado" 
                                       name="password_certificado"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Ingrese la contraseña de su certificado">
                                @error('password_certificado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="opcionesSimple" class="mb-8 hidden">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">2</span>
                            Verificación de Identidad
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password_usuario" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirme su Contraseña
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_usuario" 
                                       name="password_usuario"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Ingrese su contraseña de usuario">
                                <p class="mt-1 text-xs text-gray-500">Para confirmar su identidad, ingrese su contraseña de usuario</p>
                                @error('password_usuario')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center text-sm">3</span>
                            Observaciones (Opcional)
                        </h2>
                        
                        <textarea name="observaciones_firma" 
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Ingrese observaciones sobre la firma (opcional)">{{ old('observaciones_firma') }}</textarea>
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="mb-8">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" 
                                   name="acepta_terminos" 
                                   required
                                   class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">Acepto los términos y condiciones.</span> 
                                Al firmar este documento declaro que he revisado su contenido y acepto la responsabilidad legal de mi firma electrónica.
                            </span>
                        </label>
                        @error('acepta_terminos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                                id="btnFirmar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Firmar Resolución
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel de Información - 1 columna -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Importante</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Validez Legal</h4>
                            <p class="text-xs text-gray-600 mt-1">La firma electrónica tiene la misma validez legal que una firma manuscrita según la legislación vigente.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Seguridad</h4>
                            <p class="text-xs text-gray-600 mt-1">El proceso de firma es seguro y está encriptado. Tu información está protegida.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Responsabilidad</h4>
                            <p class="text-xs text-gray-600 mt-1">Al firmar, asumes la responsabilidad del contenido del documento.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Irreversible</h4>
                            <p class="text-xs text-gray-600 mt-1">Una vez firmado, el documento no podrá ser modificado.</p>
                        </div>
                    </div>
                </div>

                <!-- Datos del Firmante -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Firmante</h4>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500">Fecha y hora de firma</p>
                    <p class="font-semibold text-gray-900" id="fechaHoraActual">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioDigital = document.querySelector('input[value="digital"]');
    const radioSimple = document.querySelector('input[value="simple"]');
    const opcionesDigital = document.getElementById('opcionesDigital');
    const opcionesSimple = document.getElementById('opcionesSimple');
    
    // Alternar entre opciones de firma
    radioDigital.addEventListener('change', function() {
        if (this.checked) {
            opcionesDigital.classList.remove('hidden');
            opcionesSimple.classList.add('hidden');
        }
    });
    
    radioSimple.addEventListener('change', function() {
        if (this.checked) {
            opcionesDigital.classList.add('hidden');
            opcionesSimple.classList.remove('hidden');
        }
    });
    
    // Actualizar fecha y hora en tiempo real
    setInterval(function() {
        const now = new Date();
        const formatted = now.toLocaleString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('fechaHoraActual').textContent = formatted;
    }, 1000);
    
    // Confirmación antes de firmar
    document.getElementById('firmaForm').addEventListener('submit', function(e) {
        const confirmacion = confirm(
            '¿Está seguro de firmar esta resolución?\n\n' +
            'Esta acción es irreversible y tiene validez legal.\n\n' +
            'Resolución: {{ $resolucion->num_resolucion }}'
        );
        
        if (!confirmacion) {
            e.preventDefault();
        } else {
            // Deshabilitar el botón para evitar doble clic
            document.getElementById('btnFirmar').disabled = true;
            document.getElementById('btnFirmar').innerHTML = `
                <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 