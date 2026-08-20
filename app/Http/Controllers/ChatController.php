<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private const SYSTEM_PROMPT = "You are the official 'Customer Service' representative of JuangDev (a modern creative studio building custom technology solutions).

JuangDev Profile:
- Specialization: Landing Page, Company Profile, Online Store / E-Commerce, Information Systems, Custom Web App, ERP & CRM.
- Additional Services: SEO Optimization, UI/UX Design, API Integration, Mobile App.
- Contact WhatsApp: +6283852174877
- Website: juangdev.com

How to reply:
1. Welcome visitors warmly and help them find the right solution.
2. Answer questions regarding JuangDev's services, pricing, technologies, and workflow.
3. Recommend packages/services that fit the client's needs.
4. If the client wants an in-depth consultation or price negotiation, direct them to WhatsApp: https://wa.me/6283852174877
5. Use friendly, professional, and natural Indonesian (or English if the user asks in English).
6. Keep your answers concise, clear, and informative.
7. IMPORTANT: Do not use complex markdown formatting like excessive asterisks (*). Reply with clean readable text and line breaks.";

    public function status()
    {
        $apiKey = env('GOOGLE_GENERATIVE_AI_API_KEY');
        return response()->json([
            'status' => 'ok',
            'configured' => !empty($apiKey),
        ]);
    }

    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        $apiKey = env('GOOGLE_GENERATIVE_AI_API_KEY');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API Key Gemini belum dikonfigurasi.',
                'reply' => 'Halo! Terima kasih telah menghubungi JuangDev. Untuk konsultasi langsung atau penawaran harga, silakan hubungi tim kami via WhatsApp di https://wa.me/6283852174877.'
            ], 200);
        }

        // Build conversation history
        $conversationHistory = '';
        foreach ($messages as $m) {
            $content = trim($m['content'] ?? '');
            if (!empty($content)) {
                $role = ($m['role'] ?? 'user') === 'user' ? 'Customer' : 'Customer Service';
                $conversationHistory .= "{$role}: {$content}\n\n";
            }
        }

        $fullPrompt = self::SYSTEM_PROMPT . "\n\n---\nConversation history:\n" . $conversationHistory . "\n---\nCustomer Service:";

        try {
            // Call Google Gemini API
            $response = Http::timeout(25)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                if (!empty($reply)) {
                    return response()->json([
                        'reply' => trim($reply),
                    ]);
                }
            }

            Log::warning('Gemini API returned error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
        }

        // Graceful fallback response
        return response()->json([
            'reply' => "Halo! Terima kasih telah menghubungi JuangDev. Kami siap membantu pembuatan website profesional untuk bisnis Anda. Silakan hubungi kami langsung di WhatsApp: https://wa.me/6283852174877 untuk konsultasi gratis!",
        ]);
    }
}
