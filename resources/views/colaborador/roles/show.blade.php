{{-- filepath: resources/views/colaborador/roles/show.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Detalle del Rol')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('colaborador.roles.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🛡️ {{ $rol->name }}</h1>
                    <p class="text-gray-600 mt-1">Detalles del rol y permisos asignados</p>
                </div>
            </div>
            @can('roles.editar')
            <a href="{{ route('colaborador.roles.edit', $rol) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar Rol
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Rol -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">📋 Información del Rol</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nombre</label>
                        <p class="text-base text-gray-900 mt-1">{{ $rol->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Guard</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $rol->guard_name === 'colaborador' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($rol->guard_name) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Creado</label>
                        <p class="text-base text-gray-900 mt-1">{{ $rol->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Última actualización</label>
                        <p class="text-base text-gray-900 mt-1">{{ $rol->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">📊 Estadísticas</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Usuarios asignados</span>
                        <span class="text-lg font-bold text-blue-600">{{ $rol->users->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Permisos asignados</span>
                        <span class="text-lg font-bold text-purple-600">{{ $rol->permissions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permisos -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">🔐 Permisos Asignados</h2>
                
                @if($rol->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($rol->permissions as $permiso)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ $permiso->name }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin permisos</h3>
                        <p class="mt-1 text-sm text-gray-500">Este rol no tiene permisos asignados</p>
                    </div>
                @endif
            </div>

            <!-- Usuarios con este rol -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">👥 Usuarios con este Rol</h2>
                
                @if($rol->users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rol->users as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $usuario->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $usuario->email }}</td>
                                    <td class="px-4 py-3">
                                        @if($usuario->i_active)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin usuarios</h3>
                        <p class="mt-1 text-sm text-gray-500">Ningún usuario tiene asignado este rol</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection