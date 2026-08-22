<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query aggregated customers from orders
        $query = Order::select(
            'customer_name',
            'customer_email',
            'customer_phone',
            DB::raw('COUNT(id) as total_orders'),
            DB::raw('SUM(total_amount) as total_spent'),
            DB::raw('MAX(created_at) as last_order_at')
        )
        ->groupBy('customer_phone', 'customer_email', 'customer_name');

        if ($search) {
            $query->having(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('last_order_at', 'desc')->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($phone)
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        $orders = Order::where('customer_phone', 'like', "%{$cleanPhone}%")
            ->orWhere('customer_phone', $phone)
            ->latest()
            ->get();

        $contacts = Contact::where('phone', 'like', "%{$cleanPhone}%")
            ->orWhere('phone', $phone)
            ->latest()
            ->get();

        $customerName = $orders->first()->customer_name ?? $contacts->first()->name ?? 'Pelanggan JuangDev';
        $customerEmail = $orders->first()->customer_email ?? $contacts->first()->email ?? '-';

        return view('admin.customers.show', compact('phone', 'customerName', 'customerEmail', 'orders', 'contacts'));
    }
}
