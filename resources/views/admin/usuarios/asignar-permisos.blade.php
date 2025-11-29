{{-- filepath: resources/views/admin/usuarios/asignar-permisos.blade.php --}}
@extends('layouts.admin')

@section('title', 'Asignar Permisos')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.usuarios.show', $usuario) }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔐 Asignar Permisos</h1>
                <p class="text-gray-600 mt-1">Usuario: {{ $usuario->name }}</p>
            </div>
        </div>
    </div>

    <!-- Información del Usuario -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-16 w-16">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br 
                    {{ $usuario->tipo_acceso === 'admin' ? 'from-red-400 to-red-600' : 
                       ($usuario->tipo_acceso === 'colaborador' ? 'from-blue-400 to-blue-600' : 
                       'from-green-400 to-green-600') }} 
                    flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                </div>
            </div>
            <div class="ml-6">
                <h3 class="text-xl font-bold text-gray-900">{{ $usuario->name }}</h3>
                <p class="text-gray-600">{{ $usuario->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                    {{ $usuario->tipo_acceso === 'admin' ? 'bg-red-100 text-red-800' : 
                       ($usuario->tipo_acceso === 'colaborador' ? 'bg-blue-100 text-blue-800' : 
                       'bg-green-100 text-green-800') }}">
                    {{ ucfirst($usuario->tipo_acceso) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Formulario de Permisos -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('admin.usuarios.guardar-permisos', $usuario) }}">
            @csrf
            @method('PUT')

            <!-- Permisos por Módulo -->
            @foreach($modulos as $modulo)
            <div class="mb-8 pb-8 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $modulo->nombre_modulo }}</h3>
                        <p class="text-sm text-gray-500">{{ $modulo->descripcion_modulo }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ml-16">
                    @foreach($modulo->permisos as $permiso)
                    <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" 
                               name="permisos[]" 
                               value="{{ $permiso->id_permiso }}"
                               {{ $usuario->permisos->contains($permiso->id_permiso) ? 'checked' : '' }}
                               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $permiso->nombre_permiso }}</p>
                            <p class="text-xs text-gray-500">{{ $permiso->descripcion_permiso }}</p>
                        </div>
                    </label>
                    @endforeach

                    @if($modulo->permisos->count() === 0)
                    <div class="col-span-3 text-center py-4 text-gray-500 text-sm">
                        No hay permisos disponibles para este módulo
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.usuarios.show', $usuario) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ✓ Guardar Permisos
                </button>
            </div>
        </form>
    </div>

    <!-- Permisos Actuales -->
    @if($usuario->permisos->count() > 0)
    <div class="mt-6 bg-purple-50 border border-purple-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-purple-900 mb-4">📋 Permisos Actuales ({{ $usuario->permisos->count() }})</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($usuario->permisos as $permiso)
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                    {{ $permiso->nombre_permiso }}
                </span>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection