{{-- filepath: resources/views/layouts/sidebar.blade.php --}}
<div x-data="{ sidebarOpen: true }" class="flex">
    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Sidebar -->
    <aside 
        x-show="sidebarOpen"
        @click.away="sidebarOpen = false"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col shadow-2xl lg:static lg:translate-x-0"
    >
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-700 bg-gray-900">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-lg font-bold bg-gradient-to-r from-blue-400 to-blue-300 bg-clip-text text-transparent">DRE Huánuco</span>
            </a>
            <!-- Close button (Mobile) -->
            <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-md hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 mb-1 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-blue-500 shadow-lg shadow-blue-500/50' : 'text-gray-300 hover:bg-gray-700/50' }}">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-gray-700/50 group-hover:bg-gray-600' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <!-- Resoluciones -->
            <a href="#" 
               class="flex items-center px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium">Resoluciones</span>
                <span class="ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">12</span>
            </a>

            <!-- Personas -->
            <a href="#" 
               class="flex items-center px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium">Personas</span>
            </a>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-700"></div>
            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Gestión</p>

            <!-- Registros (Dropdown) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Registros</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-11 space-y-1">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Colaboradores</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Clientes</span>
                    </a>
                </div>
            </div>

            <!-- Catálogos (Dropdown) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Catálogos</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-11 space-y-1">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Áreas</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Cargos</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Unidades</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Dependencias</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Direcciones</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2" />
                        </svg>
                        <span class="text-sm">Especialidades</span>
                    </a>
                </div>
            </div>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-700"></div>
            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Sistema</p>

            <!-- Reportes -->
            <a href="#" 
               class="flex items-center px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium">Reportes</span>
            </a>

            <!-- Administración -->
            <a href="#" 
               class="flex items-center px-4 py-3 mb-1 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200 group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium">Administración</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="border-t border-gray-700 p-4 bg-gray-900">
            <div class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-800 transition-colors cursor-pointer group">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="{{ Auth::user()->name }}"
                         class="w-10 h-10 rounded-full ring-2 ring-blue-500 ring-offset-2 ring-offset-gray-900">
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center ring-2 ring-blue-400 ring-offset-2 ring-offset-gray-900">
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate group-hover:text-blue-300 transition-colors">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-gray-400 truncate">
                        {{ Auth::user()->email }}
                    </p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>
    </aside>

    <!-- Toggle Button (Mobile) -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="fixed bottom-4 right-4 z-50 lg:hidden w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-200 flex items-center justify-center"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>