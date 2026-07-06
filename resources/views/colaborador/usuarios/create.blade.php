{{-- filepath: resources/views/colaborador/usuarios/create.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Crear Usuario Completo')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
                <p class="text-gray-600 mt-1">Complete todos los datos: Usuario, Persona y Colaborador</p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.usuarios.store') }}">
            @csrf

            <div class="space-y-8">
                <!-- SECCIÓN 1: DATOS DE ACCESO -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">1</span>
                        Datos de Acceso al Sistema
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username (Generado automáticamente - OCULTO) -->
                    <input type="hidden" name="username" id="username" value="">

                    <!-- Mensaje informativo -->
                    <div class="md:col-span-2">
                        <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Usuario generado automáticamente</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    El username se generará automáticamente basado en el nombre y apellido (ejm: <strong>jmartinez</strong>)
                                </p>
                            </div>
                        </div>
                    </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   placeholder="usuario@dre.gob.pe">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Repita la contraseña">
                        </div>

                        <!-- Tipo de Acceso -->
                        <div class="md:col-span-2">
                            <label for="tipo_acceso" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Acceso <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_acceso" 
                                    id="tipo_acceso" 
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_acceso') border-red-500 @enderror">
                                <option value="">Seleccione...</option>
                                <option value="admin" {{ old('tipo_acceso') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="colaborador" {{ old('tipo_acceso') == 'colaborador' ? 'selected' : '' }}>Colaborador</option>
                                <option value="cliente" {{ old('tipo_acceso') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            </select>
                            @error('tipo_acceso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: DATOS PERSONALES -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">2</span>
                        Datos Personales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Tipo Documento -->
                        <div>
                            <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo Documento <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_documento" 
                                    id="tipo_documento" 
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror">
                                <option value="DNI" {{ old('tipo_documento', 'DNI') == 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                                <option value="Pasaporte" {{ old('tipo_documento') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            @error('tipo_documento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Número Documento con botón de búsqueda -->
                        <div class="md:col-span-2">
                            <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Número Documento <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       name="num_documento" 
                                       id="num_documento" 
                                       value="{{ old('num_documento') }}"
                                       required
                                       maxlength="20"
                                       class="flex-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('num_documento') border-red-500 @enderror"
                                       placeholder="12345678">
                                
                                <button type="button" 
                                        id="btnBuscarDNI" 
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <span id="btnText">Buscar</span>
                                        <svg id="loadingSpinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            @error('num_documento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-blue-600" id="ayudaDNI">Si es DNI, presiona "Buscar" para autocompletar datos desde RENIEC</p>
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                    name="nombres" 
                                    id="nombres" 
                                    value="{{ old('nombres') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror"
                                    placeholder="Juan Carlos">
                            </div>
                            @error('nombres')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellido Paterno -->
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Paterno <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                    name="apellido_paterno" 
                                    id="apellido_paterno" 
                                    value="{{ old('apellido_paterno') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_paterno') border-red-500 @enderror"
                                    placeholder="Pérez">
                            </div>
                            @error('apellido_paterno')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellido Materno -->
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Materno
                            </label>
                            <div class="relative">
                                <input type="text" 
                                    name="apellido_materno" 
                                    id="apellido_materno" 
                                    value="{{ old('apellido_materno') }}"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('apellido_materno') border-red-500 @enderror"
                                    placeholder="García">
                            </div>
                            @error('apellido_materno')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono" 
                                   value="{{ old('telefono') }}"
                                   maxlength="20"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror"
                                   placeholder="999888777">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Dirección -->
                        <div class="md:col-span-3">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="direccion" 
                                      id="direccion" 
                                      rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('direccion') border-red-500 @enderror"
                                      placeholder="Dirección completa...">{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: DATOS DEL COLABORADOR -->
                <div id="seccion-colaborador" class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-purple-100 text-purple-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                        Datos del Colaborador (Opcional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cargo -->
                        <div>
                            <label for="id_cargos" class="block text-sm font-medium text-gray-700 mb-2">
                                Cargo
                            </label>
                            <select name="id_cargos" 
                                    id="id_cargos"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($cargos as $cargo)
                                <option value="{{ $cargo->id_cargos }}" {{ old('id_cargos') == $cargo->id_cargos ? 'selected' : '' }}>
                                    {{ $cargo->nombre_cargo }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Unidad -->
                        <div>
                            <label for="id_unidad" class="block text-sm font-medium text-gray-700 mb-2">
                                Unidad
                            </label>
                            <select name="id_unidad" 
                                    id="id_unidad"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id_unidad }}" {{ old('id_unidad') == $unidad->id_unidad ? 'selected' : '' }}>
                                    {{ $unidad->nombre_unidad }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="id_direcciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <select name="id_direcciones" 
                                    id="id_direcciones"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($direcciones as $direccion)
                                <option value="{{ $direccion->id_direcciones }}" {{ old('id_direcciones') == $direccion->id_direcciones ? 'selected' : '' }}>
                                    {{ $direccion->nombre_direcciones }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dependencia -->
                        <div>
                            <label for="id_dependencia" class="block text-sm font-medium text-gray-700 mb-2">
                                Dependencia
                            </label>
                            <select name="id_dependencia" 
                                    id="id_dependencia"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($dependencias as $dependencia)
                                <option value="{{ $dependencia->id_dependencias }}" {{ old('id_dependencia') == $dependencia->id_dependencias ? 'selected' : '' }}>
                                    {{ $dependencia->nombre_dependencia }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Área -->
                        <div>
                            <label for="id_area" class="block text-sm font-medium text-gray-700 mb-2">
                                Área
                            </label>
                            <select name="id_area" 
                                    id="id_area"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id_area }}" {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                    {{ $area->nombre_area }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Especialidad -->
                        <div>
                            <label for="id_especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                                Especialidad
                            </label>
                            <select name="id_especialidad" 
                                    id="id_especialidad"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->id_especialidad }}" {{ old('id_especialidad') == $especialidad->id_especialidad ? 'selected' : '' }}>
                                    {{ $especialidad->nombre_especialidad }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo Personal -->
                        <div>
                            <label for="id_tipo_personal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Personal
                            </label>
                            <select name="id_tipo_personal" 
                                    id="id_tipo_personal"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($tiposPersonal as $tipo)
                                <option value="{{ $tipo->id_tipo_personal }}" {{ old('id_tipo_personal') == $tipo->id_tipo_personal ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo_personal }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rol Organizacional - SE GUARDA EN USERS -->
                        <div>
                            <label for="id_rol" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol Organizacional
                            </label>
                            <select name="id_rol" 
                                    id="id_rol"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($rolesOrganizacionales as $rol)
                                <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->nombre_rol }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: CONFIGURACIÓN -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-orange-100 text-orange-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">4</span>
                        Configuración
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Usuario Activo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   name="i_active" 
                                   id="i_active" 
                                   value="1"
                                   {{ old('i_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="i_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Usuario Activo</span>
                                <p class="text-xs text-gray-500">El usuario podrá iniciar sesión en el sistema</p>
                            </label>
                        </div>

                        <!-- Email Verificado -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   name="email_verified" 
                                   id="email_verified" 
                                   value="1"
                                   {{ old('email_verified', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="email_verified" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Email Verificado</span>
                                <p class="text-xs text-gray-500">Marcar el correo como verificado automáticamente</p>
                            </label>
                        </div>

                        <!-- Colaborador Activo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   name="colaborador_activo" 
                                   id="colaborador_activo" 
                                   value="1"
                                   {{ old('colaborador_activo', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="colaborador_activo" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Colaborador Activo</span>
                                <p class="text-xs text-gray-500">Activar el registro del colaborador</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.usuarios.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    Crear Usuario Completo
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
        <li>Se crearán automáticamente: <strong>Usuario</strong>, <strong>Persona</strong> y el registro del tipo correspondiente (<strong>Colaborador</strong> o <strong>Cliente</strong>)</li>
                        <li><strong>Admin:</strong> Solo crea Usuario + Persona (acceso al panel de permisos)</li>
                        <li><strong>Colaborador:</strong> Crea además un registro en la tabla colaborador (con cargo, unidad, etc.)</li>
                        <li><strong>Cliente:</strong> Crea además un registro en la tabla cliente (acceso al portal de resoluciones)</li>
                        <li>El <strong>username</strong> será único para iniciar sesión</li>
                        <li>El <strong>email</strong> se compartirá entre usuario y persona</li>
                        <li>Los datos del colaborador (cargo, unidad, etc.) son opcionales</li>
                        <li>El código del colaborador se generará automáticamente</li>
                        <li>Los roles y permisos de Spatie se asignan desde el panel de administración</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const numDocumentoInput = document.getElementById('num_documento');
    const tipoDocumentoSelect = document.getElementById('tipo_documento');
    const nombresInput = document.getElementById('nombres');
    const apellidoPaternoInput = document.getElementById('apellido_paterno');
    const apellidoMaternoInput = document.getElementById('apellido_materno');
    const usernameInput = document.getElementById('username');
    const btnBuscar = document.getElementById('btnBuscarDNI');
    const btnText = document.getElementById('btnText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    let datosReniecCargados = false;

    // ── Toggle sección colaborador según tipo de acceso ──
    const tipoAccesoSelect = document.getElementById('tipo_acceso');
    const seccionColaborador = document.getElementById('seccion-colaborador');

    function toggleSeccionColaborador() {
        const tipo = tipoAccesoSelect.value;
        if (tipo === 'colaborador') {
            seccionColaborador.classList.remove('hidden');
        } else {
            seccionColaborador.classList.add('hidden');
        }
    }

    tipoAccesoSelect.addEventListener('change', toggleSeccionColaborador);
    // Ejecutar al cargar (para manejar old() con valor previo)
    toggleSeccionColaborador();


    // Evento del botón buscar
    btnBuscar.addEventListener('click', buscarEnReniec);

    // También buscar con Enter en el campo DNI
    numDocumentoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && tipoDocumentoSelect.value === 'DNI') {
            e.preventDefault();
            buscarEnReniec();
        }
    });

    // Si cambia el DNI después de haber buscado, habilitar campos nuevamente
    numDocumentoInput.addEventListener('input', function() {
        if (datosReniecCargados) {
            habilitarCampos();
            datosReniecCargados = false;
        }
    });

    function buscarEnReniec() {
        const dni = numDocumentoInput.value.trim();

        // Validaciones
        if (tipoDocumentoSelect.value !== 'DNI') {
            mostrarAlerta('Esta función solo está disponible para DNI', 'warning');
            return;
        }

        if (!dni) {
            mostrarAlerta('Por favor ingrese un número de DNI', 'error');
            numDocumentoInput.focus();
            return;
        }

        if (dni.length !== 8 || !/^\d+$/.test(dni)) {
            mostrarAlerta('El DNI debe tener 8 dígitos', 'error');
            numDocumentoInput.focus();
            return;
        }

        // Deshabilitar botón y mostrar loading
        btnBuscar.disabled = true;
        btnText.textContent = 'Buscando...';
        loadingSpinner.classList.remove('hidden');

        console.log('🔍 Consultando DNI:', dni);

        // Llamada AJAX a RENIEC usando la misma ruta que paso1.blade
        fetch('{{ route("colaborador.reniec.consultar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ dni: dni })
        })
        .then(response => {
            console.log('📡 Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('📦 Data recibida:', data);
            
            if (data.success) {
                console.log('✅ Datos obtenidos correctamente');
                
                // Llenar campos automáticamente (los nombres vienen en snake_case desde ResolucionController)
                nombresInput.value = data.nombres || '';
                apellidoPaternoInput.value = data.apellido_paterno || '';
                apellidoMaternoInput.value = data.apellido_materno || '';

                // Generar username automáticamente
                generarUsername();

                // BLOQUEAR campos (readonly en lugar de disabled)
                bloquearCampos();
                datosReniecCargados = true;

                // Destacar visualmente los campos llenados
                [nombresInput, apellidoPaternoInput, apellidoMaternoInput].forEach(input => {
                    input.classList.add('bg-green-50', 'border-green-500');
                });

                mostrarAlerta('Datos obtenidos de RENIEC. Campos bloqueados.', 'success');
                
                // Hacer focus en el teléfono
                setTimeout(() => {
                    document.getElementById('telefono').focus();
                }, 100);
            } else {
                console.log('❌ No se encontraron datos:', data.message);
                mostrarAlerta(data.message || 'No se encontraron datos para este DNI', 'error');
                habilitarCampos();
            }
        })
        .catch(error => {
            console.error('💥 Error en fetch:', error);
            mostrarAlerta('Error al consultar RENIEC. Verifique su conexión.', 'error');
            habilitarCampos();
        })
        .finally(() => {
            // Rehabilitar botón
            btnBuscar.disabled = false;
            btnText.textContent = 'Buscar';
            loadingSpinner.classList.add('hidden');
        });
    }

    function bloquearCampos() {
        // USAR READONLY en lugar de DISABLED para que los valores SÍ se envíen
        nombresInput.readOnly = true;
        apellidoPaternoInput.readOnly = true;
        apellidoMaternoInput.readOnly = true;
        
        // Aplicar estilos visuales de "bloqueado"
        nombresInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-700');
        apellidoPaternoInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-700');
        apellidoMaternoInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-700');

        // Agregar icono de candado
        agregarIconoCandado(nombresInput);
        agregarIconoCandado(apellidoPaternoInput);
        agregarIconoCandado(apellidoMaternoInput);
    }

    function habilitarCampos() {
        // Remover readOnly
        nombresInput.readOnly = false;
        apellidoPaternoInput.readOnly = false;
        apellidoMaternoInput.readOnly = false;
        
        // Remover estilos
        nombresInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-700', 'bg-green-50', 'border-green-500');
        apellidoPaternoInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-700', 'bg-green-50', 'border-green-500');
        apellidoMaternoInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-700', 'bg-green-50', 'border-green-500');

        // Remover iconos de candado
        removerIconoCandado(nombresInput);
        removerIconoCandado(apellidoPaternoInput);
        removerIconoCandado(apellidoMaternoInput);

        // Limpiar campos
        nombresInput.value = '';
        apellidoPaternoInput.value = '';
        apellidoMaternoInput.value = '';
        usernameInput.value = '';
    }

    function agregarIconoCandado(input) {
        // Verificar si ya existe el icono
        if (input.parentElement.querySelector('.icono-candado')) return;

        const iconoHTML = `
            <svg class="icono-candado absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-500" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        `;
        
        // Hacer el contenedor relativo si no lo es
        if (!input.parentElement.classList.contains('relative')) {
            input.parentElement.classList.add('relative');
        }
        
        input.parentElement.insertAdjacentHTML('beforeend', iconoHTML);
    }

    function removerIconoCandado(input) {
        const icono = input.parentElement.querySelector('.icono-candado');
        if (icono) {
            icono.remove();
        }
    }

    function generarUsername() {
        const nombres = nombresInput.value.trim();
        const apellidoPaterno = apellidoPaternoInput.value.trim();

        if (nombres && apellidoPaterno) {
            const primeraLetraNombre = nombres.charAt(0).toLowerCase();
            const apellidoLimpio = apellidoPaterno.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/ñ/g, 'n')
                .replace(/[^a-z]/g, '');

            const username = primeraLetraNombre + apellidoLimpio;
            usernameInput.value = username;
        }
    }

    function mostrarAlerta(mensaje, tipo) {
        const alertaHTML = `
            <div id="alertaTemporal" class="fixed top-4 right-4 z-50 max-w-md px-6 py-4 rounded-lg shadow-lg animate-fade-in ${
                tipo === 'success' ? 'bg-green-500' : 
                tipo === 'warning' ? 'bg-yellow-500' : 
                'bg-red-500'
            } text-white">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${tipo === 'success' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' :
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        }
                    </svg>
                    <span>${mensaje}</span>
                </div>
            </div>
        `;

        const alertaAnterior = document.getElementById('alertaTemporal');
        if (alertaAnterior) alertaAnterior.remove();

        document.body.insertAdjacentHTML('beforeend', alertaHTML);

        setTimeout(() => {
            const alerta = document.getElementById('alertaTemporal');
            if (alerta) {
                alerta.style.opacity = '0';
                alerta.style.transition = 'opacity 0.3s';
                setTimeout(() => alerta.remove(), 300);
            }
        }, 4000);
    }

    // Si se intenta escribir manualmente y NO hay datos de RENIEC, generar username
    nombresInput.addEventListener('input', function() {
        if (!datosReniecCargados) {
            generarUsername();
        }
    });
    
    apellidoPaternoInput.addEventListener('input', function() {
        if (!datosReniecCargados) {
            generarUsername();
        }
    });

    // Mostrar/ocultar ayuda según tipo de documento
    tipoDocumentoSelect.addEventListener('change', function() {
        const ayudaDNI = document.getElementById('ayudaDNI');
        if (this.value === 'DNI') {
            ayudaDNI.classList.remove('hidden');
            btnBuscar.disabled = false;
        } else {
            ayudaDNI.classList.add('hidden');
            btnBuscar.disabled = true;
            habilitarCampos();
        }
    });
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush
@endsection