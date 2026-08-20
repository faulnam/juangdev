<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

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

        Contact::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda berhasil dikirim! Tim JuangDev akan segera menghubungi Anda.',
            ]);
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim! Tim JuangDev akan segera menghubungi Anda.');
    }
}
