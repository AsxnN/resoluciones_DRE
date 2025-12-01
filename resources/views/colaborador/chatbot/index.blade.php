{{-- filepath: resources/views/colaborador/chatbot/index.blade.php --}}
@extends('layouts.colaborador')

@section('title', 'Asistente IA')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-gray-600 font-medium">Asistente IA</span>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-blue-600 text-white p-6">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold">Asistente IA</h1>
                    <p class="text-blue-100 text-sm">Hazme preguntas sobre resoluciones y colaboradores</p>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4">
            <!-- Mensaje de bienvenida -->
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-gray-800">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Asistente IA</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex gap-3">
                @csrf
                <input type="text" 
                       id="user-message" 
                       name="message" 
                       placeholder="Escribe tu pregunta aquí..." 
                       required
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                
                <button type="submit" 
                        id="send-btn"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>

    </div>

    <!-- Sugerencias -->
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-gray-900 mb-3">💡 Sugerencias de preguntas:</h3>
        <div class="space-y-2">
            <button onclick="askQuestion('¿Cuántos colaboradores hay activos?')" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                ¿Cuántos colaboradores hay activos?
            </button>
            <button onclick="askQuestion('¿Cuáles son las resoluciones recientes?')" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                ¿Cuáles son las resoluciones recientes?
            </button>
            <button onclick="askQuestion('¿Qué áreas tiene la organización?')" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                ¿Qué áreas tiene la organización?
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script>
const chatForm = document.getElementById('chat-form');
const chatMessages = document.getElementById('chat-messages');
const userMessageInput = document.getElementById('user-message');
const sendBtn = document.getElementById('send-btn');

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const message = userMessageInput.value.trim();
    if (!message) return;

    // Agregar mensaje del usuario
    addMessage(message, 'user');
    userMessageInput.value = '';
    
    // Deshabilitar botón
    sendBtn.disabled = true;
    
    // Mostrar typing indicator
    const typingId = addTypingIndicator();

    try {
        const response = await fetch('{{ route("colaborador.chatbot.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        
        // Remover typing indicator
        removeTypingIndicator(typingId);
        
        if (data.response) {
            addMessage(data.response, 'bot');
        } else {
            addMessage('Lo siento, no pude procesar tu mensaje.', 'bot');
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        addMessage('Error al conectar con el asistente.', 'bot');
    } finally {
        sendBtn.disabled = false;
    }
});

function addMessage(text, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex gap-3';
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="flex-1"></div>
            <div class="flex-shrink-0 order-2">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-blue-600 text-white p-4 rounded-lg shadow-sm">
                    <p>${text}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-right">Tú</p>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-gray-800">${text}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">Asistente IA</p>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.className = 'flex gap-3';
    typingDiv.innerHTML = `
        <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return 'typing-indicator';
}

function removeTypingIndicator(id) {
    const indicator = document.getElementById(id);
    if (indicator) indicator.remove();
}

function askQuestion(question) {
    userMessageInput.value = question;
    chatForm.dispatchEvent(new Event('submit'));
}
</script>
@endpush
@endsection