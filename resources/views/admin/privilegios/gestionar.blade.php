{{-- filepath: resources/views/admin/privilegios/gestionar.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Privilegios: {{ $usuario->name }}
            </h2>
            <a href="{{ route('admin.privilegios.historial', $usuario) }}" 
               class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-history mr-1"></i> Ver Historial
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Información del Usuario --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $usuario->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipo de Acceso</p>
                        <p class="font-semibold uppercase">{{ $usuario->tipo_acceso }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Permisos Actuales</p>
                        <p class="font-semibold">{{ $usuario->permissions->count() }} permisos</p>
                    </div>
                </div>
            </div>

            {{-- Formulario de Asignación --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.privilegios.asignar', $usuario) }}">
                    @csrf

                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-700">
                                Seleccionar Permisos por Módulo
                            </h3>
                            <div class="space-x-2">
                                <button type="button" onclick="marcarTodos(true)" 
                                        class="text-sm text-blue-600 hover:text-blue-800">
                                    Marcar Todos
                                </button>
                                <button type="button" onclick="marcarTodos(false)" 
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Desmarcar Todos
                                </button>
                            </div>
                        </div>

                        @foreach($modulos as $modulo)
                            <div class="border rounded-lg p-4 mb-4">
                                {{-- Encabezado del Módulo --}}
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-md font-semibold text-gray-800">
                                        <i class="{{ $modulo->icono }} mr-2 text-blue-600"></i>
                                        {{ $modulo->nombre_modulo }}
                                    </h4>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               class="modulo-toggle rounded border-gray-300 text-blue-600"
                                               data-modulo="{{ $modulo->id_modulo }}"
                                               onchange="toggleModulo(this)">
                                        <span class="ml-2 text-sm text-gray-600">Seleccionar todos</span>
                                    </label>
                                </div>

                                {{-- Permisos del Módulo --}}
                                @if($modulo->permisos->count() > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @foreach($modulo->permisos as $permiso)
                                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                <input type="checkbox" 
                                                       name="permisos[]" 
                                                       value="{{ $permiso->id }}"
                                                       class="permiso-checkbox modulo-{{ $modulo->id_modulo }} rounded border-gray-300 text-blue-600"
                                                       {{ in_array($permiso->id, $permisosUsuario) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">
                                                    {{ ucfirst(str_replace($modulo->slug . '.', '', $permiso->name)) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">No hay permisos configurados para este módulo</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('admin.privilegios.index') }}" 
                           class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Marcar/Desmarcar todos los permisos
        function marcarTodos(marcar) {
            document.querySelectorAll('.permiso-checkbox').forEach(checkbox => {
                checkbox.checked = marcar;
            });
            document.querySelectorAll('.modulo-toggle').forEach(toggle => {
                toggle.checked = marcar;
            });
        }

        // Marcar/Desmarcar permisos de un módulo
        function toggleModulo(toggle) {
            const moduloId = toggle.dataset.modulo;
            const checkboxes = document.querySelectorAll(`.modulo-${moduloId}`);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = toggle.checked;
            });
        }

        // Actualizar estado del toggle del módulo al cambiar checkboxes individuales
        document.querySelectorAll('.permiso-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const moduloClass = Array.from(this.classList).find(c => c.startsWith('modulo-'));
                if (moduloClass) {
                    const moduloId = moduloClass.replace('modulo-', '');
                    const moduloCheckboxes = document.querySelectorAll(`.modulo-${moduloId}`);
                    const moduloToggle = document.querySelector(`.modulo-toggle[data-modulo="${moduloId}"]`);
                    
                    const allChecked = Array.from(moduloCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(moduloCheckboxes).some(cb => cb.checked);
                    
                    if (moduloToggle) {
                        moduloToggle.checked = allChecked;
                        moduloToggle.indeterminate = someChecked && !allChecked;
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>