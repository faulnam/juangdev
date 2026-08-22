<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactPageController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::where('is_active', true)->orderBy('display_order')->get();
        $selectedPlan = $request->query('plan');
        $selectedService = $request->query('service');
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.contact', compact('services', 'selectedPlan', 'selectedService', 'settings'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'service' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:100',
            'message' => 'required|string',
        ]);

        $successMsg = 'Pesan Anda telah berhasil dikirim! Tim JuangDev akan segera menghubungi Anda.';

        // Prevent duplicate submissions & double WA notifications within 60 seconds (Cache + DB Guard)
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone'] ?? '');
        $cacheKey = 'wa_contact_lock_' . md5(($validated['email'] ?? '') . '_' . $cleanPhone . '_' . substr(md5($validated['message'] ?? ''), 0, 10));

        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }
            return back()->with('success', $successMsg);
        }

        \Illuminate\Support\Facades\Cache::put($cacheKey, true, 60);

        $isDuplicate = Contact::where(function($q) use ($validated, $cleanPhone) {
                $q->where('email', $validated['email']);
                if (!empty($cleanPhone)) {
                    $q->orWhere('phone', 'like', "%{$cleanPhone}%");
                }
            })
            ->where('created_at', '>=', now()->subSeconds(60))
            ->exists();

        if ($isDuplicate) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }
            return back()->with('success', $successMsg);
        }

        // Save to Database for Admin Panel (/admin/contacts)
        Contact::create($validated);

        // 1. Send WA Notification to Admin
        $targetPhone = env('ADMIN_WA_NUMBER') ?? SiteSetting::where('key', 'whatsapp_number')->value('value') ?? '62859171681988';
        $waAdminMsg = "PEMBERITAHUAN PESAN MASUK REGULER\n"
            . "JuangDev Digital Solutions\n\n"
            . "Kepada Yth. Tim Admin JuangDev,\n\n"
            . "Telah diterima pesan baru melalui formulir kontak situs web resmi JuangDev dengan rincian sebagai berikut:\n\n"
            . "Nama Pengirim: " . $validated['name'] . "\n"
            . "Alamat Email: " . $validated['email'] . "\n"
            . "Nomor Telepon: " . ($validated['phone'] ?? '-') . "\n"
            . "Layanan Kebutuhan: " . ($validated['service'] ?? '-') . "\n"
            . "Estimasi Anggaran: " . ($validated['budget'] ?? '-') . "\n\n"
            . "Isi Pesan:\n\"" . $validated['message'] . "\"\n\n"
            . "Pesan ini disampaikan secara otomatis oleh sistem situs web resmi JuangDev.";

        \App\Services\PakasirService::sendWaNotification($targetPhone, $waAdminMsg);

        // 2. Send WA Confirmation to Customer (if phone provided)
        if (!empty($validated['phone'])) {
            $waCustomerMsg = "KONFIRMASI PENERIMAAN PESAN KONSULTASI\n"
                . "JuangDev Digital Solutions\n\n"
                . "Kepada Yth. Bapak/Ibu " . $validated['name'] . ",\n\n"
                . "Terima kasih telah menghubungi JuangDev Digital Solutions. Pesan dan permohonan konsultasi Anda telah berhasil kami terima dan dicatat di sistem.\n\n"
                . "Rincian Permohonan:\n"
                . "Layanan Kebutuhan: " . ($validated['service'] ?? '-') . "\n"
                . "Estimasi Anggaran: " . ($validated['budget'] ?? '-') . "\n\n"
                . "Tim konsultan teknis JuangDev akan segera mempelajari kebutuhan Anda dan menghubungi Anda kembali melalui WhatsApp ini.\n\n"
                . "Hormat kami,\n"
                . "Tim Manajemen JuangDev";

            \App\Services\PakasirService::sendWaNotification($validated['phone'], $waCustomerMsg);
        }

        $successMsg = 'Pesan Anda telah berhasil dikirim! Tim JuangDev akan segera menghubungi Anda.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
            ]);
        }

        return back()->with('success', $successMsg);
    }
}
