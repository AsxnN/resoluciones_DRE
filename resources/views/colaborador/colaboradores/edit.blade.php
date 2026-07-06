{{-- filepath: resources/views/colaborador/colaboradores/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Colaborador')

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
                            {{ $persona->nombre_completo }} - {{ $persona->num_documento }}
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
                    <select name="id_cargos" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_cargos') border-red-500 @enderror">
                        <option value="">Seleccionar cargo</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id_cargos }}" {{ old('id_cargos', $colaborador->id_cargos) == $cargo->id_cargos ? 'selected' : '' }}>
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
                    <select name="id_unidad" required class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('id_unidad') border-red-500 @enderror">
                        <option value="">Seleccionar unidad</option>
                        @foreach($unidades as $unidad)
                        <option value="{{ $unidad->id_unidad }}" {{ old('id_unidad', $colaborador->id_unidad) == $unidad->id_unidad ? 'selected' : '' }}>
                            {{ $unidad->nombre_unidad }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_unidad')
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
                        <option value="{{ $direccion->id_direcciones }}" {{ old('id_direcciones', $colaborador->id_direcciones) == $direccion->id_direcciones ? 'selected' : '' }}>
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
                        <option value="{{ $dependencia->id_dependencias }}" {{ old('id_dependencia', $colaborador->id_dependencia) == $dependencia->id_dependencias ? 'selected' : '' }}>
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
                        <option value="{{ $especialidad->id_especialidad }}" {{ old('id_especialidad', $colaborador->id_especialidad) == $especialidad->id_especialidad ? 'selected' : '' }}>
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
                        <option value="">Seleccionar tipo de personal</option>
                        @foreach($tiposPersonal as $tipoPersonal)
                        <option value="{{ $tipoPersonal->id_tipo_personal }}" {{ old('id_tipo_personal', $colaborador->id_tipo_personal) == $tipoPersonal->id_tipo_personal ? 'selected' : '' }}>
                            {{ $tipoPersonal->nombre_tipo_personal }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_tipo_personal')
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
