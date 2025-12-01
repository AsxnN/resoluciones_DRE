{{-- filepath: resources/views/admin/privilegios/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestión de Privilegios')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔐 Gestión de Privilegios</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Administra los permisos y accesos de cada usuario del sistema
                </p>
            </div>
            <div class="flex space-x-3">
                <!-- Botón Copiar Permisos -->
                <button type="button" 
                        onclick="document.getElementById('modalCopiarPermisos').showModal()"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copiar Permisos
                </button>
            </div>
        </div>
    </div>

    <!-- Mensajes de Éxito/Error -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">📋 Usuarios del Sistema</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo de Acceso
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permisos Asignados
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Última Sesión
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Usuario -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $usuario->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $usuario->email }}
                                    </div>
                                    @if($usuario->persona)
                                    <div class="text-xs text-gray-400">
                                        DNI: {{ $usuario->persona->num_documento }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Tipo de Acceso -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $colorAcceso = match($usuario->tipo_acceso) {
                                    'admin' => 'bg-red-100 text-red-800',
                                    'colaborador' => 'bg-blue-100 text-blue-800',
                                    'cliente' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorAcceso }}">
                                {{ ucfirst($usuario->tipo_acceso) }}
                            </span>
                        </td>

                        <!-- Permisos Asignados -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $usuario->permissions->count() }}
                                </span>
                                <span class="text-xs text-gray-500 ml-1">permisos</span>
                            </div>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.privilegios.toggle-estado', $usuario) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition {{ $usuario->i_active ? 'bg-green-500' : 'bg-gray-300' }}"
                                        title="{{ $usuario->i_active ? 'Activo - Click para desactivar' : 'Inactivo - Click para activar' }}">
                                    <span class="sr-only">Toggle estado</span>
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition {{ $usuario->i_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>

                        <!-- Última Sesión -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($usuario->ultima_sesion)
                                {{ \Carbon\Carbon::parse($usuario->ultima_sesion)->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Nunca</span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.privilegios.gestionar', $usuario) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                Gestionar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-lg font-medium">No hay usuarios registrados</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $usuarios->links() }}
        </div>
    </div>

    <!-- Modal Copiar Permisos -->
    <dialog id="modalCopiarPermisos" class="rounded-lg shadow-xl backdrop:bg-gray-900/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Copiar Permisos entre Usuarios</h3>
            
            <form action="{{ route('admin.privilegios.copiar-permisos') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Usuario Origen -->
                    <div>
                        <label for="usuario_origen_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Copiar permisos desde:
                        </label>
                        <select name="usuario_origen_id" id="usuario_origen_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar usuario...</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->permissions->count() }} permisos)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Usuario Destino -->
                    <div>
                        <label for="usuario_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Aplicar permisos a:
                        </label>
                        <select name="usuario_destino_id" id="usuario_destino_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar usuario...</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Advertencia -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <p class="text-sm text-yellow-700">
                            ⚠️ Esta acción <strong>reemplazará todos</strong> los permisos actuales del usuario destino.
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('modalCopiarPermisos').close()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                        Copiar Permisos
                    </button>
                </div>
            </form>
        </div>
    </dialog>

</div>

<script>
// Auto-cerrar mensajes después de 5 segundos
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