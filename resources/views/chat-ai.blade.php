<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FoodLink AI - Asisten Virtual</title>
    <meta name="description" content="Chat dengan FoodLink AI untuk mendapatkan informasi lebih lanjut."/>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon-192x192-rounded.png') }}" sizes="192x192" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --fl-green: #4DB43F;
            --fl-green-dark: #3aa233;
            --fl-bg: #f5fbf6;
            --fl-muted: #94a3a1;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--fl-bg);
            color: #0f172a;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            background: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }

        .chat-header {
            background-color: var(--fl-green);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header a {
            color: white;
            text-decoration: none;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .chat-header a:hover {
            opacity: 1;
        }

        .chat-body {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-color: #f8fafc;
        }

        .chat-msg {
            max-width: 85%;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            font-size: 1rem;
            line-height: 1.5;
        }

        .chat-msg.user {
            background-color: var(--fl-green);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 0.25rem;
        }

        .chat-msg.bot {
            background-color: white;
            color: #334155;
            align-self: flex-start;
            border-bottom-left-radius: 0.25rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .chat-footer {
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 2rem;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            border-color: var(--fl-green);
            box-shadow: 0 0 0 3px rgba(77, 180, 63, 0.1);
        }

        .chat-send-btn {
            background: var(--fl-green);
            color: white;
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .chat-send-btn:hover {
            background: var(--fl-green-dark);
            transform: scale(1.05);
        }

        .typing-indicator {
            display: inline-flex;
            gap: 4px;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            background-color: #94a3b8;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="chat-container">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('welcome') }}" class="text-white">
                    <i class="fas fa-arrow-left fs-5"></i>
                </a>
                <div class="rounded-circle bg-white text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-robot fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">FoodLink AI</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">Asisten Virtual Resmi</div>
                </div>
            </div>
        </div>
        
        <div class="chat-body" id="chatBody">
            <div class="chat-msg bot">
                Halo! Saya asisten AI resmi dari FoodLink. Ada yang bisa saya bantu terkait ekosistem ekonomi sirkular kami hari ini? 😊
            </div>
        </div>
        
        <div class="chat-footer">
            <input type="text" class="chat-input" id="chatInput" placeholder="Ketik pesan Anda di sini..." autocomplete="off">
            <button class="chat-send-btn" id="chatSendBtn" title="Kirim Pesan">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatInput = document.getElementById('chatInput');
            const chatSendBtn = document.getElementById('chatSendBtn');
            const chatBody = document.getElementById('chatBody');
            
            let chatHistory = [];
            let isWaitingForResponse = false;

            // Send Message Function
            async function sendMessage() {
                const message = chatInput.value.trim();
                if (!message || isWaitingForResponse) return;

                // 1. Add user message to UI
                appendMessage(message, 'user');
                chatInput.value = '';
                
                // 2. Show typing indicator
                const typingId = showTypingIndicator();
                isWaitingForResponse = true;

                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            message: message,
                            history: chatHistory
                        })
                    });

                    const data = await response.json();
                    
                    // Remove typing indicator
                    removeTypingIndicator(typingId);

                    if (data.success) {
                        appendMessage(data.reply, 'bot');
                        
                        // Save to history
                        chatHistory.push({ role: 'user', content: message });
                        chatHistory.push({ role: 'assistant', content: data.reply });
                        
                        // Keep history short (last 6 messages / 3 pairs) to save tokens
                        if (chatHistory.length > 6) {
                            chatHistory = chatHistory.slice(-6);
                        }
                    } else {
                        appendMessage(data.reply || 'Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti.', 'bot');
                    }
                } catch (error) {
                    removeTypingIndicator(typingId);
                    appendMessage('Terjadi kesalahan atau koneksi timeout saat menghubungi server AI.', 'bot');
                    console.error('Chat Error:', error);
                } finally {
                    isWaitingForResponse = false;
                    chatInput.focus();
                }
            }

            // Append Message to UI
            function appendMessage(text, sender) {
                const msgDiv = document.createElement('div');
                msgDiv.className = `chat-msg ${sender}`;
                // Convert newlines to br tags for better formatting
                msgDiv.innerHTML = text.replace(/\\n/g, '<br>');
                chatBody.appendChild(msgDiv);
                scrollToBottom();
            }

            // Show Typing Indicator
            function showTypingIndicator() {
                const id = 'typing-' + Date.now();
                const msgDiv = document.createElement('div');
                msgDiv.className = 'chat-msg bot';
                msgDiv.id = id;
                msgDiv.innerHTML = `<div class="typing-indicator"><span></span><span></span><span></span></div>`;
                chatBody.appendChild(msgDiv);
                scrollToBottom();
                return id;
            }

            // Remove Typing Indicator
            function removeTypingIndicator(id) {
                const el = document.getElementById(id);
                if (el) el.remove();
            }

            // Scroll to Bottom
            function scrollToBottom() {
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            // Event Listeners for Input
            chatSendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Focus input on load
            chatInput.focus();
        });
    </script>
</body>
</html>
