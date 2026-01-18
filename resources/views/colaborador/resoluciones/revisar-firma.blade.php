{{-- filepath: resources/views/colaborador/resoluciones/revisar-firma.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Revisar Firma de Resoluciones')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('colaborador.resoluciones.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Revisar Firma de Resoluciones</h1>
        </div>
        <p class="text-gray-600">Revisa las resoluciones seleccionadas antes de firmar</p>
    </div>

    <!-- Resumen -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $resoluciones->count() }} Resolución(es) Seleccionadas</h2>
                <p class="text-blue-100">Revisa la información y selecciona las opciones de notificación</p>
            </div>
            <div class="text-6xl font-bold opacity-20">
                {{ $resoluciones->count() }}
            </div>
        </div>
    </div>

    <!-- Formulario de Firma -->
    <form method="POST" action="{{ route('colaborador.resoluciones.firmarMasivo') }}" id="formFirmar">
        @csrf
        <input type="hidden" name="resoluciones_ids" value="{{ json_encode($resoluciones->pluck('id_resolucion')->toArray()) }}">

        <!-- Lista de Resoluciones -->
        <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Resoluciones a Firmar
                </h3>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($resoluciones as $resolucion)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</h4>
                                    <p class="text-sm text-gray-600">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $resolucion->estado->nombre_estado === 'Aprobado' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $resolucion->estado->nombre_estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $resolucion->estado->nombre_estado === 'Rechazado' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $resolucion->estado->nombre_estado }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 mb-3">{{ $resolucion->asunto_resolucion }}</p>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium text-gray-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Creado por:</span>
                                    <span class="font-medium text-gray-900">{{ $resolucion->usuarioCreador->persona->nombre_persona ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if($resolucion->personasInvolucradas && $resolucion->personasInvolucradas->isNotEmpty())
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Personas a notificar:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($resolucion->personasInvolucradas as $persona)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $persona->nombre_persona }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 italic">Sin personas involucradas</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Opciones de Notificación -->
        <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Opciones de Notificación
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-gray-700 mb-4">Selecciona cómo deseas notificar a las personas involucradas:</p>

                <!-- WhatsApp -->
                <div class="flex items-center p-4 bg-green-50 border-2 border-green-200 rounded-lg hover:border-green-400 transition-colors cursor-pointer" onclick="toggleCheckbox('enviar_whatsapp')">
                    <input type="checkbox" 
                           name="enviar_whatsapp" 
                           id="enviar_whatsapp" 
                           value="1"
                           class="w-6 h-6 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="enviar_whatsapp" class="ml-4 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div>
                            <span class="block font-bold text-gray-900 text-lg">Enviar por WhatsApp</span>
                            <span class="block text-sm text-gray-600">Notificar a las personas con número de WhatsApp registrado</span>
                        </div>
                    </label>
                </div>

                <!-- Correo Electrónico -->
                <div class="flex items-center p-4 bg-blue-50 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition-colors cursor-pointer" onclick="toggleCheckbox('enviar_correo')">
                    <input type="checkbox" 
                           name="enviar_correo" 
                           id="enviar_correo" 
                           value="1"
                           class="w-6 h-6 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="enviar_correo" class="ml-4 flex items-center gap-3 cursor-pointer flex-1">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <span class="block font-bold text-gray-900 text-lg">Enviar por Correo Electrónico</span>
                            <span class="block text-sm text-gray-600">Notificar a las personas con correo electrónico registrado</span>
                        </div>
                    </label>
                </div>

                <!-- Buscar Usuarios Adicionales por DNI -->
                <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            ENVIAR POR CORREO A USUARIOS ADICIONALES
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Busca usuarios por DNI para enviarles las resoluciones firmadas por correo electrónico
                        </p>

                        <!-- Formulario de búsqueda -->
                        <div class="mb-6 bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label for="dni_buscar" class="block text-sm font-medium text-gray-700 mb-2">
                                        Buscar por DNI
                                    </label>
                                    <input type="text" 
                                           id="dni_buscar" 
                                           placeholder="Ingrese DNI de 8 dígitos"
                                           maxlength="8"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" 
                                            id="btn-buscar-usuario"
                                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Resultado de búsqueda -->
                            <div id="resultado-busqueda" class="hidden mt-4 p-4 bg-white border border-gray-300 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900" id="usuario-nombre"></p>
                                            <p class="text-sm text-gray-600" id="usuario-dni"></p>
                                            <p class="text-sm text-gray-500" id="usuario-email"></p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            id="btn-agregar-usuario"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Agregar
                                    </button>
                                </div>
                            </div>

                            <!-- Mensaje de no encontrado -->
                            <div id="mensaje-no-encontrado" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-2 text-red-700">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">No se encontró usuario con ese DNI</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de usuarios seleccionados -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                Usuarios adicionales a notificar (<span id="contador-usuarios">0</span>)
                            </h4>
                            <div id="lista-usuarios-notificar" class="space-y-2">
                                <!-- Se llenará dinámicamente con JavaScript -->
                            </div>
                            <div id="mensaje-sin-usuarios" class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-sm">No hay usuarios adicionales seleccionados</p>
                            </div>
                        </div>

                        <!-- Inputs ocultos para enviar los IDs -->
                        <div id="inputs-usuarios-notificar"></div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Las notificaciones son opcionales</p>
                            <p class="text-xs text-yellow-700 mt-1">Puedes firmar sin enviar notificaciones si así lo deseas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('colaborador.resoluciones.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Firmar {{ $resoluciones->count() }} Resolución(es)
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Array para almacenar usuarios seleccionados
let usuariosNotificar = [];

function toggleCheckbox(id) {
    const checkbox = document.getElementById(id);
    checkbox.checked = !checkbox.checked;
}

// Buscar usuario por DNI
document.getElementById('btn-buscar-usuario').addEventListener('click', async function() {
    const dni = document.getElementById('dni_buscar').value.trim();
    
    if (!dni) {
        alert('⚠️ Ingrese un DNI para buscar');
        return;
    }
    
    if (dni.length !== 8 || !/^\d+$/.test(dni)) {
        alert('⚠️ El DNI debe tener 8 dígitos numéricos');
        return;
    }
    
    // Ocultar mensajes previos
    document.getElementById('resultado-busqueda').classList.add('hidden');
    document.getElementById('mensaje-no-encontrado').classList.add('hidden');
    
    try {
        const response = await fetch(`{{ route('colaborador.resoluciones.buscar-usuario') }}?dni=${dni}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Verificar si ya está agregado
            if (usuariosNotificar.find(u => u.id === data.usuario.id)) {
                alert('⚠️ Este usuario ya fue agregado a la lista');
                return;
            }
            
            // Mostrar resultado
            document.getElementById('usuario-nombre').textContent = data.usuario.nombre_completo;
            document.getElementById('usuario-dni').textContent = `DNI: ${data.usuario.dni}`;
            document.getElementById('usuario-email').textContent = `📧 ${data.usuario.email}`;
            document.getElementById('resultado-busqueda').classList.remove('hidden');
            
            // Guardar temporalmente el usuario encontrado
            document.getElementById('btn-agregar-usuario').setAttribute('data-usuario', JSON.stringify(data.usuario));
        } else {
            document.getElementById('mensaje-no-encontrado').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al buscar usuario. Intente nuevamente.');
    }
});

// Agregar usuario a la lista
document.getElementById('btn-agregar-usuario').addEventListener('click', function() {
    const usuarioData = this.getAttribute('data-usuario');
    if (!usuarioData) return;
    
    const usuario = JSON.parse(usuarioData);
    
    // Agregar al array
    usuariosNotificar.push(usuario);
    
    // Actualizar vista
    actualizarListaUsuarios();
    
    // Limpiar búsqueda
    document.getElementById('dni_buscar').value = '';
    document.getElementById('resultado-busqueda').classList.add('hidden');
});

// Actualizar lista de usuarios
function actualizarListaUsuarios() {
    const lista = document.getElementById('lista-usuarios-notificar');
    const mensaje = document.getElementById('mensaje-sin-usuarios');
    const contador = document.getElementById('contador-usuarios');
    
    contador.textContent = usuariosNotificar.length;
    
    if (usuariosNotificar.length === 0) {
        lista.innerHTML = '';
        mensaje.classList.remove('hidden');
        actualizarInputsOcultos();
        return;
    }
    
    mensaje.classList.add('hidden');
    
    lista.innerHTML = usuariosNotificar.map((usuario, index) => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                    ${usuario.nombre_completo.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">${usuario.nombre_completo}</p>
                    <div class="flex gap-3 text-sm text-gray-600">
                        <span>DNI: ${usuario.dni}</span>
                        <span>•</span>
                        <span>📧 ${usuario.email}</span>
                    </div>
                </div>
            </div>
            <button type="button" 
                    onclick="eliminarUsuario(${index})"
                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    title="Eliminar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `).join('');
    
    actualizarInputsOcultos();
}

// Eliminar usuario de la lista
function eliminarUsuario(index) {
    usuariosNotificar.splice(index, 1);
    actualizarListaUsuarios();
}

// Actualizar inputs ocultos para enviar con el formulario
function actualizarInputsOcultos() {
    const container = document.getElementById('inputs-usuarios-notificar');
    container.innerHTML = usuariosNotificar.map((usuario, index) => 
        `<input type="hidden" name="usuarios_notificar_adicionales[${index}]" value="${usuario.id}">`
    ).join('');
}
</script>
@endpush
@endsection