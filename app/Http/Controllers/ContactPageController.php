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

        // Save to Database for Admin Panel (/admin/contacts)
        Contact::create($validated);

        $targetPhone = env('ADMIN_WA_NUMBER') ?? SiteSetting::where('key', 'whatsapp_number')->value('value') ?? '62859171681988';
        $targetPhoneClean = preg_replace('/[^0-9]/', '', $targetPhone);
        if (str_starts_with($targetPhoneClean, '0')) {
            $targetPhoneClean = '62' . substr($targetPhoneClean, 1);
        }

        $waMessage = "📩 *PESAN BARU DARI WEBSITE JUANGDEV*\n\n"
            . "👤 *Nama*: " . $validated['name'] . "\n"
            . "📧 *Email*: " . $validated['email'] . "\n"
            . "📱 *No. WA/HP*: " . ($validated['phone'] ?? '-') . "\n"
            . "💼 *Layanan*: " . ($validated['service'] ?? '-') . "\n"
            . "💰 *Budget*: " . ($validated['budget'] ?? '-') . "\n\n"
            . "💬 *Pesan*:\n\"" . $validated['message'] . "\"\n\n"
            . "--- \n_Pemberitahuan Otomatis Website JuangDev_";

        // Send WhatsApp Notification to Admin via Fonnte API
        $fonnteToken = config('services.fonnte.token') ?? env('FONNTE_TOKEN');

        if (!empty($fonnteToken)) {
            try {
                Http::withoutVerifying()->withHeaders([
                    'Authorization' => $fonnteToken,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $targetPhoneClean,
                    'message' => $waMessage,
                    'countryCode' => '62',
                ]);
            } catch (\Throwable $e) {
                Log::error('Fonnte WA send error: ' . $e->getMessage());
            }
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
