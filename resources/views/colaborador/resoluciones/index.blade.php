{{-- filepath: resources/views/colaborador/resoluciones/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Gestión de Resoluciones')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header con título y botones de acción -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Gestión de Resoluciones
            </h1>
            <p class="text-gray-600 mt-1">Administra y firma las resoluciones directorales</p>
        </div>
        
        <div class="flex gap-3">
            @can('resoluciones.crear')
            <a href="{{ route('colaborador.resoluciones.create') }}" 
               class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nueva Resolución
            </a>
            @endcan

            <!-- Botón Firmar Seleccionadas -->
            @can('resoluciones.firmar')
            <button type="button" 
                    id="btnFirmarSeleccionadas"
                    onclick="firmarSeleccionadas()"
                    disabled
                    class="inline-flex items-center px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Firmar Seleccionadas (<span id="contadorSeleccionadas">0</span>)
            </button>
            @endcan
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
        <form method="GET" action="{{ route('colaborador.resoluciones.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Búsqueda -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Número de resolución, asunto..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos los estados</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ request('estado') == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Resolución -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="tipo_resolucion" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos los tipos</option>
                        @foreach($tiposResolucion as $tipo)
                            <option value="{{ $tipo->id_tipo_resolucion }}" {{ request('tipo_resolucion') == $tipo->id_tipo_resolucion ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo_resolucion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Año -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                    <select name="anio" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos</option>
                        @php
                            $currentYear = now()->year;
                            $startYear = 2020; // Año inicial
                        @endphp
                        @for($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}" {{ request('anio') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Mes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mes</label>
                    <select name="mes" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos</option>
                        <option value="1" {{ request('mes') == '1' ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ request('mes') == '2' ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ request('mes') == '3' ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ request('mes') == '4' ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ request('mes') == '5' ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ request('mes') == '6' ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ request('mes') == '7' ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ request('mes') == '8' ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ request('mes') == '9' ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ request('mes') == '10' ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ request('mes') == '11' ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ request('mes') == '12' ? 'selected' : '' }}>Diciembre</option>
                    </select>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </button>
                <a href="{{ route('colaborador.resoluciones.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </a>
            </div>
        </form>
    </div>

    <!-- Formulario para redirigir a revisión (oculto) -->
    <form id="formRevisarFirma" method="GET" action="{{ route('colaborador.resoluciones.revisar-firma') }}" style="display: none;">
        <input type="hidden" name="resoluciones_ids" id="resoluciones_ids">
    </form>

    <!-- Lista de resoluciones -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Cabecera -->
        <div class="flex items-center bg-blue-900 text-white text-sm font-semibold uppercase tracking-wide">
            @can('resoluciones.firmar')
            <div class="px-4 py-3">
                <input type="checkbox"
                       id="selectAll"
                       onclick="toggleSelectAll(this)"
                       class="rounded border-gray-400 text-blue-600 focus:ring-blue-500 h-4 w-4">
            </div>
            @endcan
            <div class="flex-1 px-2 py-3">Documentos</div>
            <div class="w-48 px-4 py-3 text-center border-l border-blue-800">Archivos</div>
            <div class="w-32 px-4 py-3 text-right border-l border-blue-800">Acciones</div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($resoluciones as $resolucion)
            @php
                // Los documentos firmados se ven en "Resoluciones Firmadas", no aquí.
                $archivosResolucion = [];
                if ($resolucion->archivo_resolucion) {
                    $archivosResolucion[] = ['ruta' => $resolucion->archivo_resolucion, 'etiqueta' => 'Documento'];
                }
                foreach ($archivosResolucion as &$archivoItem) {
                    $bytes = Storage::disk('public')->exists($archivoItem['ruta'])
                        ? Storage::disk('public')->size($archivoItem['ruta'])
                        : 0;
                    $archivoItem['tamano'] = $bytes
                        ? ($bytes >= 1048576 ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 0) . ' KB')
                        : null;
                }
                unset($archivoItem);
            @endphp
            <div class="flex items-start hover:bg-gray-50 transition">
                @can('resoluciones.firmar')
                <div class="px-4 py-5">
                    <input type="checkbox"
                           class="checkbox-resolucion rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4 mt-1"
                           value="{{ $resolucion->id_resolucion }}"
                           onchange="actualizarContador()">
                </div>
                @endcan

                <!-- Información del documento -->
                <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}" class="flex-1 px-2 py-4 min-w-0">
                    <div class="grid grid-cols-[90px_1fr] gap-x-3 gap-y-1.5 text-sm">
                        <span class="font-bold text-gray-500">Doc:</span>
                        <span class="font-semibold text-gray-900 truncate">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }} N° {{ $resolucion->num_resolucion }}
                        </span>

                        <span class="font-bold text-gray-500">Fecha:</span>
                        <span class="text-gray-700">{{ $resolucion->fecha_resolucion->format('d-m-Y') }}</span>

                        <span class="font-bold text-gray-500">Descripción:</span>
                        <span class="text-gray-600 line-clamp-2">{{ $resolucion->asunto_resolucion }}</span>
                    </div>

                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full
                        {{ $resolucion->estado->nombre_estado === 'Confidencial' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $resolucion->estado->nombre_estado }}
                    </span>
                    @if($resolucion->archivo_firmado)
                    <span class="inline-block mt-2 ml-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        ✓ Firmada
                    </span>
                    @endif
                </a>

                <!-- Archivos -->
                <div class="w-48 px-4 py-4 flex items-center justify-center gap-4 border-l border-gray-100">
                    @forelse($archivosResolucion as $archivoItem)
                    <a href="{{ Storage::url($archivoItem['ruta']) }}" target="_blank" class="flex flex-col items-center text-center hover:opacity-75">
                        <span class="px-2 py-0.5 mb-1 bg-blue-900 text-white text-[10px] font-bold rounded-full">
                            {{ $archivoItem['etiqueta'] }}
                        </span>
                        <svg class="w-9 h-9 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7z" opacity="0.15"/>
                            <path d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H14a1 1 0 01-1-1V3.5zM7 4h5v4a3 3 0 003 3h4v9a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5V4z"/>
                            <text x="12" y="17" font-size="7" font-weight="bold" text-anchor="middle" fill="white">PDF</text>
                        </svg>
                        @if($archivoItem['tamano'])
                        <span class="text-[11px] text-gray-500 mt-1">{{ $archivoItem['tamano'] }}</span>
                        @endif
                    </a>
                    @empty
                    <span class="text-xs text-gray-400">Sin archivo</span>
                    @endforelse
                </div>

                <!-- Acciones -->
                <div class="w-32 px-4 py-5 flex items-center justify-end gap-2">
                    <a href="{{ route('colaborador.resoluciones.show', $resolucion) }}"
                       class="text-blue-600 hover:text-blue-900"
                       title="Ver detalles">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>

                    @can('resoluciones.firmar')
                        @if($resolucion->archivo_resolucion)
                        <a href="{{ route('colaborador.resoluciones.revisar-firma') }}?resoluciones_ids={{ urlencode(json_encode([$resolucion->id_resolucion])) }}"
                           class="text-purple-600 hover:text-purple-900"
                           title="{{ $resolucion->archivo_firmado ? 'Entregar de nuevo' : 'Firmar' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        @endif
                    @endcan

                    @can('resoluciones.editar')
                    <a href="{{ route('colaborador.resoluciones.edit', $resolucion) }}"
                       class="text-yellow-600 hover:text-yellow-900"
                       title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    @endcan
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-medium">No hay resoluciones disponibles</p>
                    <p class="text-sm mt-1">Las resoluciones creadas aparecerán aquí</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($resoluciones->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $resoluciones->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function actualizarContador() {
    const checkboxes = document.querySelectorAll('.checkbox-resolucion:checked');
    const contador = checkboxes.length;
    document.getElementById('contadorSeleccionadas').textContent = contador;
    document.getElementById('btnFirmarSeleccionadas').disabled = contador === 0;
}

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.checkbox-resolucion');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    actualizarContador();
}

function firmarSeleccionadas() {
    const checkboxes = document.querySelectorAll('.checkbox-resolucion:checked');
    if (checkboxes.length === 0) {
        alert('⚠️ Debe seleccionar al menos una resolución');
        return;
    }
    
    // Preparar datos
    const ids = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('resoluciones_ids').value = JSON.stringify(ids);
    
    // Enviar a vista de revisión
    document.getElementById('formRevisarFirma').submit();
}
</script>
@endpush
@endsection