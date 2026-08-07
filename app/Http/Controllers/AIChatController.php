<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\AiChatSession;
use App\Models\AiChatMessage;

class AIChatController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $mitraCredentials = [
            'email_bisnis' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('mitra')->attempt($mitraCredentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'message' => 'Login berhasil.']);
        }

        $userCredentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('web')->attempt($userCredentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'message' => 'Login berhasil.']);
        }

        return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 401);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('mitra')->check()) {
            Auth::guard('mitra')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }

    public function getSessions(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return response()->json(['success' => false, 'sessions' => []]);

        $sessions = AiChatSession::where('user_id', $user['id'])
            ->where('user_type', $user['type'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'sessions' => $sessions]);
    }

    public function createSession(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return response()->json(['success' => false, 'message' => 'Harus login.']);
        
        // Cek apakah ada sesi kosong sebelumnya agar tidak menumpuk tab kosong
        $existingSession = AiChatSession::where('user_id', $user['id'])
            ->where('user_type', $user['type'])
            ->where('title', 'Sesi Chat Baru')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingSession && $existingSession->messages()->count() === 0) {
            return response()->json(['success' => true, 'session' => $existingSession]);
        }

        $session = AiChatSession::create([
            'user_id' => $user['id'],
            'user_type' => $user['type'],
            'title' => 'Sesi Chat Baru'
        ]);
        
        return response()->json(['success' => true, 'session' => $session]);
    }

    public function getHistory(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return response()->json(['success' => false, 'history' => []]);

        $sessionId = $request->query('session_id');
        if (!$sessionId) return response()->json(['success' => false, 'history' => []]);

        $session = AiChatSession::where('user_id', $user['id'])
            ->where('user_type', $user['type'])
            ->where('id', $sessionId)
            ->first();

        if (!$session) return response()->json(['success' => false, 'history' => []]);

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();
        return response()->json(['success' => true, 'history' => $messages]);
    }

    private function getAuthenticatedUser()
    {
        if (Auth::guard('mitra')->check()) {
            return ['id' => Auth::guard('mitra')->id(), 'type' => 'mitra'];
        } elseif (Auth::guard('web')->check()) {
            return ['id' => Auth::guard('web')->id(), 'type' => 'user'];
        }
        return null;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|integer',
            'history' => 'nullable|array',
            'model' => 'nullable|string|in:gemini-3.5-flash,claude-sonnet-4.5,deepseek-v4-pro,qwen-3.7-max'
        ]);

        $user = $this->getAuthenticatedUser();
        $ip = $request->ip();
        
        // Rate Limiting
        $limitKey = $user ? "ai-chat-{$user['type']}-{$user['id']}" : "ai-chat-guest-{$ip}";
        $maxAttempts = $user ? 50 : 5; // 50 for logged in, 5 for guest per day
        
        $attempts = 0;
        if ($user) {
            $sessions = AiChatSession::where('user_id', $user['id'])->where('user_type', $user['type'])->pluck('id');
            $attempts = AiChatMessage::whereIn('ai_chat_session_id', $sessions)
                ->where('role', 'user')
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();
                
            if ($attempts >= $maxAttempts) {
                return response()->json([
                    'success' => false,
                    'reply' => 'Maaf, Anda telah mencapai limit chat hari ini. Silakan coba lagi besok.'
                ], 429);
            }
        } else {
            if (RateLimiter::tooManyAttempts($limitKey, $maxAttempts)) {
                return response()->json([
                    'success' => false,
                    'reply' => 'Maaf, Anda telah mencapai limit chat hari ini. Silakan coba lagi besok. Login untuk mendapatkan limit lebih besar.'
                ], 429);
            }
            RateLimiter::hit($limitKey, 86400); // 24 hours
        }

        $apiKey = env('COSMOSHUB_API_KEY');
        $baseUrl = 'https://api.cosmoshub.tech/v1/chat/completions';
        
        $userMessage = $request->input('message');
        $history = $request->input('history', []);
        $sessionId = $request->input('session_id');

        // Stronger System prompt
        $systemPrompt = [
            'role' => 'system',
            'content' => 'Kamu adalah asisten virtual resmi FoodLink, platform ekonomi sirkular untuk mencegah food waste (Jual-Cepat, Donasi, Barter B2B).
TUGAS UTAMA: Jawab pertanyaan secara ringkas, ramah, dan profesional. 
PENTING: JIKA PENGGUNA BERTANYA DI LUAR KONTEKS FOODLINK (seperti coding, politik, hiburan, dll), TOLAK DENGAN HALUS dan beritahu bahwa kamu hanya bisa membantu seputar layanan FoodLink.'
        ];

        $messages = [$systemPrompt];
        
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content']) && in_array($msg['role'], ['user', 'assistant'])) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content']
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        // Save user message if logged in
        if ($user) {
            if (!$sessionId) {
                $session = AiChatSession::create([
                    'user_id' => $user['id'],
                    'user_type' => $user['type'],
                    'title' => mb_substr($userMessage, 0, 30) . '...'
                ]);
                $sessionId = $session->id;
            } else {
                $session = AiChatSession::where('user_id', $user['id'])->where('user_type', $user['type'])->where('id', $sessionId)->first();
                if ($session && ($session->messages()->count() == 0 || $session->title == 'Sesi Chat Baru')) {
                    $session->update(['title' => mb_substr($userMessage, 0, 30) . '...']);
                }
            }

            if (isset($session) && $session) {
                AiChatMessage::create([
                    'ai_chat_session_id' => $session->id,
                    'role' => 'user',
                    'content' => $userMessage
                ]);
            }
        }

        $selectedModel = $request->input('model', 'gemini-3.5-flash');
        // If guest, force gemini
        if (!$user) {
            $selectedModel = 'gemini-3.5-flash';
        }

        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($baseUrl, [
                'model' => $selectedModel,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak mengerti.';
                
                // Save AI message if logged in
                if ($user && $sessionId && isset($session)) {
                    AiChatMessage::create([
                        'ai_chat_session_id' => $session->id,
                        'role' => 'assistant',
                        'content' => $reply
                    ]);
                    // trigger session update so it goes to top
                    $session->touch();
                }

                if (!$user) {
                    $attempts = RateLimiter::attempts($limitKey);
                } else {
                    $attempts++; // Account for the message just saved
                }
                $remaining = max(0, $maxAttempts - $attempts);

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'session_id' => $sessionId,
                    'limit' => [
                        'max' => $maxAttempts,
                        'used' => $attempts,
                        'remaining' => $remaining
                    ]
                ]);
            } else {
                Log::error('CosmosHub API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'reply' => 'Maaf, sistem AI sedang sibuk. Silakan coba lagi nanti.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('CosmosHub Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'reply' => 'Maaf, terjadi kesalahan atau koneksi timeout saat menghubungi server AI.'
            ], 500);
        }
    }

    public function getLimitStatus(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $ip = $request->ip();
        
        $limitKey = $user ? "ai-chat-{$user['type']}-{$user['id']}" : "ai-chat-guest-{$ip}";
        $maxAttempts = $user ? 50 : 5;
        
        $attempts = 0;
        if ($user) {
            $sessions = AiChatSession::where('user_id', $user['id'])->where('user_type', $user['type'])->pluck('id');
            $attempts = AiChatMessage::whereIn('ai_chat_session_id', $sessions)
                ->where('role', 'user')
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();
        } else {
            $attempts = RateLimiter::attempts($limitKey);
        }
        
        $remaining = max(0, $maxAttempts - $attempts);
        
        return response()->json([
            'success' => true,
            'limit' => [
                'max' => $maxAttempts,
                'used' => $attempts,
                'remaining' => $remaining
            ]
        ]);
    }
}
