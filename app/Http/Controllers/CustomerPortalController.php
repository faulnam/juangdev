<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerPortalController extends Controller
{
    /**
     * Customer Dashboard (Detail Pesanan & Informasi Akun).
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get all orders associated with this user (by user_id or matching email/phone)
        $orders = Order::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('customer_email', $user->email);
            if (!empty($user->phone)) {
                $query->orWhere('customer_phone', $user->phone);
            }
        })
        ->latest()
        ->get();

        // Link orders without user_id to this user
        foreach ($orders as $o) {
            if (empty($o->user_id)) {
                $o->user_id = $user->id;
                $o->save();
            }
        }

        $totalSpent = $orders->whereIn('payment_status', ['dp_paid', 'fully_paid'])->sum('total_amount');
        $activeProjects = $orders->whereIn('project_status', ['pending', 'in_progress'])->count();
        $completedProjects = $orders->where('project_status', 'completed')->count();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.customer.dashboard', compact(
            'user',
            'orders',
            'totalSpent',
            'activeProjects',
            'completedProjects',
            'settings'
        ));
    }

    /**
     * Customer Orders List & Detail.
     */
    public function orders()
    {
        return $this->dashboard();
    }

    /**
     * Customer Profile & Information.
     */
    public function profile()
    {
        return $this->dashboard();
    }

    /**
     * Dedicated Order Detail Page (Gambar 5 Style).
     */
    public function showOrder($invoiceNumber)
    {
        $user = Auth::user();

        $order = Order::where('invoice_number', $invoiceNumber)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
                if (!empty($user->phone)) {
                    $query->orWhere('customer_phone', $user->phone);
                }
            })
            ->firstOrFail();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        // Check if customer already submitted testimonial
        $testimonial = Testimonial::where('name', $user->name)->latest()->first();

        // Generate genuine Pakasir payment data (Live ASPI QRIS string / VA / Link) for unpaid/pelunasan
        $pakasirData = null;
        if ($order->payment_status !== 'fully_paid') {
            $type = ($order->payment_status === 'dp_paid') ? 'remaining' : ($order->payment_scheme === 'full_100' ? 'full' : 'dp');
            $channel = $order->payment_channel ?? 'qris';
            $pakasirData = \App\Services\PakasirService::createTransaction($order, $channel, $type);
        }

        return view('pages.customer.order-detail', compact('user', 'order', 'settings', 'testimonial', 'pakasirData'));
    }

    /**
     * Submit Testimonial for an Order.
     */
    public function submitTestimonial(Request $request, $invoiceNumber)
    {
        $user = Auth::user();

        $order = Order::where('invoice_number', $invoiceNumber)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
                if (!empty($user->phone)) {
                    $query->orWhere('customer_phone', $user->phone);
                }
            })
            ->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ], [
            'content.required' => 'Pesan testimonial wajib diisi.',
            'content.min' => 'Pesan testimonial minimal 5 karakter.',
        ]);

        Testimonial::create([
            'name' => $user->name,
            'role' => 'Klien Proyek ' . ($order->project_name ?? $order->service_name),
            'company' => 'Customer JuangDev',
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? 5,
            'featured' => false,
            'display_order' => 0,
        ]);

        return back()->with('success', 'Terima kasih! Testimonial Anda berhasil dikirimkan.');
    }

    /**
     * Update Customer Profile Information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:9|max:20',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $user->name = $validated['name'];
        $user->phone = $phone;

        if (!empty($validated['new_password'])) {
            if (!empty($user->password) && !Hash::check($validated['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Informasi profil Anda berhasil diperbarui.');
    }
}
