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
            'model' => 'nullable|string|in:gemini-3.8-flash,claude-sonnet-5.5,deepseek-v4-pro,qwen-3.7-max'
        ]);

        $user = $this->getAuthenticatedUser();
        $ip = $request->ip();
        
        $userMessage = $request->input('message');

        // Batasan karakter untuk guest (longgar untuk riset makanan & kalkulasi bahan)
        if (!$user && mb_strlen($userMessage) > 500) {
            return response()->json([
                'success' => false,
                'reply' => 'Maaf, sebagai Guest Anda dibatasi maksimal 500 karakter per pesan. Silakan login untuk mengirim pesan lebih panjang.'
            ], 403);
        }
        
        // Rate Limiting hanya untuk user yang login
        $maxAttempts = 50;
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
        }

        $apiKey = config('services.cosmoshub.api_key', env('COSMOSHUB_API_KEY'));
        $baseUrl = rtrim(config('services.cosmoshub.base_url', 'https://api.cosmoshub.tech/v1'), '/') . '/chat/completions';
        
        if (empty($apiKey)) {
            Log::error('CosmosHub API Key is not configured.');
            return response()->json([
                'success' => false,
                'reply' => 'Maaf, konfigurasi API AI belum lengkap. Harap hubungi administrator.'
            ], 500);
        }

        $history = $request->input('history', []);
        $sessionId = $request->input('session_id');

        // System prompt ringkas, super cepat, anti-LaTeX, dan mendukung chart & kalkulator interaktif
        $systemPrompt = [
            'role' => 'system',
            'content' => "Kamu adalah asisten AI resmi FoodLink — platform ekonomi sirkular pencegahan food waste, sekaligus konsultan kuliner dan akuntan biaya makanan (Food Costing & Culinary Business Advisor) yang CEPAT, TEPAT, dan RINGKAS.

KECEPATAN & EFISIENSI RESPON (SANGAT PENTING):
- Respon harus padat, terstruktur, to-the-point, dan bebas basa-basi atau teori panjang.
- JIKA PENGGUNA BELUM MEMBERIKAN DATA/BAHAN (hanya bertanya umum atau meminta bantu hitung):
  * JANGAN PERNAH membuat contoh simulasi fiktif yang panjang (jangan menulis menu karangan sendiri seperti simulasi ayam geprek dsb).
  * Cukup sambut dengan hangat (1-2 kalimat), lalu berikan template ringkas yang perlu diisi:
    1. Nama Menu & Jumlah Porsi (Yield)
    2. Daftar Bahan Baku & Harganya
    3. Kemasan & Target Untung (Opsional)
  * Selesai! Pertahankan respon di bawah 120 kata agar muncul seketika dalam hitungan detik.

FORMAT PERHITUNGAN MATEMATIKA & ANTI-LATEX (MUTLAK):
- DILARANG KERAS menggunakan sintaks LaTeX atau kode matematika seperti: $$, \\[, \\], \\text{}, \\frac{}, \\times, \\mathbf{}.
- Tulis rumus dan kalkulasi secara bersih dan manusiawi dalam teks biasa/tebal, contoh:
  * HPP per Porsi = Total Biaya / Jumlah Porsi = Rp 60.000 / 10 = Rp 6.000
  * Harga Jual (Margin 40%) = HPP / (1 - 0,40) = Rp 6.000 / 0,60 = Rp 10.000
  * Estimasi Laba per Porsi = Rp 10.000 - Rp 6.000 = Rp 4.000

OUTPUT INTERAKTIF (TABEL, GRAFIK/CHART, & KALKULATOR):
Saat pengguna memberikan data resep/biaya makanan:
1. TABEL RINCIAN BIAYA:
   Sajikan tabel Markdown rapi dengan kolom: Bahan/Item | Jumlah | Harga Beli | Biaya per Porsi. Baris terakhir wajib cantumkan 'Total HPP'.
2. GRAFIK INTERAKTIF (CHART):
   SELALU sertakan satu blok kode ```chart dengan format JSON valid berikut agar frontend memunculkan grafik interaktif otomatis:
```chart
{
  \"type\": \"doughnut\",
  \"title\": \"Komposisi Biaya & Margin (per Porsi)\",
  \"labels\": [\"Bahan Baku\", \"Kemasan\", \"Target Laba\"],
  \"data\": [5000, 1000, 4000],
  \"colors\": [\"#4DB43F\", \"#38bdf8\", \"#8b5cf6\"]
}
```
3. WIDGET KALKULATOR INTERAKTIF:
   SELALU sertakan satu blok kode ```calculator dengan format JSON valid berikut agar frontend memunculkan simulator slider margin live:
```calculator
{
  \"hpp\": 6000,
  \"defaultMargin\": 40,
  \"menu\": \"Nama Menu\"
}
```
4. TIPS SIRKULAR FOODLINK:
   Berikan 1 tips singkat pemanfaatan fitur Jual-Cepat FoodLink jika ada sisa porsi/bahan untuk selamatkan modal."
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

        // Ensure session exists
        if (!$sessionId) {
            $sessionData = [
                'user_type' => $user ? $user['type'] : 'guest',
                'user_id' => $user ? $user['id'] : null,
                'title' => $user ? (mb_substr($userMessage, 0, 30) . '...') : $ip
            ];
            $session = AiChatSession::create($sessionData);
            $sessionId = $session->id;
        } else {
            $sessionQuery = AiChatSession::where('id', $sessionId);
            if ($user) {
                $sessionQuery->where('user_id', $user['id'])->where('user_type', $user['type']);
            } else {
                $sessionQuery->where('user_type', 'guest')->where('title', $ip);
            }
            $session = $sessionQuery->first();
            
            if ($session && $user && ($session->messages()->count() == 0 || $session->title == 'Sesi Chat Baru')) {
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

        $allowedModels = ['gemini-3.8-flash', 'claude-sonnet-5.5', 'deepseek-v4-pro', 'qwen-3.7-max'];
        $selectedModel = $request->input('model', 'gemini-3.8-flash');
        if (!in_array($selectedModel, $allowedModels)) {
            $selectedModel = 'gemini-3.8-flash';
        }

        // If guest, force gemini-3.8-flash
        if (!$user) {
            $selectedModel = 'gemini-3.8-flash';
        }

        try {
            $payload = [
                'model' => $selectedModel,
                'messages' => $messages,
                'temperature' => 0.5,
                'max_tokens' => 1500,
                'reasoning_effort' => 'low',
                'chat_template_kwargs' => ['thinking' => false]
            ];

            // Tutup session PHP agar tidak terjadi session lock yang memperlambat request
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($baseUrl, $payload);

            // Fallback ke gemini-3.8-flash jika model non-default bermasalah di CosmosHub
            if (!$response->successful() && $selectedModel !== 'gemini-3.8-flash') {
                Log::warning("CosmosHub API failed for {$selectedModel}, attempting fallback to gemini-3.8-flash. Response: " . $response->body());
                $payload['model'] = 'gemini-3.8-flash';
                $response = Http::timeout(60)->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post($baseUrl, $payload);
            }

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses jawaban saat ini.';
                
                // Save AI message
                if ($sessionId && isset($session)) {
                    AiChatMessage::create([
                        'ai_chat_session_id' => $session->id,
                        'role' => 'assistant',
                        'content' => $reply
                    ]);
                    // trigger session update so it goes to top
                    $session->touch();
                }

                if (!$user) {
                    $attempts = 0;
                    $remaining = 'Unlimited';
                    $maxAttempts = 'Unlimited';
                } else {
                    $attempts++; // Account for the message just saved
                    $remaining = max(0, $maxAttempts - $attempts);
                }

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
                Log::error('CosmosHub API Error: ' . $response->status() . ' - ' . $response->body());
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
        
        if (!$user) {
            return response()->json([
                'success' => true,
                'limit' => [
                    'max' => 'Unlimited',
                    'used' => 0,
                    'remaining' => 'Unlimited'
                ]
            ]);
        }
        
        $maxAttempts = 50;
        $sessions = AiChatSession::where('user_id', $user['id'])->where('user_type', $user['type'])->pluck('id');
        $attempts = AiChatMessage::whereIn('ai_chat_session_id', $sessions)
            ->where('role', 'user')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->count();
        
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
