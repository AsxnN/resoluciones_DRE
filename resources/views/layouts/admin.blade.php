{{-- filepath: resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') - Sistema DRE</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- ============================================ -->
        <!-- SIDEBAR -->
        <!-- ============================================ -->
        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="w-64 bg-white text-gray-700 flex flex-col shadow-xl border-r border-gray-200">
            
            <!-- Logo/Header -->
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Sistema DRE</h2>
                        <p class="text-xs text-gray-500">Administrador</p>
                    </div>
                </div>
            </div>

            <!-- Usuario Info -->
            <div class="px-4 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-sm font-bold text-white">
                        AD
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Administrador Sistema</p>
                        <p class="text-xs text-gray-500 truncate">admin@dre.gob.pe</p>
                    </div>
                </div>
            </div>

            <!-- Navegación -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <!-- Separador -->
                <div class="px-3 pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</p>
                </div>

                <!-- Gestión de Privilegios -->
                <div x-data="{ open: {{ request()->routeIs('admin.privilegios.*') || request()->routeIs('admin.usuarios.*') || request()->routeIs('admin.modulos.*') || request()->routeIs('admin.permisos.*') ? 'true' : 'false' }} }">
                    <!-- Título del grupo -->
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.privilegios.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="font-medium text-sm">Privilegios</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Submenú -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        
                        <a href="{{ route('admin.privilegios.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.privilegios.index') || request()->routeIs('admin.privilegios.gestionar') ? 'bg-red-100 text-red-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Usuarios
                        </a>

                        <a href="{{ route('admin.privilegios.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-sm transition text-gray-600 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Módulos
                            <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                        </a>

                        <a href="{{ route('admin.privilegios.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-sm transition text-gray-600 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Permisos
                            <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                        </a>
                    </div>
                </div>

                <!-- Separador -->
                <div class="px-3 pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistema</p>
                </div>

                <!-- Resoluciones -->
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-sm">Resoluciones</span>
                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                </a>

                <!-- Personas -->
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Personas</span>
                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                </a>

                <!-- Firmas -->
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <span class="font-medium text-sm">Firmas</span>
                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                </a>

                <!-- Separador -->
                <div class="px-3 pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Reportes</p>
                </div>

                <!-- Auditoría -->
                <a href="{{ route('admin.auditoria.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.auditoria.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="font-medium text-sm">Auditoría</span>
                </a>

                <!-- Estadísticas -->
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium text-sm">Estadísticas</span>
                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Pronto</span>
                </a>

            </nav>

            <!-- Footer del Sidebar -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-sm font-medium text-gray-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============================================ -->
        <!-- CONTENIDO PRINCIPAL -->
        <!-- ============================================ -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Toggle Sidebar -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Title -->
                    <div class="flex-1 px-4">
                        <h1 class="text-xl font-bold text-gray-900">Panel Administrativo</h1>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notificaciones -->
                        <button class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>

                        <!-- User Avatar -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                                AD
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>