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
            overflow: hidden; /* prevent body scroll */
        }

        .layout-wrapper {
            display: flex;
            height: 100%;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 300px;
            background: white;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1040;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
            margin-right: -300px; /* pull content left */
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .history-list {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .history-item {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 0.9rem;
            color: #475569;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-item:hover, .history-item.active {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        /* Main Chat Area */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            transition: all 0.3s ease;
            min-width: 0; /* important for flex overflow */
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
        
        .btn-toggle-sidebar {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            margin-right: 1rem;
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
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
            transform: translateY(15px);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styling for markdown content */
        .chat-msg p { margin-bottom: 0.5rem; }
        .chat-msg p:last-child { margin-bottom: 0; }
        .chat-msg ul, .chat-msg ol { padding-left: 1.5rem; margin-bottom: 0.5rem; }
        .chat-msg ul:last-child, .chat-msg ol:last-child { margin-bottom: 0; }
        .chat-msg strong { font-weight: 700; }

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

        .chat-footer-wrapper {
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }

        .prompt-bubbles {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        
        /* Hide scrollbar for bubbles */
        .prompt-bubbles::-webkit-scrollbar { display: none; }
        .prompt-bubbles { -ms-overflow-style: none; scrollbar-width: none; }

        .prompt-bubble {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.85rem;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
        }

        .prompt-bubble:hover {
            background-color: var(--fl-green);
            color: white;
            border-color: var(--fl-green);
        }

        .chat-footer {
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

        .chat-send-btn:hover:not(:disabled) {
            background: var(--fl-green-dark);
            transform: scale(1.05);
        }
        
        .chat-send-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .typing-indicator { display: inline-flex; gap: 4px; }
        .typing-indicator span {
            width: 8px; height: 8px;
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

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                height: 100%;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            .sidebar.collapsed {
                margin-right: 0;
            }
            /* Backdrop for mobile sidebar */
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
            }
            .sidebar-backdrop.show { display: block; }
        }
    </style>
</head>
<body>

    @php
        $isLoggedIn = Auth::guard('web')->check() || Auth::guard('mitra')->check();
        $userType = Auth::guard('mitra')->check() ? 'Mitra' : (Auth::guard('web')->check() ? 'User' : 'Guest');
        $userName = 'Guest';
        if(Auth::guard('web')->check()) $userName = Auth::guard('web')->user()->nama_lengkap;
        if(Auth::guard('mitra')->check()) $userName = Auth::guard('mitra')->user()->nama_mitra;
    @endphp

    <div class="layout-wrapper">
        <!-- Backdrop for mobile -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Sidebar -->
        <div class="sidebar collapsed" id="sidebar">
            <div class="sidebar-header d-flex justify-content-between align-items-center">
                <button class="btn btn-outline-success w-100 fw-bold" id="btnNewChat">
                    <i class="fas fa-plus me-2"></i> Chat Baru
                </button>
            </div>
            
            <div class="history-list" id="historyList">
                @if(!$isLoggedIn)
                    <div class="text-center text-muted mt-4 p-3 bg-light rounded">
                        <i class="fas fa-lock fs-3 mb-2"></i>
                        <p class="mb-2" style="font-size: 0.9rem;">Login untuk menyimpan riwayat dan limit yang lebih besar.</p>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Login Sekarang</button>
                    </div>
                @else
                    <!-- History items will be injected here via JS -->
                    <div class="text-center text-muted mt-4">Memuat riwayat...</div>
                @endif
            </div>

            <div class="sidebar-footer">
                @if($isLoggedIn)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-success overflow-hidden" style="width: 32px; height: 32px;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=f8fafc&color=4DB43F" alt="User" style="width: 100%;">
                        </div>
                        <div class="text-truncate">
                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $userName }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">{{ $userType }}</div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger w-100" id="btnLogout">Logout</button>
                @else
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                @endif
            </div>
        </div>

        <!-- Main Chat Container -->
        <div class="chat-container">
            <div class="chat-header">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn-toggle-sidebar" id="btnToggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="{{ route('welcome') }}" class="text-white me-2" title="Kembali ke Beranda">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="rounded-circle bg-white text-fl-green d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                        <img src="https://ui-avatars.com/api/?name=AI&background=fff&color=4DB43F&rounded=true&font-size=0.4" alt="AI Logo" style="width: 100%; height: 100%;">
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
            
            <div class="chat-footer-wrapper">
                <!-- Prompt Bubbles -->
                <div class="prompt-bubbles" id="promptBubbles">
                    <div class="prompt-bubble" onclick="usePrompt('Apa itu FoodLink?')">Apa itu FoodLink?</div>
                    <div class="prompt-bubble" onclick="usePrompt('Bagaimana cara ikut Jual-Cepat?')">Cara ikut Jual-Cepat?</div>
                    <div class="prompt-bubble" onclick="usePrompt('Jelaskan sistem Barter B2B')">Sistem Barter B2B</div>
                    <div class="prompt-bubble" onclick="usePrompt('Siapa saja yang bisa Donasi Makanan?')">Info Donasi Makanan</div>
                </div>

                <div class="chat-footer">
                    <input type="text" class="chat-input" id="chatInput" placeholder="Ketik pesan Anda di sini..." autocomplete="off">
                    <button class="chat-send-btn" id="chatSendBtn" title="Kirim Pesan">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="loginModalLabel">Login ke Chat AI</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-muted small">Login untuk menyimpan riwayat chat dan mendapatkan limit penggunaan yang lebih besar.</p>
            
            <div class="alert alert-danger d-none" id="loginError"></div>

            <form id="chatLoginForm">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="loginEmail" required placeholder="Masukkan email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="loginPassword" required placeholder="Masukkan password">
                </div>
                <button type="submit" class="btn btn-success w-100" id="btnLoginSubmit">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                    Login
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        const isLoggedIn = {{ $isLoggedIn ? 'true' : 'false' }};
        let currentSessionId = null;
        let chatHistory = [];
        let isWaitingForResponse = false;

        document.addEventListener('DOMContentLoaded', function () {
            // UI Elements
            const sidebar = document.getElementById('sidebar');
            const btnToggleSidebar = document.getElementById('btnToggleSidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const chatInput = document.getElementById('chatInput');
            const chatSendBtn = document.getElementById('chatSendBtn');
            const chatBody = document.getElementById('chatBody');
            const historyList = document.getElementById('historyList');
            const btnNewChat = document.getElementById('btnNewChat');

            // Sidebar Toggle
            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                sidebarBackdrop.classList.toggle('show');
            }
            btnToggleSidebar.addEventListener('click', toggleSidebar);
            sidebarBackdrop.addEventListener('click', () => {
                sidebar.classList.add('collapsed');
                sidebarBackdrop.classList.remove('show');
            });

            // Initialize History if logged in
            if (isLoggedIn) {
                loadSessions();
            }

            // New Chat Button
            btnNewChat.addEventListener('click', async () => {
                if (!isLoggedIn) {
                    clearChat();
                    return;
                }
                
                try {
                    const res = await fetch('/api/chat/sessions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        currentSessionId = data.session.id;
                        clearChat();
                        loadSessions();
                        
                        // Close sidebar on mobile
                        if(window.innerWidth < 768) toggleSidebar();
                    }
                } catch (e) {
                    console.error("Failed to create session", e);
                }
            });

            // Load Sessions
            async function loadSessions() {
                try {
                    const res = await fetch('/api/chat/sessions');
                    const data = await res.json();
                    if (data.success) {
                        renderHistoryList(data.sessions);
                    }
                } catch (e) {
                    console.error("Failed to load sessions", e);
                }
            }

            function renderHistoryList(sessions) {
                historyList.innerHTML = '';
                if (sessions.length === 0) {
                    historyList.innerHTML = '<div class="text-center text-muted mt-4">Belum ada riwayat</div>';
                    return;
                }
                
                sessions.forEach(session => {
                    const div = document.createElement('div');
                    div.className = `history-item ${session.id === currentSessionId ? 'active' : ''}`;
                    div.textContent = session.title || 'Sesi Chat';
                    div.onclick = () => loadHistory(session.id);
                    historyList.appendChild(div);
                });
            }

            // Load specific history
            async function loadHistory(sessionId) {
                try {
                    const res = await fetch(`/api/chat/history?session_id=${sessionId}`);
                    const data = await res.json();
                    if (data.success) {
                        currentSessionId = sessionId;
                        clearChat(false);
                        chatHistory = []; // Reset context
                        
                        data.history.forEach(msg => {
                            // only append user and assistant
                            if(msg.role === 'user' || msg.role === 'assistant') {
                                appendMessage(msg.content, msg.role === 'user' ? 'user' : 'bot', false);
                                chatHistory.push({ role: msg.role, content: msg.content });
                            }
                        });
                        
                        // re-render active state
                        loadSessions();
                        
                        if(window.innerWidth < 768) toggleSidebar();
                    }
                } catch(e) {
                    console.error("Failed to load history", e);
                }
            }

            function clearChat(showGreeting = true) {
                chatBody.innerHTML = '';
                chatHistory = [];
                if (showGreeting) {
                    appendMessage("Halo! Saya asisten AI resmi dari FoodLink. Ada yang bisa saya bantu terkait ekosistem ekonomi sirkular kami hari ini? 😊", 'bot', false);
                }
            }

            // Send Message Function
            async function sendMessage() {
                const message = chatInput.value.trim();
                if (!message || isWaitingForResponse) return;

                appendMessage(message, 'user', false);
                chatInput.value = '';
                
                const typingId = showTypingIndicator();
                isWaitingForResponse = true;
                chatSendBtn.disabled = true;

                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            message: message,
                            history: chatHistory.slice(-6), // Send only last 6 for context
                            session_id: currentSessionId
                        })
                    });

                    const data = await response.json();
                    removeTypingIndicator(typingId);

                    if (data.success) {
                        // Use typewriter effect for the AI reply
                        await typeWriterMessage(data.reply, 'bot');
                        
                        chatHistory.push({ role: 'user', content: message });
                        chatHistory.push({ role: 'assistant', content: data.reply });
                        
                        if (isLoggedIn && chatHistory.length === 2) {
                            // First message, refresh sidebar to update title
                            loadSessions();
                        }
                    } else {
                        appendMessage(data.reply || 'Terjadi kesalahan sistem.', 'bot', false);
                    }
                } catch (error) {
                    removeTypingIndicator(typingId);
                    appendMessage('Terjadi kesalahan koneksi.', 'bot', false);
                } finally {
                    isWaitingForResponse = false;
                    chatSendBtn.disabled = false;
                    chatInput.focus();
                }
            }

            // Typewriter effect function
            function typeWriterMessage(text, sender) {
                return new Promise(resolve => {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = `chat-msg ${sender}`;
                    chatBody.appendChild(msgDiv);
                    
                    let i = 0;
                    const speed = 15; // ms per char
                    
                    function type() {
                        if (i < text.length) {
                            // advance a chunk of characters for faster typing if text is long
                            let chunkSize = text.length > 500 ? 5 : (text.length > 200 ? 3 : 1);
                            i += chunkSize;
                            if (i > text.length) i = text.length;
                            
                            // Parse markdown on the fly
                            msgDiv.innerHTML = marked.parse(text.substring(0, i));
                            scrollToBottom();
                            setTimeout(type, speed);
                        } else {
                            msgDiv.innerHTML = marked.parse(text); // ensure final parse is clean
                            scrollToBottom();
                            resolve();
                        }
                    }
                    type();
                });
            }

            // Append Message to UI (Instant)
            function appendMessage(text, sender, animate = true) {
                const msgDiv = document.createElement('div');
                msgDiv.className = `chat-msg ${sender}`;
                
                if (!animate) {
                    msgDiv.style.animation = 'none';
                    msgDiv.style.opacity = '1';
                    msgDiv.style.transform = 'translateY(0)';
                }
                
                if (sender === 'bot') {
                    msgDiv.innerHTML = marked.parse(text);
                } else {
                    msgDiv.innerHTML = text.replace(/\n/g, '<br>');
                }
                
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
            chatInput.focus();

            // Login Logic
            const chatLoginForm = document.getElementById('chatLoginForm');
            if (chatLoginForm) {
                chatLoginForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const email = document.getElementById('loginEmail').value;
                    const password = document.getElementById('loginPassword').value;
                    const errorDiv = document.getElementById('loginError');
                    const btn = document.getElementById('btnLoginSubmit');
                    const spinner = btn.querySelector('.spinner-border');
                    
                    errorDiv.classList.add('d-none');
                    btn.disabled = true;
                    spinner.classList.remove('d-none');
                    
                    try {
                        const res = await fetch('/chat-ai/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ email, password })
                        });
                        
                        const data = await res.json();
                        if (data.success) {
                            window.location.reload();
                        } else {
                            errorDiv.textContent = data.message || 'Login gagal.';
                            errorDiv.classList.remove('d-none');
                        }
                    } catch(err) {
                        errorDiv.textContent = 'Terjadi kesalahan sistem.';
                        errorDiv.classList.remove('d-none');
                    } finally {
                        btn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });
            }

            // Logout Logic
            const btnLogout = document.getElementById('btnLogout');
            if (btnLogout) {
                btnLogout.addEventListener('click', async function() {
                    if(!confirm('Yakin ingin logout?')) return;
                    
                    try {
                        await fetch('/chat-ai/logout', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        window.location.reload();
                    } catch (e) {
                        console.error('Logout error', e);
                    }
                });
            }
        });
        
        // Global function for bubbles
        function usePrompt(text) {
            const input = document.getElementById('chatInput');
            const btn = document.getElementById('chatSendBtn');
            input.value = text;
            btn.click();
        }
    </script>
</body>
</html>
