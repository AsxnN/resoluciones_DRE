<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Colaborador') - Sistema DRE</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sidebar-link-active {
            @apply bg-white bg-opacity-20 text-white font-semibold;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: true, userMenuOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 bg-blue-950 bg-opacity-50">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    <div>
                        <h1 class="text-sm font-bold">Sistema DRE</h1>
                        <p class="text-xs text-blue-300">Colaborador</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto" style="max-height: calc(100vh - 8rem);">
                <div class="space-y-2">
                    
                    <!-- Dashboard (siempre visible) -->
                    <a href="{{ route('colaborador.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.dashboard') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <!-- Resoluciones -->
                    @can('resoluciones.ver')
                    <a href="{{ route('colaborador.resoluciones.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.resoluciones.*') && !request()->routeIs('colaborador.mis-resoluciones.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium">Resoluciones</span>
                        @can('resoluciones.crear')
                        <span class="ml-auto bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">+</span>
                        @endcan
                    </a>
                    @endcan
                    
                    <!-- Mis Resoluciones (siempre visible para colaboradores) -->
                    <a href="{{ route('colaborador.mis-resoluciones.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.mis-resoluciones.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium">Mis Resoluciones</span>
                    </a>

                    <!-- Resoluciones Firmadas -->
                    @can('resoluciones-firmadas.ver')
                    <a href="{{ route('colaborador.resoluciones-firmadas.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.resoluciones-firmadas.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Resoluciones Firmadas</span>
                    </a>
                    @endcan

                    <!-- Asistente IA -->
                    @can('asistente-ia.usar')
                    <a href="{{ route('colaborador.chatbot.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.chatbot.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <span class="font-medium">Asistente IA</span>
                        <span class="ml-auto bg-purple-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">AI</span>
                    </a>
                    @endcan
                    
                    <!-- Divider solo si hay permisos de gestión -->
                    @canany(['personas.ver', 'colaboradores.ver', 'direcciones.ver', 'dependencias.ver', 'areas.ver', 'cargos.ver', 'especialidades.ver', 'tipos-personal.ver'])
                    <div class="my-4 border-t border-blue-800"></div>
                    
                    <div class="px-4 py-2">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Gestión</p>
                    </div>
                    @endcanany
                    
                    <!-- Colaboradores -->
                    @can('colaboradores.ver')
                    <a href="{{ route('colaborador.colaboradores.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.colaboradores.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="font-medium">Colaboradores</span>
                    </a>
                    @endcan

                    <!-- Personas -->
                    @can('personas.ver')
                    <a href="{{ route('colaborador.personas.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.personas.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium">Personas</span>
                    </a>
                    @endcan
                    
                    <!-- Direcciones -->
                    @can('direcciones.ver')
                    <a href="{{ route('colaborador.direcciones.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.direcciones.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">Direcciones</span>
                    </a>
                    @endcan
                    
                    <!-- Dependencias -->
                    @can('dependencias.ver')
                    <a href="{{ route('colaborador.dependencias.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.dependencias.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Dependencias</span>
                    </a>
                    @endcan
                    
                    <!-- Áreas -->
                    @can('areas.ver')
                    <a href="{{ route('colaborador.areas.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.areas.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 001-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                        </svg>
                        <span class="font-medium">Áreas</span>
                    </a>
                    @endcan

                    <!-- Cargos -->
                    @can('cargos.ver')
                    <a href="{{ route('colaborador.cargos.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.cargos.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Cargos</span>
                    </a>
                    @endcan

                    <!-- Especialidades -->
                    @can('especialidades.ver')
                    <a href="{{ route('colaborador.especialidades.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.especialidades.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="font-medium">Especialidades</span>
                    </a>
                    @endcan

                    <!-- Tipos de Personal -->
                    @can('tipos-personal.ver')
                    <a href="{{ route('colaborador.tipos-personal.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.tipos-personal.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <span class="font-medium">Tipos de Personal</span>
                    </a>
                    @endcan

                    <!-- Unidades -->
                    @can('unidades.ver')
                    <a href="{{ route('colaborador.unidades.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.unidades.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">Unidades</span>
                    </a>
                    @endcan

                    <!-- Tipos de Resolución -->
                    @can('tipos-resolucion.ver')
                    <a href="{{ route('colaborador.tipos-resolucion.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.tipos-resolucion.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Tipos de Resolución</span>
                    </a>
                    @endcan

                    <!-- Roles -->
                    @can('roles.ver')
                    <a href="{{ route('colaborador.roles.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.roles.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="font-medium">Roles</span>
                    </a>
                    @endcan
                    

                    <!-- Divider para Sistema (solo si tiene permiso de usuarios) -->
                    @can('usuarios.ver')
                    <div class="my-4 border-t border-blue-800"></div>

                    <div class="px-4 py-2">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Sistema</p>
                    </div>

                    <a href="{{ route('colaborador.usuarios.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('colaborador.usuarios.*') ? 'sidebar-link-active' : 'text-gray-300' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="font-medium">Usuarios</span>
                    </a>
                    @endcan
                </div>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-300 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-40">
                <div class="flex items-center justify-between h-16 px-6">
                    
                    <!-- Menu Toggle (Mobile) -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="hidden md:block">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2">
                                <li>
                                    <a href="{{ route('colaborador.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                        </svg>
                                    </a>
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-4">
                        
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50"
                                 style="display: none;">
                                <a href="{{ route('colaborador.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Mi Perfil
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        🚪 Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-800 font-medium mb-2">Se encontraron los siguientes errores:</p>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="ml-3 text-red-600 hover:text-red-800 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay (Mobile) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
         style="display: none;"></div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')

</body>
</html>