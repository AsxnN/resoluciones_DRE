{{-- filepath: resources/views/auth/cliente-register.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Cuenta Cliente - Sistema de Resoluciones</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-900 to-teal-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg my-8">
        <!-- Logo y Título -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-2xl">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">Crear Cuenta</h1>
            <p class="text-green-200">Portal Cliente — Sistema de Resoluciones DRE</p>
        </div>

        <!-- Card de Registro -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-5 text-center">📝 Datos de Registro</h2>

            {{-- Errores generales --}}
            @if (session('error'))
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('cliente.register') }}" class="space-y-4">
                @csrf

                <!-- Nombres -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <input id="nombres"
                               type="text"
                               name="nombres"
                               value="{{ old('nombres') }}"
                               required
                               autofocus
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nombres') border-red-500 @enderror"
                               placeholder="Juan Carlos">
                    </div>
                    <div>
                        <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-1">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <input id="apellido_paterno"
                               type="text"
                               name="apellido_paterno"
                               value="{{ old('apellido_paterno') }}"
                               required
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('apellido_paterno') border-red-500 @enderror"
                               placeholder="Pérez">
                    </div>
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-1">
                        Apellido Materno
                    </label>
                    <input id="apellido_materno"
                           type="text"
                           name="apellido_materno"
                           value="{{ old('apellido_materno') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="García (opcional)">
                </div>

                <!-- Tipo y Número de Documento -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo Doc. <span class="text-red-500">*</span>
                        </label>
                        <select id="tipo_documento"
                                name="tipo_documento"
                                required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tipo_documento') border-red-500 @enderror">
                            <option value="DNI" {{ old('tipo_documento', 'DNI') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Carnet Ext.</option>
                            <option value="Pasaporte" {{ old('tipo_documento') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-1">
                            N° Documento <span class="text-red-500">*</span>
                        </label>
                        <input id="num_documento"
                               type="text"
                               name="num_documento"
                               value="{{ old('num_documento') }}"
                               required
                               maxlength="20"
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('num_documento') border-red-500 @enderror"
                               placeholder="12345678">
                        @error('num_documento')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                               placeholder="tucorreo@gmail.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono (opcional)
                    </label>
                    <input id="telefono"
                           type="text"
                           name="telefono"
                           value="{{ old('telefono') }}"
                           maxlength="20"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="999 888 777">
                </div>

                <!-- Contraseñas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror"
                                   placeholder="Mín. 8 caracteres">
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>
                </div>

                <!-- Botón Registrar -->
                <button type="submit"
                        class="w-full mt-2 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg">
                    ✅ Crear mi Cuenta
                </button>
            </form>

            <!-- Volver al login -->
            <div class="mt-5 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-semibold ml-1">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-green-100 hover:text-white text-sm">
                ← Volver al Login
            </a>
        </div>
    </div>
</body>
</html>
