{{-- filepath: resources/views/colaborador/firma/firmar.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Firmar Resolución')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.firma.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✍️ Firmar Resolución Digitalmente</h1>
                <p class="text-gray-600 mt-1">Aplicar firma electrónica a la resolución</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.firma.store') }}" id="formFirma">
            @csrf

            <!-- Selección de Resolución -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">1</span>
                    Seleccionar Resolución
                </h2>
                
                <div>
                    <label for="id_resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Resolución a Firmar <span class="text-red-500">*</span>
                    </label>
                    <select id="id_resolucion" 
                            name="id_resolucion" 
                            required
                            onchange="cargarResolucion(this.value)"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('id_resolucion') border-red-500 @enderror">
                        <option value="">Seleccione una resolución...</option>
                        @foreach($resoluciones as $resolucion)
                            <option value="{{ $resolucion->id_resolucion }}" 
                                {{ old('id_resolucion', request('resolucion')) == $resolucion->id_resolucion ? 'selected' : '' }}
                                data-numero="{{ $resolucion->num_resolucion }}"
                                data-asunto="{{ $resolucion->asunto_resolucion }}"
                                data-fecha="{{ $resolucion->fecha_resolucion->format('d/m/Y') }}">
                                {{ $resolucion->num_resolucion }} - {{ Str::limit($resolucion->asunto_resolucion, 60) }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_resolucion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview de Resolución Seleccionada -->
                <div id="previewResolucion" class="hidden mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="font-semibold text-blue-900 mb-2">📄 Resolución Seleccionada:</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-blue-700">Número:</span>
                            <span class="ml-2 font-medium" id="prev-numero"></span>
                        </div>
                        <div>
                            <span class="text-blue-700">Fecha:</span>
                            <span class="ml-2 font-medium" id="prev-fecha"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-blue-700">Asunto:</span>
                            <span class="ml-2 font-medium" id="prev-asunto"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos de la Firma -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">2</span>
                    Datos del Firmante
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cargo del Firmante -->
                    <div class="md:col-span-2">
                        <label for="cargo_firmante" class="block text-sm font-medium text-gray-700 mb-2">
                            Cargo del Firmante <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="cargo_firmante" 
                               name="cargo_firmante" 
                               value="{{ old('cargo_firmante', 'Director Regional de Educación') }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('cargo_firmante') border-red-500 @enderror"
                               placeholder="Ej: Director Regional de Educación">
                        @error('cargo_firmante')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación de Firma -->
                    <div>
                        <label for="ubicacion_firma" class="block text-sm font-medium text-gray-700 mb-2">
                            Ubicación de Firma
                        </label>
                        <input type="text" 
                               id="ubicacion_firma" 
                               name="ubicacion_firma" 
                               value="{{ old('ubicacion_firma', 'Huánuco, Perú') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                               placeholder="Huánuco, Perú">
                    </div>

                    <!-- Motivo de Firma -->
                    <div>
                        <label for="motivo_firma" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo de Firma
                        </label>
                        <input type="text" 
                               id="motivo_firma" 
                               name="motivo_firma" 
                               value="{{ old('motivo_firma', 'Aprobación de Resolución') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                               placeholder="Aprobación de Resolución">
                    </div>
                </div>
            </div>

            <!-- Configuración de Firma -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                    Configuración de Seguridad
                </h2>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-900">⚠️ Importante</h3>
                            <p class="text-sm text-yellow-800 mt-1">
                                La firma digital es irrevocable y genera un hash criptográfico único.
                                Verifique que la información sea correcta antes de firmar.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contraseña de Confirmación -->
                <div>
                    <label for="password_confirmacion" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirme su Contraseña para Firmar <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmacion" 
                           name="password_confirmacion" 
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('password_confirmacion') border-red-500 @enderror"
                           placeholder="Ingrese su contraseña para confirmar la firma">
                    @error('password_confirmacion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Términos y Condiciones -->
            <div class="mb-8">
                <label class="flex items-start">
                    <input type="checkbox" 
                           name="aceptar_terminos" 
                           required
                           class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-3 text-sm text-gray-700">
                        Acepto que al firmar digitalmente esta resolución, estoy certificando la autenticidad 
                        del documento y asumo la responsabilidad legal correspondiente. 
                        <span class="text-red-600 font-semibold">Esta acción es irreversible.</span>
                    </span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('colaborador.firma.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        id="btnFirmar"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    ✍️ Firmar Digitalmente
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function cargarResolucion(idResolucion) {
    const select = document.getElementById('id_resolucion');
    const preview = document.getElementById('previewResolucion');
    
    if (!idResolucion) {
        preview.classList.add('hidden');
        return;
    }
    
    const option = select.options[select.selectedIndex];
    document.getElementById('prev-numero').textContent = option.dataset.numero;
    document.getElementById('prev-asunto').textContent = option.dataset.asunto;
    document.getElementById('prev-fecha').textContent = option.dataset.fecha;
    
    preview.classList.remove('hidden');
}

// Cargar si hay resolución preseleccionada
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('id_resolucion');
    if (select.value) {
        cargarResolucion(select.value);
    }
    
    // Confirmación antes de enviar
    document.getElementById('formFirma').addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro de firmar esta resolución digitalmente? Esta acción NO se puede deshacer.')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection