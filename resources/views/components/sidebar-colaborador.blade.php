{{-- filepath: resources/views/components/sidebar-colaborador.blade.php --}}
<aside class="w-64 bg-white shadow-lg">
    <div class="p-6">
        <h2 class="text-xl font-bold text-gray-800">Panel Colaborador</h2>
    </div>

    <nav class="mt-6">
        <!-- Dashboard (siempre visible) -->
        <a href="{{ route('colaborador.dashboard') }}" 
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            Dashboard
        </a>

        <!-- Mis Resoluciones (siempre visible) -->
        <a href="{{ route('colaborador.mis-resoluciones.index') }}" 
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            Mis Resoluciones
        </a>

        <!-- Módulos dinámicos según permisos -->
        @foreach(auth()->user()->modulosAccesibles() as $modulo)
            <a href="{{ route('colaborador.' . $modulo->slug . '.index') }}" 
               class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                <i class="{{ $modulo->icono }} w-5 h-5 mr-3"></i>
                {{ $modulo->nombre_modulo }}
            </a>
        @endforeach

        <!-- Chatbot (siempre visible) -->
        <a href="{{ route('colaborador.chatbot.index') }}" 
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
            </svg>
            Asistente IA
        </a>
    </nav>
</aside>