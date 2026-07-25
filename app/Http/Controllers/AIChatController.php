<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $apiKey = env('COSMOSHUB_API_KEY', 'sk-cos-5Yew_wCyM_GDnZSJgakIB1qLR6dmLh51azdIWDTNEYY');
        $baseUrl = 'https://api.cosmoshub.tech/v1/chat/completions';
        
        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        // System prompt to guide the AI
        $systemPrompt = [
            'role' => 'system',
            'content' => 'Kamu adalah asisten AI resmi dari FoodLink. FoodLink adalah ekosistem ekonomi sirkular yang menghubungkan usaha makanan dengan masyarakat untuk mengurangi food waste melalui fitur Jual-Cepat, Donasi, dan Barter B2B. Jawablah pertanyaan pengguna dengan ramah, singkat, dan profesional. Jika ditanya hal di luar konteks FoodLink, jawablah sebisanya tetapi arahkan kembali ke FoodLink.'
        ];

        // Format messages for the API
        $messages = [$systemPrompt];
        
        // Append history (make sure it only contains role and content)
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content']) && in_array($msg['role'], ['user', 'assistant'])) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content']
                ];
            }
        }

        // Add current user message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($baseUrl, [
                'model' => 'qwen-3.7-max',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak mengerti.';
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply
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
                'reply' => 'Maaf, terjadi kesalahan pada server.'
            ], 500);
        }
    }
}
