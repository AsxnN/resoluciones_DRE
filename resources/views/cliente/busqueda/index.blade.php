{{-- filepath: resources/views/cliente/busqueda/index.blade.php --}}
@extends('layouts.cliente')

@section('title', 'Buscar Resoluciones')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">🔍 Buscar Resoluciones</h1>
        <p class="text-gray-600 mt-1">Busca resoluciones por número, DNI o nombre</p>
    </div>

    <!-- Formulario de Búsqueda -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <form method="GET" action="{{ route('cliente.busqueda.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Número de Resolución -->
                <div>
                    <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Resolución
                    </label>
                    <input type="text" 
                           id="numero" 
                           name="numero" 
                           value="{{ request('numero') }}"
                           placeholder="Ej: RD-001-2025"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- DNI -->
                <div>
                    <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                        DNI
                    </label>
                    <input type="text" 
                           id="dni" 
                           name="dni" 
                           value="{{ request('dni') }}"
                           placeholder="Ej: 12345678"
                           maxlength="8"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre o Apellidos
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ request('nombre') }}"
                           placeholder="Ej: Juan Pérez"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    🔍 Buscar
                </button>
                <a href="{{ route('cliente.busqueda.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    🔄 Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    @if(isset($resultados))
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                📊 Resultados de Búsqueda
                <span class="text-sm font-normal text-gray-600">({{ $resultados->total() }} encontrados)</span>
            </h2>
        </div>

        @if($resultados->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($resultados as $resolucion)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $resolucion->num_resolucion }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($resolucion->asunto_resolucion, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $resolucion->fecha_resolucion->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                {{ $resolucion->estado->nombre_estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('cliente.resoluciones.show', $resolucion) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    👁️ Ver
                                </a>
                                @if($resolucion->archivo_firmado)
                                <a href="{{ Storage::url($resolucion->archivo_firmado) }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-900">
                                    📥 PDF
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            @if($resultados->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $resultados->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron resultados</h3>
                <p class="mt-1 text-sm text-gray-500">Intenta con otros criterios de búsqueda</p>
            </div>
        @endif
    </div>
    @endif

    <!-- Ayuda -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Consejos de Búsqueda</h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li>• Puedes buscar por número completo o parcial de resolución</li>
            <li>• Si buscas por DNI, ingresa los 8 dígitos</li>
            <li>• La búsqueda por nombre encuentra coincidencias parciales</li>
            <li>• Puedes combinar varios criterios de búsqueda</li>
        </ul>
    </div>
</div>
@endsection