{{-- filepath: resources/views/colaborador/usuarios/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Usuario')

@section('content')
@php $personaVerificada = $usuario->persona?->obtenido_reniec ?? false; @endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('colaborador.usuarios.index') }}"
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Usuario</h1>
                <p class="text-gray-600 mt-1">{{ $usuario->name }}</p>
            </div>
        </div>

        @if($personaVerificada)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-sm text-blue-700">
                <strong>Identidad verificada con RENIEC.</strong> El nombre no puede modificarse. Solo se puede editar correo, teléfono y dirección.
            </p>
        </div>
        @endif
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <ul class="text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('colaborador.usuarios.update', $usuario) }}">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            <!-- SECCIÓN 1: DATOS DE ACCESO -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">1</span>
                    Datos de Acceso
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Username (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" value="{{ $usuario->username }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                        <p class="mt-1 text-xs text-gray-400">No se puede modificar</p>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $usuario->email) }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tipo de Acceso -->
                    <div>
                        <label for="tipo_acceso" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de Acceso <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_acceso" id="tipo_acceso" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="admin"       {{ old('tipo_acceso', $usuario->tipo_acceso) === 'admin'       ? 'selected' : '' }}>Administrador</option>
                            <option value="colaborador" {{ old('tipo_acceso', $usuario->tipo_acceso) === 'colaborador' ? 'selected' : '' }}>Colaborador</option>
                            <option value="cliente"     {{ old('tipo_acceso', $usuario->tipo_acceso) === 'cliente'     ? 'selected' : '' }}>Cliente</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" id="activo" name="activo" value="1"
                               {{ old('activo', $usuario->i_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                        <label for="activo">
                            <span class="text-sm font-medium text-gray-700">Usuario activo</span>
                            <p class="text-xs text-gray-400">El usuario puede iniciar sesión</p>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: CAMBIAR CONTRASEÑA -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <span class="bg-orange-100 text-orange-600 rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">2</span>
                    Cambiar Contraseña <span class="text-sm font-normal text-gray-400">(opcional)</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" minlength="8"
                               placeholder="Dejar en blanco para mantener la actual"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8"
                               placeholder="Repetir nueva contraseña"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DATOS PERSONALES -->
            @if($usuario->persona)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <span class="bg-green-100 text-green-600 rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">3</span>
                    Datos Personales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <!-- Tipo documento (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Documento</label>
                        <input type="text" value="{{ $usuario->persona->tipo_documento ?? 'DNI' }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                    </div>

                    <!-- Número documento (readonly) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Documento</label>
                        <input type="text" value="{{ $usuario->persona->num_documento ?? '' }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                    </div>

                    <!-- Nombres -->
                    <div>
                        <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                        @if($personaVerificada)
                            <input type="text" value="{{ $usuario->persona->nombres }}" readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                        @else
                            <input type="text" id="nombres" name="nombres" maxlength="100"
                                   value="{{ old('nombres', $usuario->persona->nombres) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @endif
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
                        @if($personaVerificada)
                            <input type="text" value="{{ $usuario->persona->apellido_paterno }}" readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                        @else
                            <input type="text" id="apellido_paterno" name="apellido_paterno" maxlength="100"
                                   value="{{ old('apellido_paterno', $usuario->persona->apellido_paterno) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @endif
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                        @if($personaVerificada)
                            <input type="text" value="{{ $usuario->persona->apellido_materno }}" readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500">
                        @else
                            <input type="text" id="apellido_materno" name="apellido_materno" maxlength="100"
                                   value="{{ old('apellido_materno', $usuario->persona->apellido_materno) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @endif
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" maxlength="20"
                               value="{{ old('telefono', $usuario->persona->telefono) }}"
                               placeholder="Ej: 987654321"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                        <input type="text" id="direccion" name="direccion" maxlength="500"
                               value="{{ old('direccion', $usuario->persona->direccion) }}"
                               placeholder="Dirección registrada"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            @endif

            <!-- SECCIÓN 4: DATOS DEL COLABORADOR -->
            @if($usuario->colaborador)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <span class="bg-purple-100 text-purple-600 rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">4</span>
                    Datos del Colaborador
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label for="id_cargos" class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                        <select name="id_cargos" id="id_cargos"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Cargo::where('i_active', true)->orderBy('nombre_cargo')->get() as $cargo)
                            <option value="{{ $cargo->id_cargos }}" {{ old('id_cargos', $usuario->colaborador->id_cargos) == $cargo->id_cargos ? 'selected' : '' }}>
                                {{ $cargo->nombre_cargo }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_unidad" class="block text-sm font-medium text-gray-700 mb-1">Unidad</label>
                        <select name="id_unidad" id="id_unidad"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Unidad::where('i_active', true)->orderBy('nombre_unidad')->get() as $unidad)
                            <option value="{{ $unidad->id_unidad }}" {{ old('id_unidad', $usuario->colaborador->id_unidad) == $unidad->id_unidad ? 'selected' : '' }}>
                                {{ $unidad->nombre_unidad }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_direcciones" class="block text-sm font-medium text-gray-700 mb-1">Dirección Organizacional</label>
                        <select name="id_direcciones" id="id_direcciones"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get() as $dir)
                            <option value="{{ $dir->id_direcciones }}" {{ old('id_direcciones', $usuario->colaborador->id_direcciones) == $dir->id_direcciones ? 'selected' : '' }}>
                                {{ $dir->nombre_direcciones }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_dependencia" class="block text-sm font-medium text-gray-700 mb-1">Dependencia</label>
                        <select name="id_dependencia" id="id_dependencia"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get() as $dep)
                            <option value="{{ $dep->id_dependencias }}" {{ old('id_dependencia', $usuario->colaborador->id_dependencia) == $dep->id_dependencias ? 'selected' : '' }}>
                                {{ $dep->nombre_dependencia }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_area" class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                        <select name="id_area" id="id_area"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Area::where('i_active', true)->orderBy('nombre_area')->get() as $area)
                            <option value="{{ $area->id_area }}" {{ old('id_area', $usuario->colaborador->id_area) == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nombre_area }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_especialidad" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                        <select name="id_especialidad" id="id_especialidad"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get() as $esp)
                            <option value="{{ $esp->id_especialidad }}" {{ old('id_especialidad', $usuario->colaborador->id_especialidad) == $esp->id_especialidad ? 'selected' : '' }}>
                                {{ $esp->nombre_especialidad }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_tipo_personal" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Personal</label>
                        <select name="id_tipo_personal" id="id_tipo_personal"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get() as $tipo)
                            <option value="{{ $tipo->id_tipo_personal }}" {{ old('id_tipo_personal', $usuario->colaborador->id_tipo_personal) == $tipo->id_tipo_personal ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo_personal }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <input type="checkbox" id="colaborador_activo" name="colaborador_activo" value="1"
                               {{ old('colaborador_activo', $usuario->colaborador->i_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                        <label for="colaborador_activo">
                            <span class="text-sm font-medium text-gray-700">Colaborador activo</span>
                        </label>
                    </div>
                </div>
            </div>
            @endif

            <!-- SECCIÓN 5: ROL ORGANIZACIONAL -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">5</span>
                    Rol Organizacional
                </h3>
                <div>
                    <label for="id_rol" class="block text-sm font-medium text-gray-700 mb-1">Rol en la organización</label>
                    <select id="id_rol" name="id_rol"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sin rol organizacional</option>
                        @foreach(\App\Models\Rol::where('i_active', true)->orderBy('nombre_rol')->get() as $rol)
                        <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                            {{ $rol->nombre_rol }}{{ $rol->descripcion ? ' — ' . $rol->descripcion : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <!-- Botones -->
        <div class="flex justify-between items-center mt-6 pt-4">
            <div class="text-xs text-gray-400 space-y-0.5">
                <p>Creado: {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                <p>Último acceso: {{ $usuario->ultima_sesion ? $usuario->ultima_sesion->format('d/m/Y H:i') : 'Nunca' }}</p>
                @if($usuario->colaborador)
                <p>Código colaborador: {{ $usuario->colaborador->id_colab_dis }}</p>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('colaborador.usuarios.index') }}"
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
