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
        <p class="text-gray-600">Sigue los 3 pasos para firmar y entregar la resolución.</p>
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

    <!-- Indicador de pasos -->
    <div class="flex items-center justify-center gap-2 mb-6 text-xs font-bold uppercase">
        <span class="flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-full">① Documento</span>
        <span class="text-gray-300">→</span>
        <span class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 rounded-full">② Receptor</span>
        <span class="text-gray-300">→</span>
        <span class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-full">③ Notificación</span>
    </div>

    <!-- Formulario de Firma -->
    <form method="POST" action="{{ route('colaborador.resoluciones.firmarMasivo') }}" id="formFirmar" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="resoluciones_ids" value="{{ json_encode($resoluciones->pluck('id_resolucion')->toArray()) }}">
        <input type="hidden" name="tipo_receptor" id="tipo_receptor" value="">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Lista de Resoluciones Seleccionadas -->
            <div class="lg:col-span-1 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Resoluciones seleccionadas
                </h3>

                @foreach($resoluciones as $resolucion)
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="font-bold text-blue-900 text-sm">{{ $resolucion->num_resolucion }}</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $resolucion->asunto_resolucion }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-medium">
                            {{ $resolucion->tipoResolucion->nombre_tipo_resolucion }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Columna Derecha: Panel de Procesamiento -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Paso 1: Documento Firmado -->
                <div class="bg-white rounded-xl shadow-lg border-2 border-purple-100 overflow-hidden">
                    <div class="bg-purple-600 px-6 py-3">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            ① Sube el documento ya firmado
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-purple-50 p-4 rounded-lg border-2 border-dashed border-purple-300 text-center">
                            <input type="file" name="archivo_firmado" id="archivo_firmado" accept=".pdf" required class="hidden">
                            <label for="archivo_firmado" class="cursor-pointer">
                                <svg class="w-12 h-12 text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-purple-700 font-bold">Haz click para seleccionar el archivo PDF</p>
                                <p class="text-purple-500 text-xs mt-1">Debe ser el PDF que ya tiene la firma digital (FirmaPerú)</p>
                                <p id="file-name" class="mt-2 text-sm font-semibold text-green-600 hidden"></p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Identificación del Receptor -->
                <div class="bg-white rounded-xl shadow-lg border-2 border-blue-100 overflow-hidden">
                    <div class="bg-blue-600 px-6 py-3">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            ② ¿A quién se le entrega?
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-gray-500 mb-4">Debe ser un cliente o persona natural externa a la DRE. Si es trabajador interno, el sistema lo bloqueará.</p>

                        @if($personasExternas->isNotEmpty())
                        <p class="text-sm font-bold text-gray-800 mb-1">👥 Personas registradas en esta resolución</p>
                        <p class="text-xs text-gray-500 mb-3">Se agregaron al crear la resolución. Haz click en "Usar" para continuar con sus datos.</p>
                        <div class="space-y-2 mb-5">
                            @foreach($personasExternas as $pe)
                            <div class="persona-relacionada flex items-center justify-between gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg" data-id="{{ $pe->id_persona_resolucion_datos }}">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $pe->nombres }} {{ $pe->apellido_paterno }} {{ $pe->apellido_materno }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500">{{ $pe->tipo_relacion }}</span>
                                        @if($pe->num_documento)
                                            @if($pe->obtenido_reniec)
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">🔍 Verificado con RENIEC</span>
                                            @else
                                            <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-[10px] font-bold rounded-full">✍️ Ingresado manualmente</span>
                                            @endif
                                        @endif
                                    </div>
                                    <p class="advertencia-dni hidden text-xs text-yellow-700 font-medium mt-1"></p>
                                </div>

                                @if($pe->num_documento)
                                <button type="button" class="btn-seleccionar-persona px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg" data-dni="{{ $pe->num_documento }}">
                                    DNI {{ $pe->num_documento }} · Usar
                                </button>
                                @else
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded-full">⚠️ Sin DNI</span>
                                    <input type="text" maxlength="8" placeholder="DNI" class="input-dni-faltante w-24 px-2 py-1 border border-yellow-300 rounded text-sm font-mono">
                                    <button type="button" class="btn-guardar-dni-faltante px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded-lg">Guardar</button>
                                </div>
                                @endif
                            </div>
                            @if(!$pe->num_documento)
                            <p class="text-[11px] text-gray-400 -mt-1 ml-1">No se registró al crear la resolución. Pídele el DNI a la persona ahora.</p>
                            @endif
                            @endforeach
                        </div>

                        <details class="mb-2">
                            <summary class="cursor-pointer text-xs font-bold text-gray-600 uppercase">¿La persona no está en la lista? Ingresa el DNI manualmente</summary>
                            <div class="mt-3">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Número de DNI *</label>
                                <div class="flex gap-2">
                                    <input type="text" id="dni_receptor" maxlength="8"
                                           class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-lg font-mono tracking-widest">
                                    <button type="button" id="btn-verificar-dni" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all flex items-center gap-2">
                                        <span id="text-btn">Verificar</span>
                                        <span id="loader-dni" class="hidden animate-spin">⌛</span>
                                    </button>
                                </div>
                            </div>
                        </details>
                        @else
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Número de DNI *</label>
                        <div class="flex gap-2">
                            <input type="text" id="dni_receptor" maxlength="8"
                                   class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-lg font-mono tracking-widest">
                            <button type="button" id="btn-verificar-dni" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all flex items-center gap-2">
                                <span id="text-btn">Verificar</span>
                                <span id="loader-dni" class="hidden animate-spin">⌛</span>
                            </button>
                        </div>
                        @endif

                        <!-- Campos que se envían al backend según el caso resuelto -->
                        <input type="hidden" name="dni" id="dni_final">
                        <input type="hidden" name="nombres" id="nombres_receptor">
                        <input type="hidden" name="apellido_paterno" id="paterno_receptor">
                        <input type="hidden" name="apellido_materno" id="materno_receptor">

                        <!-- Resultado de la verificación -->
                        <div id="resultado-verificacion" class="mt-4">
                            <!-- Caso (a): bloqueado por ser colaborador -->
                            <div id="caso-colaborador" class="hidden p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                                <p class="text-red-800 font-bold flex items-center gap-2">🚫 <span id="msg-colaborador"></span></p>
                            </div>

                            <!-- Caso (b): cliente ya existente -->
                            <div id="caso-cliente" class="hidden p-4 bg-green-50 border-2 border-green-300 rounded-lg space-y-2">
                                <p class="text-green-900 font-bold">✅ Cliente ya registrado en el sistema</p>
                                <p class="text-sm text-gray-700"><strong>Nombre:</strong> <span id="cliente-nombre"></span></p>
                                <p class="text-sm text-gray-700"><strong>Usuario del sistema:</strong> <span id="cliente-email-sistema" class="font-mono"></span></p>

                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <strong>Correo de contacto:</strong>
                                    <span id="cliente-correo-texto"></span>
                                    <input type="email" id="cliente-correo-input" class="hidden flex-1 px-2 py-1 border border-green-300 rounded">
                                    <button type="button" id="btn-editar-correo" class="text-blue-600 hover:text-blue-800 text-xs font-bold">✏️ Cambiar correo</button>
                                    <button type="button" id="btn-guardar-correo" class="hidden text-green-700 hover:text-green-900 text-xs font-bold">💾 Guardar</button>
                                </div>

                                <button type="button" id="btn-reenviar-acceso" class="mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg">
                                    ✉️ Enviar correo y usuario
                                </button>
                                <span id="msg-reenvio" class="text-xs text-gray-500"></span>
                            </div>

                            <!-- Caso (c): persona nueva -->
                            <div id="caso-nuevo" class="hidden p-4 bg-blue-50 border-2 border-blue-300 rounded-lg space-y-3">
                                <p class="text-blue-900 font-bold">🆕 Persona no registrada. Se creará una cuenta nueva.</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nombres *</label>
                                        <input type="text" id="nuevo-nombres" class="w-full px-3 py-2 border border-blue-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Apellido Paterno *</label>
                                        <input type="text" id="nuevo-paterno" class="w-full px-3 py-2 border border-blue-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Apellido Materno</label>
                                        <input type="text" id="nuevo-materno" class="w-full px-3 py-2 border border-blue-300 rounded-lg">
                                    </div>
                                </div>

                                <div id="bloque-verificar-reniec-opcional" class="hidden border-t border-blue-200 pt-3">
                                    <button type="button" id="btn-verificar-reniec-opcional" class="px-4 py-2 bg-white border border-blue-300 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg">
                                        🔍 Verificar con RENIEC (opcional)
                                    </button>
                                    <p class="text-[11px] text-gray-500 mt-1">Estos datos se ingresaron a mano al crear la resolución. Puedes confirmarlos con RENIEC antes de continuar, por precaución.</p>
                                    <p id="resultado-reniec-opcional" class="text-xs font-medium mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Notificación y Cuenta (solo aparece tras resolver el receptor) -->
                <div id="paso3" class="hidden bg-white rounded-xl shadow-lg border-2 border-green-100 overflow-hidden">
                    <div class="bg-green-600 px-6 py-3">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            ③ Notificación y entrega digital
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <input type="checkbox" name="enviar_correo" id="check_correo" value="1" checked class="w-5 h-5 text-blue-600 rounded">
                            <div class="flex-1">
                                <label for="check_correo" class="block text-sm font-bold text-blue-900">Enviar por Correo Electrónico</label>
                                <input type="email" name="email_destino" id="email_destino" placeholder="ejemplo@correo.com" class="mt-2 w-full px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <p id="email-hint" class="text-[10px] text-blue-600 mt-1 uppercase font-bold"></p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Botón Final de Acción -->
                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" id="btn-submit-firmar" disabled class="w-full py-5 bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white font-black text-xl rounded-2xl shadow-2xl transition-all transform hover:scale-[1.02] flex items-center justify-center gap-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        FIRMADO Y ENTREGAR AHORA
                    </button>
                    <p class="text-center text-xs text-gray-500 uppercase tracking-widest font-bold">
                        Al procesar, se oficializará el documento y, si corresponde, se creará la cuenta del cliente.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de nombre de archivo
    const fileInput = document.getElementById('archivo_firmado');
    const fileNameDisplay = document.getElementById('file-name');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = '📄 Archivo seleccionado: ' + this.files[0].name;
            fileNameDisplay.classList.remove('hidden');
        }
    });

    const btnVerificar = document.getElementById('btn-verificar-dni');
    const loader = document.getElementById('loader-dni');
    const textBtn = document.getElementById('text-btn');
    const dniInput = document.getElementById('dni_receptor');
    const dniFinal = document.getElementById('dni_final');
    const tipoReceptorInput = document.getElementById('tipo_receptor');

    const casoColaborador = document.getElementById('caso-colaborador');
    const casoCliente = document.getElementById('caso-cliente');
    const casoNuevo = document.getElementById('caso-nuevo');
    const paso3 = document.getElementById('paso3');
    const btnSubmit = document.getElementById('btn-submit-firmar');

    const nombresHidden = document.getElementById('nombres_receptor');
    const paternoHidden = document.getElementById('paterno_receptor');
    const maternoHidden = document.getElementById('materno_receptor');

    const emailDestino = document.getElementById('email_destino');
    const checkCorreo = document.getElementById('check_correo');
    const emailHint = document.getElementById('email-hint');

    const bloqueVerificarReniec = document.getElementById('bloque-verificar-reniec-opcional');
    const btnVerificarReniecOpcional = document.getElementById('btn-verificar-reniec-opcional');
    const resultadoReniecOpcional = document.getElementById('resultado-reniec-opcional');

    let personaActualId = null;
    let dniActual = null;

    function ocultarTodosLosCasos() {
        casoColaborador.classList.add('hidden');
        casoCliente.classList.add('hidden');
        casoNuevo.classList.add('hidden');
        bloqueVerificarReniec.classList.add('hidden');
        resultadoReniecOpcional.textContent = '';
        paso3.classList.add('hidden');
        btnSubmit.disabled = true;
        tipoReceptorInput.value = '';
    }

    async function verificarDni(dni, idPersonaRelacionada = null) {
        if (dni.length !== 8) {
            alert('⚠️ Ingrese un DNI válido de 8 dígitos');
            return;
        }

        dniActual = dni;
        dniFinal.value = dni;
        loader.classList.remove('hidden');
        textBtn.classList.add('hidden');
        btnVerificar.disabled = true;
        ocultarTodosLosCasos();

        try {
            const response = await fetch('{{ route("colaborador.resoluciones.verificar-receptor") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ dni: dni, id_persona_resolucion_datos: idPersonaRelacionada })
            });

            const data = await response.json();

            if (data.tipo === 'colaborador') {
                document.getElementById('msg-colaborador').textContent = data.message;
                casoColaborador.classList.remove('hidden');
                return;
            }

            if (data.tipo === 'cliente') {
                tipoReceptorInput.value = 'cliente';
                personaActualId = data.id_persona;

                nombresHidden.value = data.nombres;
                paternoHidden.value = data.apellido_paterno;
                maternoHidden.value = data.apellido_materno;

                document.getElementById('cliente-nombre').textContent = `${data.nombres} ${data.apellido_paterno} ${data.apellido_materno || ''}`;
                document.getElementById('cliente-email-sistema').textContent = data.username;
                document.getElementById('cliente-correo-texto').textContent = data.correo || '(sin correo registrado)';
                document.getElementById('msg-reenvio').textContent = '';
                casoCliente.classList.remove('hidden');

                // Si no tiene correo registrado, mostrar el input directamente
                if (!data.correo) {
                    correoTexto.classList.add('hidden');
                    correoInput.classList.remove('hidden');
                    btnEditarCorreo.classList.add('hidden');
                    btnGuardarCorreo.classList.remove('hidden');
                    document.getElementById('btn-reenviar-acceso').disabled = true;
                    document.getElementById('msg-reenvio').textContent = '⚠️ Agrega el correo primero para poder enviar las credenciales.';
                }

                // Paso 3: prellenado, editable, NO obligatorio
                emailDestino.value = data.correo || '';
                emailDestino.required = false;
                checkCorreo.checked = !!data.correo;
                checkCorreo.disabled = false;
                emailHint.textContent = 'Cliente ya registrado: este aviso es opcional, no genera credenciales nuevas.';

                paso3.classList.remove('hidden');
                btnSubmit.disabled = false;
                return;
            }

            // tipo === 'nuevo'
            tipoReceptorInput.value = 'nuevo';
            personaActualId = null;

            document.getElementById('nuevo-nombres').value = data.nombres || '';
            document.getElementById('nuevo-paterno').value = data.apellido_paterno || '';
            document.getElementById('nuevo-materno').value = data.apellido_materno || '';
            nombresHidden.value = data.nombres || '';
            paternoHidden.value = data.apellido_paterno || '';
            maternoHidden.value = data.apellido_materno || '';
            casoNuevo.classList.remove('hidden');

            // Botón opcional de verificación con RENIEC solo si los datos fueron ingresados a mano
            if (data.obtenido_reniec === false) {
                bloqueVerificarReniec.classList.remove('hidden');
            }

            // Paso 3: vacío, obligatorio
            emailDestino.value = '';
            emailDestino.required = true;
            checkCorreo.checked = true;
            checkCorreo.disabled = true;
            emailHint.textContent = 'Obligatorio: aquí se enviarán las credenciales de acceso al sistema nuevo.';

            paso3.classList.remove('hidden');
            btnSubmit.disabled = false;

        } catch (error) {
            console.error('Error de verificación:', error);
            alert('❌ Error al conectar con el servicio de verificación');
        } finally {
            loader.classList.add('hidden');
            textBtn.classList.remove('hidden');
            btnVerificar.disabled = false;
        }
    }

    btnVerificar.addEventListener('click', function() {
        verificarDni(dniInput.value.trim());
    });

    // Lista de personas externas ya vinculadas a la resolución
    document.querySelectorAll('.btn-seleccionar-persona').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const idPersonaRelacionada = this.closest('.persona-relacionada').dataset.id;
            verificarDni(this.dataset.dni, idPersonaRelacionada);
        });
    });

    document.querySelectorAll('.btn-guardar-dni-faltante').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            const fila = this.closest('.persona-relacionada');
            const idPersonaRelacionada = fila.dataset.id;
            const dniInputFaltante = fila.querySelector('.input-dni-faltante');
            const advertenciaEl = fila.querySelector('.advertencia-dni');
            const dni = dniInputFaltante.value.trim();

            if (dni.length !== 8) {
                alert('⚠️ Ingrese un DNI válido de 8 dígitos');
                return;
            }

            this.disabled = true;
            try {
                const response = await fetch(`/colaborador/resoluciones/personas-relacionadas/${idPersonaRelacionada}/actualizar-dni`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ dni: dni })
                });
                const data = await response.json();

                if (data.success) {
                    if (data.advertencia) {
                        advertenciaEl.textContent = '⚠️ ' + data.advertencia;
                        advertenciaEl.classList.remove('hidden');
                    }
                    verificarDni(data.dni, idPersonaRelacionada);
                } else {
                    alert(data.message || 'No se pudo guardar el DNI');
                }
            } catch (error) {
                console.error('Error guardando DNI:', error);
                alert('❌ Error al guardar el DNI');
            } finally {
                this.disabled = false;
            }
        });
    });

    // Botón opcional: confirmar con RENIEC los datos ingresados a mano
    btnVerificarReniecOpcional.addEventListener('click', async function() {
        if (!dniActual) return;

        this.disabled = true;
        resultadoReniecOpcional.textContent = 'Consultando RENIEC...';
        resultadoReniecOpcional.className = 'text-xs font-medium mt-2 text-gray-500';

        try {
            const response = await fetch('{{ route("colaborador.reniec.consultar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ dni: dniActual })
            });
            const data = await response.json();

            if (data.success) {
                const nombreReniec = `${data.nombres} ${data.apellido_paterno} ${data.apellido_materno || ''}`.trim();
                const nombreActual = `${document.getElementById('nuevo-nombres').value} ${document.getElementById('nuevo-paterno').value}`.trim();
                const coincide = nombreReniec.toLowerCase().includes(nombreActual.toLowerCase()) || nombreActual.toLowerCase().includes(nombreReniec.toLowerCase());

                resultadoReniecOpcional.textContent = coincide
                    ? `✅ RENIEC confirma: ${nombreReniec}`
                    : `⚠️ RENIEC registra: "${nombreReniec}" — revisa si coincide con lo ingresado.`;
                resultadoReniecOpcional.className = coincide
                    ? 'text-xs font-medium mt-2 text-green-700'
                    : 'text-xs font-medium mt-2 text-yellow-700';
            } else {
                resultadoReniecOpcional.textContent = '❌ ' + (data.message || 'No se encontraron datos en RENIEC.');
                resultadoReniecOpcional.className = 'text-xs font-medium mt-2 text-red-600';
            }
        } catch (error) {
            console.error('Error consultando RENIEC:', error);
            resultadoReniecOpcional.textContent = '❌ Error al conectar con RENIEC';
            resultadoReniecOpcional.className = 'text-xs font-medium mt-2 text-red-600';
        } finally {
            this.disabled = false;
        }
    });

    // Sincronizar campos editables del caso "nuevo" con los hidden que se envían
    ['nuevo-nombres', 'nuevo-paterno', 'nuevo-materno'].forEach((id, idx) => {
        const targets = [nombresHidden, paternoHidden, maternoHidden];
        document.getElementById(id).addEventListener('input', function() {
            targets[idx].value = this.value;
        });
    });

    // Caso (b): editar correo de contacto inline (AJAX, sin salir de la vista)
    const correoTexto = document.getElementById('cliente-correo-texto');
    const correoInput = document.getElementById('cliente-correo-input');
    const btnEditarCorreo = document.getElementById('btn-editar-correo');
    const btnGuardarCorreo = document.getElementById('btn-guardar-correo');

    btnEditarCorreo.addEventListener('click', function() {
        correoInput.value = correoTexto.textContent.trim() === '(sin correo registrado)' ? '' : correoTexto.textContent.trim();
        correoTexto.classList.add('hidden');
        correoInput.classList.remove('hidden');
        btnEditarCorreo.classList.add('hidden');
        btnGuardarCorreo.classList.remove('hidden');
    });

    btnGuardarCorreo.addEventListener('click', async function() {
        if (!personaActualId || !correoInput.value) {
            alert('Ingrese un correo válido');
            return;
        }

        try {
            const response = await fetch(`/colaborador/personas/${personaActualId}/actualizar-correo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ correo: correoInput.value })
            });
            const data = await response.json();

            if (data.success) {
                correoTexto.textContent = data.correo;
                emailDestino.value = data.correo;
                checkCorreo.checked = true;
                correoTexto.classList.remove('hidden');
                correoInput.classList.add('hidden');
                btnEditarCorreo.classList.remove('hidden');
                btnGuardarCorreo.classList.add('hidden');
                // Habilitar botón de reenvío ahora que hay correo
                const btnReenviar = document.getElementById('btn-reenviar-acceso');
                btnReenviar.disabled = false;
                const msgReenvio = document.getElementById('msg-reenvio');
                if (msgReenvio.textContent.startsWith('⚠️')) msgReenvio.textContent = '';
            } else {
                alert(data.message || 'No se pudo actualizar el correo');
            }
        } catch (error) {
            console.error('Error actualizando correo:', error);
            alert('❌ Error al actualizar el correo');
        }
    });

    // Caso (b): reenviar usuario + nueva contraseña (= DNI) al correo actual
    document.getElementById('btn-reenviar-acceso').addEventListener('click', async function() {
        if (!personaActualId) return;

        const msg = document.getElementById('msg-reenvio');
        this.disabled = true;
        msg.textContent = 'Enviando...';

        try {
            const response = await fetch(`/colaborador/personas/${personaActualId}/reenviar-acceso`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();

            msg.textContent = data.success
                ? `✅ Enviado a ${data.correo}`
                : `❌ ${data.message}`;
        } catch (error) {
            console.error('Error reenviando acceso:', error);
            msg.textContent = '❌ Error al enviar';
        } finally {
            this.disabled = false;
        }
    });
});
</script>
@endpush
@endsection
