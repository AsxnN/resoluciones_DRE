{{-- filepath: resources/views/colaborador/usuarios/edit.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Editar Usuario')

@section('content')
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
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Usuario</h1>
                <p class="text-gray-600 mt-1">Modificar información de: <strong>{{ $usuario->name }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('colaborador.usuarios.update', $usuario) }}">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- SECCIÓN 1: DATOS DE ACCESO -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">1</span>
                        🔐 Datos de Acceso
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Username (Solo lectura) -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <input type="text" 
                                   id="username" 
                                   value="{{ $usuario->username }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">El username no se puede modificar</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $usuario->email) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre Completo -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $usuario->name) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de Acceso -->
                        <div>
                            <label for="tipo_acceso" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Acceso <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_acceso" 
                                    id="tipo_acceso" 
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="admin" {{ old('tipo_acceso', $usuario->tipo_acceso) == 'admin' ? 'selected' : '' }}>👑 Administrador</option>
                                <option value="colaborador" {{ old('tipo_acceso', $usuario->tipo_acceso) == 'colaborador' ? 'selected' : '' }}>👔 Colaborador</option>
                                <option value="cliente" {{ old('tipo_acceso', $usuario->tipo_acceso) == 'cliente' ? 'selected' : '' }}>👤 Cliente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: CAMBIAR CONTRASEÑA -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-orange-100 text-orange-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">2</span>
                        🔑 Cambiar Contraseña (Opcional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   minlength="8"
                                   placeholder="Dejar en blanco para mantener"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   minlength="8"
                                   placeholder="Repetir nueva contraseña"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: DATOS PERSONALES -->
                @if($usuario->persona)
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                        👤 Datos Personales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Tipo Documento (Solo lectura) -->
                        <div>
                            <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo Documento
                            </label>
                            <input type="text" 
                                   id="tipo_documento" 
                                   value="{{ $usuario->persona->tipo_documento ?? 'DNI' }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">No se puede modificar</p>
                        </div>

                        <!-- DNI -->
                        <div class="md:col-span-2">
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Documento
                            </label>
                            <input type="text" 
                                   id="dni" 
                                   name="dni" 
                                   value="{{ old('dni', $usuario->persona->num_documento ?? '') }}"
                                   maxlength="20"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Nombres (Solo lectura) -->
                        <div>
                            <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombres
                            </label>
                            <input type="text" 
                                   id="nombres" 
                                   value="{{ $usuario->persona->nombres ?? '' }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                        </div>

                        <!-- Apellido Paterno (Solo lectura) -->
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Paterno
                            </label>
                            <input type="text" 
                                   id="apellido_paterno" 
                                   value="{{ $usuario->persona->apellido_paterno ?? '' }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                        </div>

                        <!-- Apellido Materno (Solo lectura) -->
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Materno
                            </label>
                            <input type="text" 
                                   id="apellido_materno" 
                                   value="{{ $usuario->persona->apellido_materno ?? '' }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono', $usuario->persona->telefono ?? '') }}"
                                   maxlength="20"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- WhatsApp (Solo lectura desde persona) -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp
                            </label>
                            <input type="text" 
                                   id="whatsapp" 
                                   value="{{ $usuario->persona->whatsapp ?? '' }}"
                                   readonly
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                        </div>

                        <!-- Dirección (Solo lectura desde persona) -->
                        <div class="md:col-span-3">
                            <label for="direccion_persona" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección Registrada
                            </label>
                            <textarea id="direccion_persona" 
                                      rows="2"
                                      readonly
                                      class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">{{ $usuario->persona->direccion ?? '' }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Los datos personales se modifican desde el módulo de Personas</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- SECCIÓN 4: DATOS DEL COLABORADOR -->
                @if($usuario->colaborador)
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-purple-100 text-purple-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">4</span>
                        💼 Datos del Colaborador
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
                                @foreach(\App\Models\Cargo::where('i_active', true)->orderBy('nombre_cargo')->get() as $cargo)
                                <option value="{{ $cargo->id_cargos }}" {{ old('id_cargos', $usuario->colaborador->id_cargos) == $cargo->id_cargos ? 'selected' : '' }}>
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
                                @foreach(\App\Models\Unidad::where('i_active', true)->orderBy('nombre_unidad')->get() as $unidad)
                                <option value="{{ $unidad->id_unidad }}" {{ old('id_unidad', $usuario->colaborador->id_unidad) == $unidad->id_unidad ? 'selected' : '' }}>
                                    {{ $unidad->nombre_unidad }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="id_direcciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección Organizacional
                            </label>
                            <select name="id_direcciones" 
                                    id="id_direcciones"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach(\App\Models\Direccion::where('i_active', true)->orderBy('nombre_direcciones')->get() as $direccion)
                                <option value="{{ $direccion->id_direcciones }}" {{ old('id_direcciones', $usuario->colaborador->id_direcciones) == $direccion->id_direcciones ? 'selected' : '' }}>
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
                                @foreach(\App\Models\Dependencia::where('i_active', true)->orderBy('nombre_dependencia')->get() as $dependencia)
                                <option value="{{ $dependencia->id_dependencias }}" {{ old('id_dependencia', $usuario->colaborador->id_dependencia) == $dependencia->id_dependencias ? 'selected' : '' }}>
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
                                @foreach(\App\Models\Area::where('i_active', true)->orderBy('nombre_area')->get() as $area)
                                <option value="{{ $area->id_area }}" {{ old('id_area', $usuario->colaborador->id_area) == $area->id_area ? 'selected' : '' }}>
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
                                @foreach(\App\Models\Especialidad::where('i_active', true)->orderBy('nombre_especialidad')->get() as $especialidad)
                                <option value="{{ $especialidad->id_especialidad }}" {{ old('id_especialidad', $usuario->colaborador->id_especialidad) == $especialidad->id_especialidad ? 'selected' : '' }}>
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
                                @foreach(\App\Models\TipoPersonal::where('i_active', true)->orderBy('nombre_tipo_personal')->get() as $tipo)
                                <option value="{{ $tipo->id_tipo_personal }}" {{ old('id_tipo_personal', $usuario->colaborador->id_tipo_personal) == $tipo->id_tipo_personal ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo_personal }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <!-- SECCIÓN 5: ROL ORGANIZACIONAL -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">5</span>
                        👔 Rol Organizacional
                    </h3>
                    
                    <div>
                        <label for="id_rol" class="block text-sm font-medium text-gray-700 mb-2">
                            Rol en la Organización (Opcional)
                        </label>
                        <select id="id_rol" 
                                name="id_rol"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sin rol organizacional</option>
                            @foreach(\App\Models\Rol::where('i_active', true)->orderBy('nombre_rol')->get() as $rol)
                            <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre_rol }}
                                @if($rol->descripcion)
                                    - {{ $rol->descripcion }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-500">
                            ℹ️ El rol organizacional define la función del usuario en la estructura de la institución.
                        </p>
                    </div>
                </div>

                <!-- SECCIÓN 6: ROLES Y PERMISOS (SPATIE) -->
                <div class="pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-red-100 text-red-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">6</span>
                        🔑 Roles y Permisos del Sistema (Spatie)
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Asignar Roles <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($roles as $role)
                            <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           id="role_{{ $role->id }}" 
                                           name="roles[]" 
                                           value="{{ $role->name }}"
                                           {{ $usuario->hasRole($role->name) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                                </div>
                                <div class="ml-3">
                                    <label for="role_{{ $role->id }}" class="font-medium text-gray-900 cursor-pointer">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        @if($role->name === 'admin')
                                            Acceso total al sistema y gestión de usuarios
                                        @elseif($role->name === 'colaborador')
                                            Acceso para gestión de resoluciones y personal
                                        @elseif($role->name === 'director')
                                            Acceso para aprobación y firma de resoluciones
                                        @else
                                            {{ $role->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-3 text-sm text-gray-500">
                            ℹ️ Los roles de Spatie controlan los permisos del sistema (no confundir con roles organizacionales).
                        </p>
                    </div>
                </div>

                <!-- SECCIÓN 7: CONFIGURACIÓN -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-gray-100 text-gray-600 rounded-full w-8 h-8 flex items-center justify-center mr-3">7</span>
                        ⚙️ Configuración
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Usuario Activo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   value="1"
                                   {{ old('activo', $usuario->i_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                            <label for="activo" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Usuario Activo</span>
                                <p class="text-xs text-gray-500">El usuario podrá acceder al sistema</p>
                            </label>
                        </div>

                        @if($usuario->colaborador)
                        <!-- Colaborador Activo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" 
                                   id="colaborador_activo" 
                                   name="colaborador_activo" 
                                   value="1"
                                   {{ old('colaborador_activo', $usuario->colaborador->i_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                            <label for="colaborador_activo" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Colaborador Activo</span>
                                <p class="text-xs text-gray-500">Activar el registro del colaborador</p>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('colaborador.usuarios.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    ✓ Actualizar Usuario
                </button>
            </div>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Información del Usuario</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Username: <strong>{{ $usuario->username }}</strong> (no se puede modificar)</li>
                        <li>Último acceso: {{ $usuario->ultima_sesion ? $usuario->ultima_sesion->format('d/m/Y H:i') : 'Nunca' }}</li>
                        <li>Email verificado: {{ $usuario->email_verified_at ? '✓ Sí' : '✗ No' }}</li>
                        <li>Creado: {{ $usuario->created_at->format('d/m/Y H:i') }}</li>
                        @if($usuario->colaborador)
                        <li>Código Colaborador: <strong>{{ $usuario->colaborador->id_colab_dis }}</strong></li>
                        @endif
                        @if($usuario->persona)
                        <li>ID Persona: <strong>{{ $usuario->persona->id_persona }}</strong></li>
                        @endif
                        @if($usuario->resoluciones()->count() > 0)
                        <li class="text-red-700 font-semibold">⚠️ Tiene {{ $usuario->resoluciones()->count() }} resoluciones asociadas</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection