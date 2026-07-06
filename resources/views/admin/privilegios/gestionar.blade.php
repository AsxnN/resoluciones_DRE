{{-- filepath: resources/views/admin/privilegios/gestionar.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestionar Permisos - ' . $usuario->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header con Breadcrumb -->
    <div class="mb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.privilegios.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            Privilegios
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $usuario->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header Principal -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-2xl mr-4">
                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestionar Permisos</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Usuario: <span class="font-semibold">{{ $usuario->name }}</span> 
                        <span class="mx-2">•</span> 
                        <span class="text-gray-500">{{ $usuario->email }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <!-- Copiar Permisos -->
                <button type="button" 
                        onclick="document.getElementById('modalCopiarPermisos').showModal()"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring ring-purple-300 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copiar de otro usuario
                </button>
                
                <!-- Volver -->
                <a href="{{ route('admin.privilegios.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring ring-gray-300 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <p class="text-sm text-green-700">✅ {{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <p class="text-sm text-red-700">❌ {{ session('error') }}</p>
    </div>
    @endif

    <!-- Formulario de Permisos -->
    <form action="{{ route('admin.privilegios.actualizar', $usuario) }}" method="POST" id="formPermisos">
        @csrf
        @method('PUT')

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-600 font-medium">Total Permisos</p>
                        <p class="text-2xl font-bold text-blue-900" id="totalPermisos">{{ count($permisosUsuario) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-600 font-medium">Módulos Activos</p>
                        <p class="text-2xl font-bold text-green-900">{{ $modulos->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-purple-600 font-medium">Tipo de Acceso</p>
                        <p class="text-lg font-bold text-purple-900">{{ ucfirst($usuario->tipo_acceso) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-600 font-medium">Estado</p>
                        <p class="text-lg font-bold text-yellow-900">{{ $usuario->i_active ? 'Activo' : 'Inactivo' }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulos y Permisos -->
        <div class="space-y-6">
            @foreach($modulos as $modulo)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-{{ $modulo->tipo_modulo === 'admin' ? 'red' : ($modulo->tipo_modulo === 'colaborador' ? 'blue' : 'green') }}-500">

                <!-- Header del Módulo -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Icono del Módulo -->
                        <div class="p-2 rounded-lg bg-{{ $modulo->tipo_modulo === 'admin' ? 'red' : ($modulo->tipo_modulo === 'colaborador' ? 'blue' : 'green') }}-100">
                            <svg class="w-6 h-6 text-{{ $modulo->tipo_modulo === 'admin' ? 'red' : ($modulo->tipo_modulo === 'colaborador' ? 'blue' : 'green') }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $modulo->nombre_modulo }}</h3>
                            @if($modulo->descripcion)
                            <p class="text-xs text-gray-500">{{ $modulo->descripcion }}</p>
                            @endif
                        </div>

                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $modulo->tipo_modulo === 'admin' ? 'red' : ($modulo->tipo_modulo === 'colaborador' ? 'blue' : 'green') }}-100 text-{{ $modulo->tipo_modulo === 'admin' ? 'red' : ($modulo->tipo_modulo === 'colaborador' ? 'blue' : 'green') }}-800">
                            {{ ucfirst($modulo->tipo_modulo) }}
                        </span>
                    </div>

                    <!-- Acciones del Módulo -->
                    <div class="flex items-center space-x-2">
                        <!-- Contador de permisos -->
                        <span class="text-sm text-gray-600">
                            <span class="font-bold text-gray-900" data-modulo-counter="{{ $modulo->id_modulo }}">
                                {{ $modulo->permisos->whereIn('id', $permisosUsuario)->count() }}
                            </span>
                            / {{ $modulo->permisos->count() }}
                        </span>

                        <!-- Seleccionar/Deseleccionar todo -->
                        <button type="button"
                                onclick="toggleModulo({{ $modulo->id_modulo }})"
                                class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Marcar/Desmarcar Todo
                        </button>
                    </div>
                </div>

                <!-- Permisos del Módulo -->
                <div class="p-6">
                    @if($modulo->permisos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($modulo->permisos as $permiso)
                        <label class="flex items-center p-3 rounded-lg border-2 cursor-pointer transition
                                      {{ in_array($permiso->id, $permisosUsuario) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50' }}">
                            <input type="checkbox"
                                   name="permisos[]"
                                   value="{{ $permiso->id }}"
                                   data-modulo="{{ $modulo->id_modulo }}"
                                   {{ in_array($permiso->id, $permisosUsuario) ? 'checked' : '' }}
                                   onchange="actualizarContador({{ $modulo->id_modulo }})"
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">

                            <div class="ml-3 flex-1">
                                <span class="text-sm font-medium text-gray-900 block">
                                    {{ ucfirst(str_replace('_', ' ', $permiso->name)) }}
                                </span>
                                @if($permiso->descripcion)
                                <span class="text-xs text-gray-500 block">
                                    {{ $permiso->descripcion }}
                                </span>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <p class="text-center text-gray-400 py-4">Este módulo no tiene permisos configurados</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Botones de Acción -->
        <div class="mt-8 flex items-center justify-between bg-gray-50 p-6 rounded-lg">
            <a href="{{ route('admin.privilegios.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring ring-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar Permisos
            </button>
        </div>
    </form>

    <!-- Modal Copiar Permisos -->
    <dialog id="modalCopiarPermisos" class="rounded-lg shadow-xl backdrop:bg-gray-900/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Copiar Permisos de Otro Usuario</h3>
            
            <form action="{{ route('admin.privilegios.copiar-permisos') }}" method="POST">
                @csrf
                <input type="hidden" name="usuario_destino_id" value="{{ $usuario->id }}">
                
                <div class="space-y-4">
                    <div>
                        <label for="usuario_origen_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Copiar permisos desde:
                        </label>
                        <select name="usuario_origen_id" id="usuario_origen_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar usuario...</option>
                            @foreach($otrosUsuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->permissions->count() }} permisos)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <p class="text-sm text-yellow-700">
                            ⚠️ Esto <strong>reemplazará todos</strong> los permisos actuales de <strong>{{ $usuario->name }}</strong>
                        </p>
                    </div>
                </div>

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
// Actualizar contador de permisos por módulo
function actualizarContador(idModulo) {
    const checkboxes = document.querySelectorAll(`input[data-modulo="${idModulo}"]:checked`);
    const counter = document.querySelector(`[data-modulo-counter="${idModulo}"]`);
    if (counter) {
        counter.textContent = checkboxes.length;
    }

    // Actualizar total
    const totalChecked = document.querySelectorAll('input[name="permisos[]"]:checked').length;
    document.getElementById('totalPermisos').textContent = totalChecked;
}

// Marcar/Desmarcar todos los permisos de un módulo
function toggleModulo(idModulo) {
    const checkboxes = document.querySelectorAll(`input[data-modulo="${idModulo}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    actualizarContador(idModulo);
}

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