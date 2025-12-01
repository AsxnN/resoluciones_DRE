{{-- filepath: resources/views/admin/auditoria/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Registro de Auditoría')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📋 Registro de Auditoría</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Historial completo de acciones realizadas en el sistema
                </p>
            </div>
            <div class="flex space-x-3">
                <!-- Exportar -->
                <a href="{{ route('admin.auditoria.exportar', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar CSV
                </a>

                <!-- Limpiar -->
                <button type="button" 
                        onclick="document.getElementById('modalLimpiar').showModal()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Limpiar Antiguos
                </button>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <p class="text-sm text-green-700">✅ {{ session('success') }}</p>
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Total Registros</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($estadisticas['total']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Hoy -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-green-500 font-medium uppercase">Registros Hoy</p>
                    <p class="text-3xl font-bold text-green-900">{{ number_format($estadisticas['hoy']) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Esta Semana -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-purple-500 font-medium uppercase">Esta Semana</p>
                    <p class="text-3xl font-bold text-purple-900">{{ number_format($estadisticas['semana']) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form action="{{ route('admin.auditoria.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Usuario -->
                <div>
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <select name="usuario_id" id="usuario_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Acción -->
                <div>
                    <label for="accion" class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
                    <select name="accion" id="accion" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="login" {{ request('accion') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('accion') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="actualizar" {{ request('accion') == 'actualizar' ? 'selected' : '' }}>Actualizar</option>
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div>
                    <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Fecha Hasta -->
                <div>
                    <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Buscar -->
                <div class="md:col-span-3">
                    <label for="buscar" class="block text-sm font-medium text-gray-700 mb-1">Buscar en Descripción</label>
                    <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Buscar..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Botones -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('admin.auditoria.index') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        ↻
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Auditoría -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($auditorias as $auditoria)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Fecha/Hora -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $auditoria->fecha_accion->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $auditoria->fecha_accion->format('H:i:s') }}</div>
                        </td>

                        <!-- Usuario -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($auditoria->usuario)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs mr-2">
                                    {{ strtoupper(substr($auditoria->usuario->name, 0, 2)) }}
                                </div>
                                <div class="text-sm text-gray-900">{{ $auditoria->usuario->name }}</div>
                            </div>
                            @else
                            <span class="text-sm text-gray-500 italic">Sistema</span>
                            @endif
                        </td>

                        <!-- Acción -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $auditoria->accion == 'login' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $auditoria->accion == 'logout' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $auditoria->accion == 'actualizar' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ $auditoria->accion_formateada }}
                            </span>
                        </td>

                        <!-- Descripción -->
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="max-w-md truncate">{{ $auditoria->descripcion }}</div>
                        </td>

                        <!-- IP -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $auditoria->ip_address }}
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.auditoria.show', $auditoria->id_auditoria) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No hay registros de auditoría
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $auditorias->links() }}
        </div>
    </div>

    <!-- Modal Limpiar -->
    <dialog id="modalLimpiar" class="rounded-lg shadow-xl backdrop:bg-gray-900/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Limpiar Registros Antiguos</h3>
            
            <form action="{{ route('admin.auditoria.limpiar') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="space-y-4">
                    <div>
                        <label for="dias" class="block text-sm font-medium text-gray-700 mb-2">
                            Eliminar registros anteriores a:
                        </label>
                        <select name="dias" id="dias" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="30">30 días</option>
                            <option value="60">60 días</option>
                            <option value="90" selected>90 días</option>
                            <option value="180">180 días</option>
                            <option value="365">1 año</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('modalLimpiar').close()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Eliminar Registros
                    </button>
                </div>
            </form>
        </div>
    </dialog>

</div>

<script>
// Auto-cerrar mensajes
setTimeout(() => {
    const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection