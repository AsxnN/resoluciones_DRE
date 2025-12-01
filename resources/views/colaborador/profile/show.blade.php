@extends('layouts.colaborador')

@section('title', 'Mi Perfil')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Mi Perfil</span>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                            {{ ucfirst(auth()->user()->tipo_acceso) }}
                        </span>
                        @if(auth()->user()->i_active)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            ✓ Activo
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('colaborador.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                ✏️ Editar Perfil
            </a>
        </div>
    </div>

    <!-- Información Personal -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Información Personal
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre Completo</label>
                <p class="text-gray-900 font-medium">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Correo Electrónico</label>
                <p class="text-gray-900 font-medium">{{ auth()->user()->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Acceso</label>
                <p class="text-gray-900 font-medium capitalize">{{ auth()->user()->tipo_acceso }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Última Sesión</label>
                <p class="text-gray-900 font-medium">
                    {{ auth()->user()->ultima_sesion ? auth()->user()->ultima_sesion->format('d/m/Y H:i') : 'Nunca' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Actividad -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Estadísticas de Actividad
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-blue-600 font-medium mb-1">Resoluciones Creadas</p>
                <p class="text-2xl font-bold text-blue-900">
                    {{ \App\Models\Resolucion::where('id_usuario', auth()->id())->count() }}
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-green-600 font-medium mb-1">Firmas Realizadas</p>
                <p class="text-2xl font-bold text-green-900">
                    {{ \App\Models\Resolucion::where('id_usuario_firma', auth()->id())->count() }}
                </p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-sm text-purple-600 font-medium mb-1">Firmas Pendientes</p>
                <p class="text-2xl font-bold text-purple-900">
                    {{ \App\Models\ColaFirma::where('id_usuario_firmante', auth()->id())
                        ->whereHas('estadoFirma', fn($q) => $q->where('nombre_estado', 'Pendiente'))
                        ->count() }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection