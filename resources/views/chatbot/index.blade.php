<style>
    :root {
        --chat-bg-dark: rgba(17, 17, 17, 0.85);
        --chat-bg-light: rgba(255, 255, 255, 0.9);
        --chat-border-dark: rgba(255, 255, 255, 0.1);
        --chat-border-light: rgba(0, 0, 0, 0.1);
        --msg-bot-dark: #1e1e1e;
        --msg-bot-light: #f3f4f6;
        --text-dark: #ffffff;
        --text-light: #1f2937;
    }

    .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-bounce-slow { animation: bounceSlow 3s infinite; }
    @keyframes bounceSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Botón Flotante (La Burbuja) */
    #toggle-chat-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #111;
        border: 2px solid #eab308;
        color: #eab308;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(234, 179, 8, 0.4);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .dark #toggle-chat-btn { background: #000; }
    #toggle-chat-btn:hover { transform: scale(1.1); box-shadow: 0 6px 25px rgba(234, 179, 8, 0.6); }
    #toggle-chat-btn.open { background: #eab308; color: #000; transform: rotate(90deg); }

    /* Contenedor de la Ventana Flotante */
    #chatbot-wrapper {
        position: fixed;
        bottom: 110px;
        right: 30px;
        z-index: 9998;
        width: calc(100% - 60px);
        max-width: 380px;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    #chatbot-wrapper.active {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    /* Diseño Cristal de la Ventana */
    .chat-glass-card {
        background: var(--chat-bg-light);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--chat-border-light);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 500px; /* Altura fija para el widget */
    }
    .dark .chat-glass-card { background: var(--chat-bg-dark); border-color: var(--chat-border-dark); box-shadow: 0 15px 40px rgba(0,0,0,0.5); }

    .chat-header { background: linear-gradient(135deg, #111 0%, #222 100%); padding: 16px; text-align: center; border-bottom: 2px solid #eab308; }
    .dark .chat-header { background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); }

    #chatbox { flex-grow: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; scroll-behavior: smooth; }
    #chatbox::-webkit-scrollbar { width: 4px; }
    #chatbox::-webkit-scrollbar-thumb { background-color: rgba(234, 179, 8, 0.5); border-radius: 10px; }

    .msg-bubble { padding: 10px 14px; border-radius: 16px; font-size: 0.9rem; max-width: 85%; box-shadow: 0 2px 5px rgba(0,0,0,0.05); line-height: 1.4; }
    .bot-message .msg-bubble { background: var(--msg-bot-light); color: var(--text-light); border-bottom-left-radius: 4px; }
    .dark .bot-message .msg-bubble { background: var(--msg-bot-dark); color: var(--text-dark); border: 1px solid rgba(255,255,255,0.05); }
    .user-message .msg-bubble { background: linear-gradient(135deg, #ffd700, #ff8c00); color: #000; font-weight: 500; border-bottom-right-radius: 4px; align-self: flex-end; }

    .chat-input-wrapper { padding: 12px; background: rgba(255,255,255,0.5); border-top: 1px solid var(--chat-border-light); display: flex; gap: 8px; }
    .dark .chat-input-wrapper { background: rgba(0,0,0,0.3); border-top: 1px solid var(--chat-border-dark); }
    
    .chat-input { flex: 1; background: #fff; border: 1px solid #d1d5db; color: #111; border-radius: 50px; padding: 10px 16px; font-size: 0.9rem; outline: none; }
    .dark .chat-input { background: #1a1a1a; border-color: #333; color: #fff; }
    .chat-input:focus { border-color: #eab308; }

    .chat-send-btn { background: linear-gradient(135deg, #ffd700, #ff8c00); color: #000; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s ease; }
    .chat-send-btn:hover { transform: scale(1.1); }

    .typing-indicator { display: flex; gap: 4px; padding: 12px 16px; background: var(--msg-bot-light); border-radius: 16px; border-bottom-left-radius: 4px; width: fit-content; }
    .dark .typing-indicator { background: var(--msg-bot-dark); }
    .typing-dot { width: 6px; height: 6px; background: #eab308; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
</style>

<button id="toggle-chat-btn" class="animate-bounce-slow">
    <svg id="chat-icon-open" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6-.097 1.016-.417 2.13-.771 2.966-.079.186.074.394.273.362 2.256-.37 3.597-.938 4.18-1.234A9.06 9.06 0 0 0 8 15z"/>
    </svg>
    <svg id="chat-icon-close" class="hidden" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
    </svg>
</button>

<div id="chatbot-wrapper">
    <div class="chat-glass-card">
        <div class="chat-header">
            <h2 class="text-lg font-black text-yellow-500 flex items-center justify-center gap-2">🤖 Asistente Spoon's</h2>
        </div>
        <div id="chatbox">
            <div class="flex items-start gap-3 animate-fadeIn bot-message">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-black font-bold flex-shrink-0 shadow-md">S</div>
                <div class="msg-bubble">
                    <strong>¡Hola! 👋</strong><br>Soy el asistente de Spoon’s Barber Shop. ¿En qué te puedo ayudar?
                </div>
            </div>
        </div>
        <form id="chat-form" class="chat-input-wrapper">
            <input type="text" id="message" placeholder="Escribe aquí..." required autocomplete="off" class="chat-input">
            <button type="submit" class="chat-send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm.83 1.258-3.08-3.08L13.34 2.87 7.466 11.328Z"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('chat-form');
        const chatbox = document.getElementById('chatbox');
        const messageInput = document.getElementById('message');
        const toggleBtn = document.getElementById('toggle-chat-btn');
        const chatWrapper = document.getElementById('chatbot-wrapper');
        const iconOpen = document.getElementById('chat-icon-open');
        const iconClose = document.getElementById('chat-icon-close');

        // Toggle Abrir/Cerrar
        toggleBtn.addEventListener('click', () => {
            chatWrapper.classList.toggle('active');
            toggleBtn.classList.toggle('open');
            
            if(chatWrapper.classList.contains('active')) {
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
                setTimeout(() => messageInput.focus(), 300);
            } else {
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        });

        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex animate-fadeIn ${isUser ? 'user-message justify-end' : 'bot-message items-start gap-3'}`;
            if (isUser) {
                messageDiv.innerHTML = `<div class="msg-bubble">${text}</div>`;
            } else {
                messageDiv.innerHTML = `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-black font-bold flex-shrink-0 shadow-md">S</div><div class="msg-bubble">${text}</div>`;
            }
            chatbox.appendChild(messageDiv);
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.id = "typing-indicator";
            typingDiv.className = `flex items-start gap-3 animate-fadeIn bot-message mt-2`;
            typingDiv.innerHTML = `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-black font-bold flex-shrink-0 shadow-md">S</div><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
            chatbox.appendChild(typingDiv);
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function removeTyping() {
            const typingDiv = document.getElementById('typing-indicator');
            if (typingDiv) typingDiv.remove();
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            messageInput.value = '';
            showTyping();

            try {
                // 🛡️ EL SUERO DE LA VERDAD Y EL TOKEN DIRECTO DE BLADE
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json", // Le decimos a Laravel que si hay un error, nos lo mande en JSON
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" // Usamos Blade para imprimir el token directamente
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) {
                    // Si Laravel manda un error 4xx o 5xx (como el límite de citas) lo atrapamos aquí
                    const errorData = await response.json();
                    throw new Error(errorData.reply || errorData.message || "Error interno del servidor.");
                }

                const data = await response.json();
                
                setTimeout(() => {
                    removeTyping();
                    addMessage(data.reply || "Lo siento, no entiendo tu mensaje. 😅");
                }, 800);

            } catch (error) {
                // 🚨 AHORA SÍ VERÁS EL ERROR REAL SI FALLA ALGO EN PHP
                setTimeout(() => {
                    removeTyping();
                    addMessage(error.message || "Error de conexión. 🔌");
                }, 800);
            }
        });
    });
</script>