{{-- filepath: resources/views/colaborador/dependencias/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Dependencia')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <a href="{{ route('colaborador.dependencias.index') }}" class="text-gray-600 hover:text-gray-900">Dependencias</a>
</li>
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Crear</span>
</li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Dependencia</h1>

        <form method="POST" action="{{ route('colaborador.dependencias.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Código de Dependencia <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="cod_dependencia" 
                       value="{{ old('cod_dependencia') }}" 
                       placeholder="Ej: DEP-001"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('cod_dependencia') border-red-500 @enderror">
                @error('cod_dependencia')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Código único de la dependencia</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre de la Dependencia <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nombre_dependencia" 
                       value="{{ old('nombre_dependencia') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('nombre_dependencia') border-red-500 @enderror">
                @error('nombre_dependencia')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('colaborador.dependencias.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection