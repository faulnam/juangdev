<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of registered customers & clients.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->whereIn('payment_status', ['dp_paid', 'fully_paid']);
            }], 'total_amount')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15);

        $totalRegistered = User::count();
        $totalGoogleUsers = User::whereNotNull('firebase_uid')->count();
        $totalEmailUsers = User::whereNull('firebase_uid')->count();

        return view('admin.customers.index', compact('customers', 'totalRegistered', 'totalGoogleUsers', 'totalEmailUsers'));
    }

    /**
     * Display detailed profile & order history of a specific customer.
     */
    public function show($id)
    {
        // Support searching by User ID or phone number
        if (is_numeric($id)) {
            $user = User::find($id);
        } else {
            $user = User::where('phone', $id)->orWhere('email', $id)->first();
        }

        if ($user) {
            $customerName = $user->name;
            $customerEmail = $user->email;
            $phone = $user->phone ?? '-';
            
            $orders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
                if (!empty($user->phone)) {
                    $q->orWhere('customer_phone', $user->phone);
                }
            })->latest()->get();

            $contacts = Contact::where('email', $user->email)
                ->orWhere(function ($q) use ($user) {
                    if (!empty($user->phone)) {
                        $clean = preg_replace('/[^0-9]/', '', $user->phone);
                        $q->where('phone', 'like', "%{$clean}%");
                    }
                })->latest()->get();
        } else {
            $cleanPhone = preg_replace('/[^0-9]/', '', $id);
            $orders = Order::where('customer_phone', 'like', "%{$cleanPhone}%")
                ->orWhere('customer_phone', $id)
                ->latest()
                ->get();

            $contacts = Contact::where('phone', 'like', "%{$cleanPhone}%")
                ->orWhere('phone', $id)
                ->latest()
                ->get();

            $customerName = $orders->first()->customer_name ?? $contacts->first()->name ?? 'Pelanggan JuangDev';
            $customerEmail = $orders->first()->customer_email ?? $contacts->first()->email ?? '-';
            $phone = $id;
        }

        return view('admin.customers.show', compact('user', 'phone', 'customerName', 'customerEmail', 'orders', 'contacts'));
    }
}
