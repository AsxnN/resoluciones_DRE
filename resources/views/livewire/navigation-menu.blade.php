{{-- filepath: resources/views/livewire/navigation-menu.blade.php --}}
<div 
    x-data="{ open: @entangle('sidebarOpen'), activeMenu: null }"
    class="relative"
>
    <!-- Sidebar Desktop -->
    <aside 
        :class="open ? 'w-64' : 'w-20'"
        class="bg-gray-800 text-white h-screen sticky top-0 transition-all duration-300 ease-in-out flex flex-col"
    >
        <!-- Logo y Toggle -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <div :class="open ? 'block' : 'hidden'" class="flex items-center space-x-2">
                <x-application-mark class="h-8 w-auto" />
                <span class="text-xl font-bold">DRE Sistema</span>
            </div>
            <button 
                @click="$wire.toggleSidebar()"
                class="p-2 rounded-lg hover:bg-gray-700 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          :d="open ? 'M11 19l-7-7 7-7m8 14l-7-7 7-7' : 'M13 5l7 7-7 7M5 5l7 7-7 7'" />
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto py-4 px-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 mb-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Dashboard</span>
            </a>

            @can('resoluciones.listar')
            <!-- Resoluciones -->
            <a href="{{ route('resoluciones.index') }}" 
               class="flex items-center px-4 py-3 mb-2 rounded-lg transition {{ request()->routeIs('resoluciones.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Resoluciones</span>
            </a>
            @endcan

            @can('personas.listar')
            <!-- Personas -->
            <a href="{{ route('personas.index') }}" 
               class="flex items-center px-4 py-3 mb-2 rounded-lg transition {{ request()->routeIs('personas.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Personas</span>
            </a>
            @endcan

            @canany(['colaboradores.listar', 'clientes.listar'])
            <!-- Registros (Dropdown) -->
            <div x-data="{ expanded: {{ request()->routeIs('colaboradores.*', 'clientes.*') ? 'true' : 'false' }} }">
                <button 
                    @click="expanded = !expanded"
                    class="w-full flex items-center justify-between px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-700"
                >
                    <div class="flex items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Registros</span>
                    </div>
                    <svg 
                        :class="open ? 'block' : 'hidden'"
                        class="w-4 h-4 transition-transform" 
                        :class="expanded ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="expanded && open" x-collapse class="ml-6 space-y-1">
                    @can('colaboradores.listar')
                    <a href="{{ route('colaboradores.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('colaboradores.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
                    >
                        <span class="text-sm">Colaboradores</span>
                    </a>
                    @endcan

                    @can('clientes.listar')
                    <a href="{{ route('clientes.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('clientes.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
                    >
                        <span class="text-sm">Clientes</span>
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany

            @can('areas.listar')
            <!-- Catálogos (Dropdown) -->
            <div x-data="{ expanded: {{ request()->routeIs('catalogos.*') ? 'true' : 'false' }} }">
                <button 
                    @click="expanded = !expanded"
                    class="w-full flex items-center justify-between px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-700"
                >
                    <div class="flex items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Catálogos</span>
                    </div>
                    <svg 
                        :class="open ? 'block' : 'hidden'"
                        class="w-4 h-4 transition-transform" 
                        :class="expanded ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="expanded && open" x-collapse class="ml-6 space-y-1">
                    @can('areas.listar')
                    <a href="{{ route('catalogos.areas.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-700">
                        <span class="text-sm">Áreas</span>
                    </a>
                    @endcan

                    @can('cargos.listar')
                    <a href="{{ route('catalogos.cargos.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-700">
                        <span class="text-sm">Cargos</span>
                    </a>
                    @endcan

                    @can('dependencias.listar')
                    <a href="{{ route('catalogos.dependencias.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-700">
                        <span class="text-sm">Dependencias</span>
                    </a>
                    @endcan

                    @can('direcciones.listar')
                    <a href="{{ route('catalogos.direcciones.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-700">
                        <span class="text-sm">Direcciones</span>
                    </a>
                    @endcan

                    @can('especialidades.listar')
                    <a href="{{ route('catalogos.especialidades.index') }}" 
                       class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-700">
                        <span class="text-sm">Especialidades</span>
                    </a>
                    @endcan
                </div>
            </div>
            @endcan

            @can('reportes.ver')
            <!-- Reportes -->
            <a href="{{ route('reportes.index') }}" 
               class="flex items-center px-4 py-3 mb-2 rounded-lg transition {{ request()->routeIs('reportes.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Reportes</span>
            </a>
            @endcan

            @can('usuarios.listar')
            <!-- Administración -->
            <a href="{{ route('admin.usuarios.index') }}" 
               class="flex items-center px-4 py-3 mb-2 rounded-lg transition {{ request()->routeIs('admin.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span :class="open ? 'ml-3' : 'hidden'" class="font-medium">Administración</span>
            </a>
            @endcan
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="border-t border-gray-700 p-4">
            <div class="flex items-center">
                <img 
                    src="{{ Auth::user()->profile_photo_url }}" 
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full"
                >
                <div :class="open ? 'ml-3' : 'hidden'">
                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>