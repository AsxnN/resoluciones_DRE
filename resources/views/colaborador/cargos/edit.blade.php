{{-- filepath: resources/views/colaborador/cargos/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Cargo')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <a href="{{ route('colaborador.cargos.index') }}" class="text-gray-600 hover:text-gray-900">Cargos</a>
</li>
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Editar</span>
</li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Cargo</h1>

        <form method="POST" action="{{ route('colaborador.cargos.update', $cargo) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Cargo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_cargo" value="{{ old('nombre_cargo', $cargo->nombre_cargo) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('nombre_cargo') border-red-500 @enderror">
                @error('nombre_cargo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="i_active" value="1" {{ old('i_active', $cargo->i_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Cargo Activo</span>
                </label>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('colaborador.cargos.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection