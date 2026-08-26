<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private const SYSTEM_PROMPT = "You are 'JuangDev AI Assistant', the smart and friendly official AI consultant for JuangDev Digital Solutions.

About JuangDev:
- JuangDev is a modern creative technology studio and software development house in Indonesia.
- Core Services: Landing Page, Company Profile, Toko Online / E-Commerce, Sistem Informasi Bisnis, Custom Web Application, ERP & CRM, UI/UX Design, SEO Optimization, API Integration.
- Key Value: 100% Transparent pricing, fast turnaround, clean code, modern neo-glow tech design, flexible 50% Down Payment (DP) scheme, free technical consultation.
- Website Features: Interactive Cost Estimator at /estimator, full portfolio at /portfolio, pricing catalog at /services.
- WhatsApp Consultation: Fast response via direct WhatsApp.

Guidelines for response:
1. Always respond in natural, professional, and friendly Indonesian (or English if the user asks in English).
2. Answer questions accurately regarding services, pricing estimates, workflow, and technologies.
3. Be concise, structured, and helpful. Use clean line breaks and readable bullet points.
4. When discussing budget or specific quotations, recommend checking the Estimator Biaya (/estimator) or contacting WhatsApp.
5. Avoid excessive markdown symbols, do not output raw code unless requested.";

    public function status()
    {
        $apiKey = env('GOOGLE_GENERATIVE_AI_API_KEY') ?? env('GEMINI_API_KEY');
        return response()->json([
            'status' => 'ok',
            'configured' => !empty($apiKey),
        ]);
    }

    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        $apiKey = env('GOOGLE_GENERATIVE_AI_API_KEY') ?? env('GEMINI_API_KEY');
        
        $waNumber = SiteSetting::where('key', 'whatsapp_number')->value('value') ?? env('ADMIN_WA_NUMBER') ?? '62859171681988';
        $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }
        $waUrl = "https://wa.me/{$waNumber}";

        // Extract latest user message
        $lastUserMsg = '';
        foreach (array_reverse($messages) as $m) {
            if (($m['role'] ?? '') === 'user' && !empty(trim($m['content'] ?? ''))) {
                $lastUserMsg = trim($m['content']);
                break;
            }
        }

        // Try Gemini API if API key is provided
        if (!empty($apiKey)) {
            $conversationHistory = '';
            foreach ($messages as $m) {
                $content = trim($m['content'] ?? '');
                if (!empty($content)) {
                    $role = ($m['role'] ?? 'user') === 'user' ? 'Client' : 'JuangDev AI';
                    $conversationHistory .= "{$role}: {$content}\n\n";
                }
            }

            $fullPrompt = self::SYSTEM_PROMPT . "\nWhatsApp Link: {$waUrl}\n\n---\nRiwayat Percakapan:\n" . $conversationHistory . "\n---\nJuangDev AI:";

            // Try primary models
            $models = ['gemini-1.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash-latest'];
            foreach ($models as $model) {
                try {
                    $response = Http::withoutVerifying()
                        ->withOptions(['verify' => false, 'timeout' => 10])
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $fullPrompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 600,
                        ]
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        if (!empty($reply)) {
                            return response()->json([
                                'reply' => trim($reply),
                                'source' => 'ai',
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning("Gemini API ({$model}) attempt failed: " . $e->getMessage());
                }
            }
        }

        // Fallback: Smart JuangDev Heuristic Knowledge Engine
        $smartReply = $this->generateSmartKnowledgeReply($lastUserMsg, $waUrl);

        return response()->json([
            'reply' => $smartReply,
            'source' => 'knowledge_engine',
        ]);
    }

    /**
     * Built-in intelligent rule & intent engine for JuangDev.
     */
    private function generateSmartKnowledgeReply(string $input, string $waUrl): string
    {
        $q = strtolower(trim($input));

        if (empty($q)) {
            return "Halo! Saya JuangDev AI Assistant. Ada yang bisa kami bantu seputar pembuatan website atau aplikasi digital untuk bisnis Anda?";
        }

        // 1. General Services List
        if (str_contains($q, 'layanan') || str_contains($q, 'jasa') || str_contains($q, 'solusi') || str_contains($q, 'produk') || str_contains($q, 'bisa buat apa')) {
            return "JuangDev menyediakan berbagai solusi pengembangan website dan software digital profesional:\n\n"
                . "1. **Landing Page**: Halaman penjualan cepat dengan konversi tinggi & copywriting persuasif.\n"
                . "2. **Company Profile**: Website profil bisnis korporat elegan untuk meningkatkan kredibilitas.\n"
                . "3. **Toko Online (E-Commerce)**: Sistem jualan online dengan payment gateway & ongkir otomatis.\n"
                . "4. **Sistem Informasi & Custom Web App**: ERP, CRM, inventaris gudang, POS kasir, dan dashboard manajemen kustom.\n"
                . "5. **Integrasi API & UI/UX Design**: Desain antarmuka modern dan konektivitas API pihak ketiga.\n\n"
                . "Anda dapat melihat katalog lengkap di [Katalog Layanan](/services) atau hitung anggaran di [Estimator Biaya](/estimator).";
        }

        // 2. Pricing / Estimator
        if (str_contains($q, 'harga') || str_contains($q, 'biaya') || str_contains($q, 'estimasi') || str_contains($q, 'tarif') || str_contains($q, 'budget') || str_contains($q, 'kalkulator')) {
            return "Investasi pembuatan website di JuangDev sangat terjangkau dan transparan:\n\n"
                . "• Landing Page: Mulai Rp 499.000\n"
                . "• Company Profile: Mulai Rp 1.200.000\n"
                . "• Toko Online (E-Commerce): Mulai Rp 2.500.000\n"
                . "• Custom Web App / Sistem Informasi: Sesuai kompleksitas fitur\n\n"
                . "Anda dapat menghitung estimasi biaya secara otomatis di halaman [Estimator Biaya](/estimator) atau konsultasi langsung via WhatsApp di " . $waUrl . ".";
        }

        // 2. Landing Page
        if (str_contains($q, 'landing') || str_contains($q, 'sales page') || str_contains($q, 'satu halaman')) {
            return "Layanan **Landing Page JuangDev** dirancang khusus untuk memaksimalkan penjualan dan konversi iklan (Google/Meta Ads):\n\n"
                . "• Desain responsif, modern, dan loading ultra-cepat\n"
                . "• Penulisan copywriting berorientasi konversi\n"
                . "• Integrasi tombol WhatsApp, form leads, dan analitik\n"
                . "• Gratis domain & hosting pada paket tertentu\n\n"
                . "Mau estimasi atau konsultasi ide Anda? Buka [Estimator Biaya](/estimator) atau hubungi WhatsApp di " . $waUrl . ".";
        }

        // 3. Toko Online / E-Commerce
        if (str_contains($q, 'toko online') || str_contains($q, 'ecommerce') || str_contains($q, 'e-commerce') || str_contains($q, 'jual beli') || str_contains($q, 'shop')) {
            return "Layanan **Toko Online JuangDev** dilengkapi fitur lengkap untuk scale-up bisnis Anda:\n\n"
                . "• Manajemen katalog produk & stok otomatis\n"
                . "• Pembayaran instan (QRIS, Virtual Account, Bank Transfer)\n"
                . "• Hitung ongkos kirim otomatis se-Indonesia\n"
                . "• Notifikasi pesanan real-time via WhatsApp & Email\n"
                . "• Dashboard admin yang mudah digunakan\n\n"
                . "Pelajari rincian fitur atau simulasikan di [Estimator Biaya](/estimator).";
        }

        // 4. Company Profile / Profil Perusahaan
        if (str_contains($q, 'company') || str_contains($q, 'profil') || str_contains($q, 'perusahaan') || str_contains($q, 'corporate')) {
            return "Layanan **Company Profile JuangDev** meningkatkan kredibilitas & citra profesional perusahaan Anda di mata calon klien & investor:\n\n"
                . "• Desain corporate elegan dan eksklusif\n"
                . "• Halaman Tentang Kami, Layanan, Galeri Proyek, & Kontak\n"
                . "• Optimasi SEO agar mudah ditemukan di Google\n"
                . "• Email bisnis profesional (nama@perusahaananda.com)\n\n"
                . "Silakan cek paket di [Katalog Layanan](/services) atau konsultasi di " . $waUrl . ".";
        }

        // 5. Custom Web App / Sistem Informasi / ERP
        if (str_contains($q, 'custom') || str_contains($q, 'aplikasi') || str_contains($q, 'sistem') || str_contains($q, 'erp') || str_contains($q, 'crm') || str_contains($q, 'dashboard')) {
            return "JuangDev berpengalaman membangun **Aplikasi Web & Sistem Informasi Kustom** dengan arsitektur kuat dan terukur:\n\n"
                . "• Sistem ERP, CRM, Manajemen Gudang, & POS Kasir\n"
                . "• Hak akses multi-level pengguna & keamanan data terenkripsi\n"
                . "• Export laporan PDF/Excel & integrasi API pihak ketiga\n"
                . "• Dibangun dengan framework modern (Laravel, Tailwind, MySQL)\n\n"
                . "Jadwalkan sesi diskusi kebutuhan teknis Anda bersama tim engineer kami via WhatsApp di " . $waUrl . ".";
        }

        // 6. Workflow / Cara Pesan / Alur Kerja
        if (str_contains($q, 'cara') || str_contains($q, 'alur') || str_contains($q, 'proses') || str_contains($q, 'tahap') || str_contains($q, 'langkah') || str_contains($q, 'pesan') || str_contains($q, 'order')) {
            return "Alur kerja pemesanan proyek di JuangDev sangat mudah dan transparan:\n\n"
                . "1. **Konsultasi & Briefing**: Diskusi kebutuhan dan tujuan proyek Anda.\n"
                . "2. **Estimasi & DP 50%**: Pembayaran uang muka untuk memulai pengerjaan resmi.\n"
                . "3. **Development & Review**: Tim teknis mulai mendesain & membangun sistem dengan sesi review berkala.\n"
                . "4. **Pelunasan & Serah Terima**: Uji coba akhir, pelunasan sisa 50%, dan penyerahan seluruh akses & source code.\n\n"
                . "Mulai sekarang dengan menghitung di [Estimator Biaya](/estimator)!";
        }

        // 7. Portofolio / Contoh Hasil
        if (str_contains($q, 'portofolio') || str_contains($q, 'portfolio') || str_contains($q, 'contoh') || str_contains($q, 'karya') || str_contains($q, 'hasil')) {
            return "Kami telah menyelesaikan puluhan proyek website & sistem digital untuk berbagai industri. Anda dapat melihat galeri karya dan studi kasus lengkap kami di halaman [Portofolio JuangDev](/portfolio).\n\n"
                . "Ingin membuat proyek dengan konsep serupa? Hubungi kami langsung di " . $waUrl . ".";
        }

        // 8. Pembayaran & Garansi
        if (str_contains($q, 'bayar') || str_contains($q, 'pembayaran') || str_contains($q, 'dp') || str_contains($q, 'garansi') || str_contains($q, 'revisi')) {
            return "Skema kerja sama JuangDev memberikan keamanan penuh untuk klien:\n\n"
                . "• **Skema DP 50%**: Anda hanya membayar 50% di awal, sisa 50% setelah proyek selesai diverifikasi.\n"
                . "• **Garansi Maintenance**: Bebas bug & error teknis setelah peluncuran.\n"
                . "• **Revisi Fleksibel**: Memastikan hasil akhir sesuai dengan ekspektasi bisnis Anda.\n\n"
                . "Pembayaran resmi mendukung QRIS, Transfer Bank, dan Virtual Account.";
        }

        // 9. Kontak / WhatsApp / Lokasi
        if (str_contains($q, 'kontak') || str_contains($q, 'wa') || str_contains($q, 'whatsapp') || str_contains($q, 'lokasi') || str_contains($q, 'alamat') || str_contains($q, 'hubungi')) {
            return "Anda dapat menghubungi tim JuangDev melalui:\n\n"
                . "• WhatsApp Resmi: [Chat WhatsApp Sekarang](" . $waUrl . ")\n"
                . "• Formulir Kontak: [Halaman Kontak](/contact)\n"
                . "• Jam Operasional: Senin - Sabtu (09:00 - 18:00 WIB)\n\n"
                . "Tim konsultan kami siap merespon pertanyaan Anda secepatnya.";
        }

        // 10. Sapaan / Salam
        if (str_contains($q, 'halo') || str_contains($q, 'hai') || str_contains($q, 'pagi') || str_contains($q, 'siang') || str_contains($q, 'malam') || str_contains($q, 'sore') || str_contains($q, 'assalam')) {
            return "Halo! Selamat datang di JuangDev Digital Solutions.\n\n"
                . "Kami siap membantu mewujudkan website profesional, toko online, atau sistem aplikasi web kustom untuk bisnis Anda.\n\n"
                . "Ada yang bisa kami bantu? Anda bisa bertanya tentang:\n"
                . "• Estimasi biaya & paket harga\n"
                . "• Fitur & spesifikasi website\n"
                . "• Alur kerja dan konsultasi gratis via WhatsApp";
        }

        // Default Helpful Response
        return "Terima kasih atas pertanyaannya! Tim JuangDev siap membantu pembuatan website profesional, aplikasi web kustom, toko online, dan sistem digital untuk bisnis Anda.\n\n"
            . "Untuk mendapatkan estimasi instan, Anda dapat menggunakan fitur [Estimator Biaya](/estimator).\n\n"
            . "Atau diskusikan langsung kebutuhan spesifik Anda bersama tim konsultan kami via WhatsApp di [Klik Disini](" . $waUrl . ").";
    }
}
