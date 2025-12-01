{{-- filepath: resources/views/colaborador/colaboradores/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Colaborador')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <a href="{{ route('colaborador.colaboradores.index') }}" class="text-gray-600 hover:text-gray-900">Colaboradores</a>
</li>
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Editar</span>
</li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Colaborador</h1>

        <form method="POST" action="{{ route('colaborador.colaboradores.update', $colaborador) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Persona -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Persona <span class="text-red-500">*</span>
                    </label>
                    <select name="id_persona" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_persona') border-red-500 @enderror">
                        <option value="">Seleccionar persona</option>
                        @foreach($personas as $persona)
                        <option value="{{ $persona->id_persona }}" {{ old('id_persona', $colaborador->id_persona) == $persona->id_persona ? 'selected' : '' }}>
                            {{ $persona->nombre_completo }} - {{ $persona->dni }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Área -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Área <span class="text-red-500">*</span>
                    </label>
                    <select name="id_area" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_area') border-red-500 @enderror">
                        <option value="">Seleccionar área</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id_area }}" {{ old('id_area', $colaborador->id_area) == $area->id_area ? 'selected' : '' }}>
                            {{ $area->nombre_area }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo <span class="text-red-500">*</span>
                    </label>
                    <select name="id_cargo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_cargo') border-red-500 @enderror">
                        <option value="">Seleccionar cargo</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id_cargo }}" {{ old('id_cargo', $colaborador->id_cargo) == $cargo->id_cargo ? 'selected' : '' }}>
                            {{ $cargo->nombre_cargo }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_cargo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="i_active" value="1" {{ old('i_active', $colaborador->i_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Colaborador Activo</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end gap-4 pt-6 border-t mt-6">
                <a href="{{ route('colaborador.colaboradores.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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