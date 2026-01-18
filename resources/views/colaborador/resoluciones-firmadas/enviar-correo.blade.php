{{-- filepath: resources/views/colaborador/resoluciones-firmadas/enviar-correo.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Enviar Resolución por Correo')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Enviar Resolución por Correo
            </h1>
            <p class="text-gray-600 mt-1">Notificar a usuarios sobre la resolución firmada</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('colaborador.resoluciones-firmadas.index') }}" 
               class="inline-flex items-center px-5 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Información de la Resolución -->
    <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Información de la Resolución
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">N° Resolución</label>
                    <p class="text-lg font-bold text-gray-900">{{ $resolucion->num_resolucion }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tipo</label>
                    <p class="text-lg text-gray-900">{{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha Resolución</label>
                    <p class="text-lg text-gray-900">{{ $resolucion->fecha_resolucion->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha Firma</label>
                    <p class="text-lg text-gray-900">
                        {{ $resolucion->fecha_firma ? $resolucion->fecha_firma->format('d/m/Y H:i:s') : 'N/A' }}
                    </p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Asunto</label>
                    <p class="text-gray-900">{{ $resolucion->asunto_resolucion }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Firmado por</label>
                    <p class="text-gray-900">{{ $resolucion->usuarioFirmante->name ?? 'No especificado' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Estado</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Firmada
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Búsqueda y Envío -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Buscar Usuarios para Notificar
            </h2>
        </div>

        <div class="p-6">
            <!-- Buscar Usuario por DNI -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar Usuario por DNI
                </h3>
                
                <div class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" 
                               id="dni-buscar"
                               placeholder="Ingrese DNI (8 dígitos)" 
                               maxlength="8"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="button" 
                            id="btn-buscar-usuario"
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar
                    </button>
                </div>

                <!-- Resultado de búsqueda -->
                <div id="resultado-busqueda" class="hidden mt-4"></div>
            </div>

            <!-- Lista de usuarios seleccionados -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Usuarios Seleccionados (<span id="contador-usuarios">0</span>)
                </h3>
                
                <div id="lista-usuarios" class="space-y-3">
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="font-medium">No hay usuarios seleccionados</p>
                        <p class="text-sm mt-1">Busque y agregue usuarios para enviar la notificación</p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('colaborador.resoluciones-firmadas.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    Cancelar
                </a>
                <button type="button" 
                        id="btn-enviar-correos"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Enviar Correos
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let usuariosNotificar = [];
const resolucionId = {{ $resolucion->id_resolucion }};

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscar = document.getElementById('btn-buscar-usuario');
    const inputDni = document.getElementById('dni-buscar');
    const btnEnviar = document.getElementById('btn-enviar-correos');

    // Buscar usuario al hacer clic
    btnBuscar.addEventListener('click', buscarUsuario);

    // Buscar con Enter
    inputDni.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            buscarUsuario();
        }
    });

    // Enviar correos
    btnEnviar.addEventListener('click', enviarCorreos);
});

async function buscarUsuario() {
    const dni = document.getElementById('dni-buscar').value.trim();
    const btnBuscar = document.getElementById('btn-buscar-usuario');
    
    if (dni.length !== 8) {
        mostrarAlerta('Por favor ingrese un DNI válido de 8 dígitos', 'error');
        return;
    }

    btnBuscar.disabled = true;
    btnBuscar.innerHTML = `
        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Buscando...</span>
    `;

    try {
        const response = await fetch(`/colaborador/resoluciones-firmadas/buscar-usuario?dni=${dni}`);
        const data = await response.json();

        if (response.ok) {
            mostrarResultadoBusqueda(data);
        } else {
            mostrarAlerta(data.message || 'No se encontró el usuario', 'error');
            document.getElementById('resultado-busqueda').classList.add('hidden');
        }
    } catch (error) {
        mostrarAlerta('Error al buscar usuario: ' + error.message, 'error');
    } finally {
        btnBuscar.disabled = false;
        btnBuscar.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span>Buscar</span>
        `;
    }
}

function mostrarResultadoBusqueda(usuario) {
    const contenedor = document.getElementById('resultado-busqueda');
    
    const html = `
        <div class="bg-white border-2 border-indigo-300 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-14 w-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        ${usuario.iniciales}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-lg">${usuario.nombre_completo}</p>
                        <p class="text-sm text-gray-600">DNI: ${usuario.num_documento}</p>
                        <p class="text-sm text-indigo-600">${usuario.correo}</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="agregarUsuario(${usuario.id_user}, '${usuario.nombre_completo}', '${usuario.num_documento}', '${usuario.correo}')"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar
                </button>
            </div>
        </div>
    `;
    
    contenedor.innerHTML = html;
    contenedor.classList.remove('hidden');
}

function agregarUsuario(id, nombre, dni, correo) {
    // Verificar si ya está en la lista
    if (usuariosNotificar.find(u => u.id === id)) {
        mostrarAlerta('Este usuario ya está en la lista', 'warning');
        return;
    }

    usuariosNotificar.push({
        id: id,
        nombre: nombre,
        dni: dni,
        correo: correo
    });

    actualizarListaUsuarios();
    
    // Limpiar búsqueda
    document.getElementById('resultado-busqueda').classList.add('hidden');
    document.getElementById('dni-buscar').value = '';
    
    mostrarAlerta('Usuario agregado correctamente', 'success');
}

function actualizarListaUsuarios() {
    const contenedor = document.getElementById('lista-usuarios');
    const contador = document.getElementById('contador-usuarios');
    
    contador.textContent = usuariosNotificar.length;
    
    if (usuariosNotificar.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="font-medium">No hay usuarios seleccionados</p>
                <p class="text-sm mt-1">Busque y agregue usuarios para enviar la notificación</p>
            </div>
        `;
        return;
    }

    let html = '';
    
    usuariosNotificar.forEach((usuario, index) => {
        html += `
            <div class="flex items-center justify-between bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm shadow">
                        ${usuario.nombre.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">${usuario.nombre}</p>
                        <p class="text-sm text-gray-600">DNI: ${usuario.dni}</p>
                        <p class="text-sm text-indigo-600">${usuario.correo}</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="eliminarUsuario(${index})"
                        class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="font-medium">Eliminar</span>
                </button>
            </div>
        `;
    });
    
    contenedor.innerHTML = html;
}

function eliminarUsuario(index) {
    if (confirm('¿Está seguro de eliminar este usuario de la lista?')) {
        usuariosNotificar.splice(index, 1);
        actualizarListaUsuarios();
        mostrarAlerta('Usuario eliminado de la lista', 'info');
    }
}

async function enviarCorreos() {
    if (usuariosNotificar.length === 0) {
        mostrarAlerta('Debe agregar al menos un usuario para enviar', 'warning');
        return;
    }

    if (!confirm(`¿Desea enviar la resolución firmada a ${usuariosNotificar.length} usuario(s)?`)) {
        return;
    }

    const btnEnviar = document.getElementById('btn-enviar-correos');
    btnEnviar.disabled = true;
    btnEnviar.innerHTML = `
        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Enviando...</span>
    `;

    try {
        const response = await fetch(`/colaborador/resoluciones-firmadas/${resolucionId}/enviar-correos`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                usuarios: usuariosNotificar.map(u => u.id)
            })
        });

        const data = await response.json();

        if (response.ok) {
            mostrarAlerta(data.message || 'Correos enviados exitosamente', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("colaborador.resoluciones-firmadas.index") }}';
            }, 2000);
        } else {
            mostrarAlerta(data.message || 'Error al enviar correos', 'error');
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Enviar Correos</span>
            `;
        }
    } catch (error) {
        mostrarAlerta('Error al enviar correos: ' + error.message, 'error');
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Enviar Correos</span>
        `;
    }
}

function mostrarAlerta(mensaje, tipo = 'info') {
    const colores = {
        success: 'bg-green-50 border-green-500 text-green-700',
        error: 'bg-red-50 border-red-500 text-red-700',
        warning: 'bg-yellow-50 border-yellow-500 text-yellow-700',
        info: 'bg-blue-50 border-blue-500 text-blue-700'
    };

    const iconos = {
        success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
        error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
        warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
        info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
    };

    const alerta = document.createElement('div');
    alerta.className = `mb-4 border-l-4 p-4 rounded-lg shadow-sm ${colores[tipo]}`;
    alerta.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                ${iconos[tipo]}
            </svg>
            <p class="font-medium">${mensaje}</p>
        </div>
    `;

    const container = document.querySelector('.container-fluid');
    const firstChild = container.firstElementChild;
    container.insertBefore(alerta, firstChild.nextSibling);

    setTimeout(() => {
        alerta.remove();
    }, 5000);
}
</script>
@endpush
@endsection