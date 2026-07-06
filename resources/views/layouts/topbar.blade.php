{{-- filepath: resources/views/layouts/topbar.blade.php --}}
<header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Page Title / Breadcrumb -->
        <div class="flex items-center space-x-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @yield('page-subtitle', 'Bienvenido al sistema')
                </p>
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-3">
            <!-- Search (Desktop) -->
            <div class="hidden md:block relative">
                <input 
                    type="text" 
                    placeholder="Buscar..." 
                    class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                </button>

                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-200"
                     style="display: none;">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">Notificaciones</h3>
                            <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">3 nuevas</span>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Nueva resolución creada</p>
                                <p class="text-xs text-gray-500 mt-1">RES-2025-001245 ha sido registrada</p>
                                <p class="text-xs text-blue-600 mt-1">Hace 5 minutos</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Resolución firmada</p>
                                <p class="text-xs text-gray-500 mt-1">RES-2025-001244 fue aprobada</p>
                                <p class="text-xs text-blue-600 mt-1">Hace 1 hora</p>
                            </div>
                        </a>
                    </div>
                    <div class="p-3 bg-gray-50 text-center">
                        <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver todas las notificaciones</a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        type="button"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img src="{{ Auth::user()->profile_photo_url }}" 
                             alt="{{ Auth::user()->name }}"
                             class="w-9 h-9 rounded-full ring-2 ring-blue-500 object-cover">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center ring-2 ring-blue-400">
                            <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Str::limit(Auth::user()->email, 25) }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" 
                         :class="{ 'rotate-180': open }"
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-200"
                     style="display: none;">
                    
                    <!-- User Info Header -->
                    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center gap-3">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}"
                                     class="w-10 h-10 rounded-full ring-2 ring-blue-400 object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center ring-2 ring-blue-400">
                                    <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('colaborador.profile.show') }}" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium">Mi Perfil</span>
                        </a>

                        <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Mis Resoluciones</span>
                        </a>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <a href="{{ route('api-tokens.index') }}" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span class="font-medium">API Tokens</span>
                        </a>
                        @endif
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-2"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="px-2">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
// Asegurar que Alpine.js esté disponible
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js initialized');
});
</script>
@endpush