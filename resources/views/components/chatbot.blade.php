{{-- filepath: resources/views/components/chatbot.blade.php --}}
<!-- Botón Flotante del Chatbot -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    <!-- Botón para abrir/cerrar -->
    <button id="chatbot-toggle" 
            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full p-4 shadow-2xl transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-blue-300">
        <svg id="chat-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <svg id="close-icon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- Ventana del Chat -->
    <div id="chatbot-window" 
         class="hidden absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Asistente Virtual</h3>
                    <p class="text-blue-100 text-sm">🤖 IA - Resoluciones DRE</p>
                </div>
                <button onclick="closeChatbot()" class="text-white hover:text-blue-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mensajes -->
        <div id="chatbot-messages" class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4">
            <!-- Mensaje de Bienvenida -->
            <div class="flex gap-3 animate-fade-in">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-white rounded-2xl rounded-tl-none p-4 shadow-sm">
                        <p class="text-gray-800 text-sm leading-relaxed">
                            👋 ¡Hola! Soy tu <strong>Asistente Virtual IA</strong> de la DRE Huánuco.
                        </p>
                        <p class="text-gray-600 text-sm mt-2">
                            Puedo ayudarte con:
                        </p>
                        <ul class="text-xs text-gray-600 mt-2 space-y-1">
                            <li>📋 Consultas sobre resoluciones</li>
                            <li>👥 Información de personal</li>
                            <li>📊 Estadísticas del sistema</li>
                            <li>❓ Preguntas frecuentes</li>
                            <li>🔍 Búsqueda rápida de datos</li>
                        </ul>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Hace un momento</p>
                </div>
            </div>

            <!-- Sugerencias rápidas -->
            <div class="flex flex-wrap gap-2">
                <button onclick="sendQuickMessage('¿Cuántas resoluciones hay este mes?')" 
                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full text-xs font-medium transition">
                    📊 Estadísticas del mes
                </button>
                <button onclick="sendQuickMessage('¿Cómo crear una resolución?')" 
                        class="px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-full text-xs font-medium transition">
                    ❓ ¿Cómo crear resolución?
                </button>
                <button onclick="sendQuickMessage('Buscar persona por DNI')" 
                        class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-full text-xs font-medium transition">
                    🔍 Buscar personal
                </button>
            </div>
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <form id="chatbot-form" class="flex gap-2">
                <input type="text" 
                       id="chatbot-input" 
                       placeholder="Escribe tu pregunta..."
                       autocomplete="off"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full p-3 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-400 text-center mt-2">
                🤖 Powered by IA - DRE Huánuco
            </p>
        </div>
    </div>

    <!-- Indicador de notificación -->
    <div id="chatbot-notification" class="hidden absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-pulse"></div>
</div>

@push('scripts')
<script>
// Variables globales
let chatbotOpen = false;
let messageCount = 0;

// Toggle Chatbot
document.getElementById('chatbot-toggle').addEventListener('click', function() {
    toggleChatbot();
});

function toggleChatbot() {
    const window = document.getElementById('chatbot-window');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const notification = document.getElementById('chatbot-notification');
    
    chatbotOpen = !chatbotOpen;
    
    if (chatbotOpen) {
        window.classList.remove('hidden');
        window.classList.add('animate-slide-up');
        chatIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        notification.classList.add('hidden');
        
        // Scroll al final de los mensajes
        scrollToBottom();
    } else {
        window.classList.add('hidden');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    }
}

function closeChatbot() {
    toggleChatbot();
}

// Enviar mensaje
document.getElementById('chatbot-form').addEventListener('submit', function(e) {
    e.preventDefault();
    sendMessage();
});

function sendMessage() {
    const input = document.getElementById('chatbot-input');
    const message = input.value.trim();
    
    if (message === '') return;
    
    // Agregar mensaje del usuario
    addUserMessage(message);
    input.value = '';
    
    // Mostrar indicador de escritura
    showTypingIndicator();
    
    // Simular respuesta del bot (aquí iría la llamada a tu API)
    setTimeout(() => {
        removeTypingIndicator();
        processMessage(message);
    }, 1500);
}

function sendQuickMessage(message) {
    document.getElementById('chatbot-input').value = message;
    sendMessage();
}

function addUserMessage(message) {
    const messagesContainer = document.getElementById('chatbot-messages');
    const messageHTML = `
        <div class="flex gap-3 justify-end animate-fade-in">
            <div class="flex-1 text-right">
                <div class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-tr-none p-4 shadow-sm max-w-xs">
                    <p class="text-sm leading-relaxed">${escapeHtml(message)}</p>
                </div>
                <p class="text-xs text-gray-400 mt-2">Ahora</p>
            </div>
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
            </div>
        </div>
    `;
    messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
    scrollToBottom();
}

function addBotMessage(message, type = 'text') {
    const messagesContainer = document.getElementById('chatbot-messages');
    const messageHTML = `
        <div class="flex gap-3 animate-fade-in">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-white rounded-2xl rounded-tl-none p-4 shadow-sm">
                    <div class="text-gray-800 text-sm leading-relaxed">${message}</div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Ahora</p>
            </div>
        </div>
    `;
    messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
    scrollToBottom();
}

function showTypingIndicator() {
    const messagesContainer = document.getElementById('chatbot-messages');
    const typingHTML = `
        <div id="typing-indicator" class="flex gap-3 animate-fade-in">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-white rounded-2xl rounded-tl-none p-4 shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
    scrollToBottom();
}

function removeTypingIndicator() {
    const indicator = document.getElementById('typing-indicator');
    if (indicator) {
        indicator.remove();
    }
}

function processMessage(message) {
    const lowerMessage = message.toLowerCase();
    
    // Respuestas inteligentes basadas en palabras clave
    if (lowerMessage.includes('hola') || lowerMessage.includes('buenos') || lowerMessage.includes('saludos')) {
        addBotMessage(`
            👋 ¡Hola! ¿En qué puedo ayudarte hoy?<br><br>
            Puedo asistirte con:
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>📋 Consultas sobre resoluciones</li>
                <li>👥 Información de personal</li>
                <li>📊 Estadísticas y reportes</li>
                <li>❓ Responder tus dudas</li>
            </ul>
        `);
    }
    else if (lowerMessage.includes('resoluciones') && (lowerMessage.includes('mes') || lowerMessage.includes('estadística'))) {
        addBotMessage(`
            📊 <strong>Estadísticas del mes actual:</strong><br><br>
            • Total de resoluciones: <strong>{{ $stats['mes_actual'] ?? 0 }}</strong><br>
            • Borradores: <strong>{{ $stats['borradores'] ?? 0 }}</strong><br>
            • En revisión: <strong>{{ $stats['revision'] ?? 0 }}</strong><br>
            • Firmadas: <strong>{{ $stats['firmadas'] ?? 0 }}</strong><br><br>
            ¿Necesitas más detalles? 🔍
        `);
    }
    else if (lowerMessage.includes('crear') && lowerMessage.includes('resolución')) {
        addBotMessage(`
            📝 <strong>Para crear una resolución:</strong><br><br>
            1️⃣ Ve a <strong>Resoluciones → Nueva Resolución</strong><br>
            2️⃣ Completa los datos básicos (número, fecha, tipo)<br>
            3️⃣ Selecciona la persona asociada<br>
            4️⃣ Elige dirección y dependencia<br>
            5️⃣ Redacta el asunto y contenido<br>
            6️⃣ Guarda como borrador o envía a revisión<br><br>
            <a href="{{ route('colaborador.resoluciones.create') }}" class="text-blue-600 hover:underline">➕ Crear resolución ahora</a>
        `);
    }
    else if (lowerMessage.includes('buscar') && (lowerMessage.includes('persona') || lowerMessage.includes('personal') || lowerMessage.includes('dni'))) {
        addBotMessage(`
            🔍 <strong>Buscar personal:</strong><br><br>
            Puedes buscar por:<br>
            • DNI<br>
            • Nombre completo<br>
            • Cargo<br>
            • Dirección<br><br>
            <a href="{{ route('colaborador.personas.index') }}" class="text-blue-600 hover:underline">👥 Ir a gestión de personal</a>
        `);
    }
    else if (lowerMessage.includes('ayuda') || lowerMessage.includes('help') || lowerMessage.includes('¿') || lowerMessage.includes('?')) {
        addBotMessage(`
            ❓ <strong>Preguntas Frecuentes:</strong><br><br>
            <div class="space-y-2">
                <button onclick="sendQuickMessage('¿Cómo crear una resolución?')" class="block w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs transition">
                    ➡️ ¿Cómo crear una resolución?
                </button>
                <button onclick="sendQuickMessage('¿Cuántas resoluciones hay este mes?')" class="block w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs transition">
                    ➡️ ¿Cuántas resoluciones hay este mes?
                </button>
                <button onclick="sendQuickMessage('Buscar persona por DNI')" class="block w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs transition">
                    ➡️ Buscar personal
                </button>
                <button onclick="sendQuickMessage('¿Cómo firmar una resolución?')" class="block w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs transition">
                    ➡️ ¿Cómo firmar resoluciones?
                </button>
            </div>
        `);
    }
    else if (lowerMessage.includes('firmar')) {
        addBotMessage(`
            ✍️ <strong>Firma de Resoluciones:</strong><br><br>
            Solo los usuarios con rol <strong>Director</strong> pueden firmar resoluciones.<br><br>
            Proceso:<br>
            1️⃣ La resolución debe estar en estado "Pendiente de Firma"<br>
            2️⃣ El director revisa el contenido<br>
            3️⃣ Sube el archivo firmado (PDF)<br>
            4️⃣ Confirma la firma digital<br><br>
            ¿Eres director? Revisa las resoluciones pendientes 📋
        `);
    }
    else if (lowerMessage.includes('gracias') || lowerMessage.includes('ok') || lowerMessage.includes('vale')) {
        addBotMessage(`
            😊 ¡De nada! Estoy aquí para ayudarte.<br><br>
            Si tienes más preguntas, no dudes en consultarme. 💬
        `);
    }
    else if (lowerMessage.includes('mi') && (lowerMessage.includes('resolución') || lowerMessage.includes('resoluciones'))) {
        addBotMessage(`
            📋 <strong>Tus Resoluciones:</strong><br><br>
            • Total creadas: <strong>{{ $stats['total'] ?? 0 }}</strong><br>
            • Borradores: <strong>{{ $stats['borradores'] ?? 0 }}</strong><br>
            • En revisión: <strong>{{ $stats['revision'] ?? 0 }}</strong><br>
            • Firmadas: <strong>{{ $stats['firmadas'] ?? 0 }}</strong><br><br>
            <a href="{{ route('colaborador.mis-resoluciones.index') }}" class="text-blue-600 hover:underline">📑 Ver mis resoluciones</a>
        `);
    }
    else {
        addBotMessage(`
            🤔 Entiendo que preguntas sobre: <strong>"${escapeHtml(message)}"</strong><br><br>
            No tengo una respuesta específica para eso, pero puedo ayudarte con:<br><br>
            <div class="space-y-2 mt-3">
                <button onclick="sendQuickMessage('¿Cómo crear una resolución?')" class="block w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs transition">
                    📝 Crear resoluciones
                </button>
                <button onclick="sendQuickMessage('Estadísticas del mes')" class="block w-full text-left px-3 py-2 bg-purple-50 hover:bg-purple-100 rounded-lg text-xs transition">
                    📊 Ver estadísticas
                </button>
                <button onclick="sendQuickMessage('Buscar personal')" class="block w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded-lg text-xs transition">
                    👥 Gestionar personal
                </button>
            </div><br>
            ¿En qué más puedo asistirte? 🙋‍♂️
        `);
    }
}

function scrollToBottom() {
    const messagesContainer = document.getElementById('chatbot-messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Notificación de bienvenida después de 5 segundos
setTimeout(() => {
    if (!chatbotOpen) {
        document.getElementById('chatbot-notification').classList.remove('hidden');
    }
}, 5000);
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

#chatbot-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#chatbot-messages::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

#chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

@media print {
    #chatbot-container {
        display: none !important;
    }
}
</style>
@endpush