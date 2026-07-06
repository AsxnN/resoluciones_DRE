{{-- filepath: resources/views/admin/clientes/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Clientes</h1>
        <p class="mt-1 text-sm text-gray-600">Personas externas registradas en el sistema</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <p class="text-sm text-green-700">✅ {{ session('success') }}</p>
    </div>
    @endif

    <!-- Tarjetas estadísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-gray-500 font-medium uppercase">Total</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totales['total']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-green-500 font-medium uppercase">Activos</p>
            <p class="text-3xl font-bold text-green-700">{{ number_format($totales['activos']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-blue-500 font-medium uppercase">Con cuenta</p>
            <p class="text-3xl font-bold text-blue-700">{{ number_format($totales['con_cuenta']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-yellow-600 font-medium uppercase">Sin cuenta</p>
            <p class="text-3xl font-bold text-yellow-700">{{ number_format($totales['sin_cuenta']) }}</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('admin.clientes.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Buscar -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                           placeholder="Nombre, DNI o correo..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Todos</option>
                        <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <!-- Tiene cuenta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cuenta de acceso</label>
                    <select name="tiene_cuenta" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Todos</option>
                        <option value="si" {{ request('tiene_cuenta') === 'si' ? 'selected' : '' }}>Con cuenta</option>
                        <option value="no" {{ request('tiene_cuenta') === 'no' ? 'selected' : '' }}>Sin cuenta</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition">
                    Filtrar
                </button>
                <a href="{{ route('admin.clientes.index') }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-200 transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cuenta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- Persona -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($cliente->nombres, 0, 1)) }}{{ strtoupper(substr($cliente->apellido_paterno, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}, {{ $cliente->nombres }}
                                    </p>
                                    @if($cliente->user?->username)
                                    <p class="text-xs text-gray-400">@{{ $cliente->user->username }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- DNI -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $cliente->num_documento ?? '—' }}
                        </td>

                        <!-- Correo -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $cliente->correo ?? '—' }}
                        </td>

                        <!-- Teléfono -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $cliente->telefono ?? '—' }}
                        </td>

                        <!-- Cuenta -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($cliente->user)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Con cuenta
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Sin cuenta
                                </span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($cliente->i_active)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Inactivo</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No se encontraron clientes con los filtros aplicados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($clientes->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $clientes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
