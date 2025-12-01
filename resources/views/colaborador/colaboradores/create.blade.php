{{-- filepath: resources/views/colaborador/colaboradores/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Colaborador')

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
    <span class="text-gray-600 font-medium">Crear</span>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Colaborador</h1>

        <form method="POST" action="{{ route('colaborador.colaboradores.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Persona -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Persona <span class="text-red-500">*</span>
                    </label>
                    <select name="id_persona" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_persona') border-red-500 @enderror">
                        <option value="">Seleccionar persona</option>
                        @foreach($personas as $persona)
                        <option value="{{ $persona->id_persona }}" {{ old('id_persona') == $persona->id_persona ? 'selected' : '' }}>
                            {{ $persona->nombre_completo }} - {{ $persona->dni }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_persona')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo <span class="text-red-500">*</span>
                    </label>
                    <select name="id_cargos" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_cargos') border-red-500 @enderror">
                        <option value="">Seleccionar cargo</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id_cargo }}" {{ old('id_cargos') == $cargo->id_cargo ? 'selected' : '' }}>
                            {{ $cargo->nombre_cargo }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_cargos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unidad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unidad <span class="text-red-500">*</span>
                    </label>
                    <select name="id_unidades" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_unidades') border-red-500 @enderror">
                        <option value="">Seleccionar unidad</option>
                        @foreach($unidades as $unidad)
                        <option value="{{ $unidad->id_unidad }}" {{ old('id_unidades') == $unidad->id_unidad ? 'selected' : '' }}>
                            {{ $unidad->nombre_unidad }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_unidades')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <select name="id_direcciones" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_direcciones') border-red-500 @enderror">
                        <option value="">Seleccionar dirección</option>
                        @foreach($direcciones as $direccion)
                        <option value="{{ $direccion->id_direcciones }}" {{ old('id_direcciones') == $direccion->id_direcciones ? 'selected' : '' }}>
                            {{ $direccion->nombre_direcciones }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_direcciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dependencia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dependencia <span class="text-red-500">*</span>
                    </label>
                    <select name="id_dependencia" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_dependencia') border-red-500 @enderror">
                        <option value="">Seleccionar dependencia</option>
                        @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia->id_dependencia }}" {{ old('id_dependencia') == $dependencia->id_dependencia ? 'selected' : '' }}>
                            {{ $dependencia->nombre_dependencia }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_dependencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Especialidad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Especialidad <span class="text-red-500">*</span>
                    </label>
                    <select name="id_especialidad" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_especialidad') border-red-500 @enderror">
                        <option value="">Seleccionar especialidad</option>
                        @foreach($especialidades as $especialidad)
                        <option value="{{ $especialidad->id_especialidad }}" {{ old('id_especialidad') == $especialidad->id_especialidad ? 'selected' : '' }}>
                            {{ $especialidad->nombre_especialidad }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_especialidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Personal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Personal <span class="text-red-500">*</span>
                    </label>
                    <select name="id_tipo_personal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_tipo_personal') border-red-500 @enderror">
                        <option value="">Seleccionar tipo</option>
                        @foreach($tiposPersonal as $tipo)
                        <option value="{{ $tipo->id_tipo_personal }}" {{ old('id_tipo_personal') == $tipo->id_tipo_personal ? 'selected' : '' }}>
                            {{ $tipo->nombre_tipo_personal }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_tipo_personal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-4 pt-6 border-t mt-6">
                <a href="{{ route('colaborador.colaboradores.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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