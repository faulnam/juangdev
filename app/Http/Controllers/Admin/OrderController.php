<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PakasirService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|string|in:unpaid,dp_paid,fully_paid',
            'project_status' => 'required|string|in:pending,in_progress,completed,cancelled',
        ]);

        $prevPaymentStatus = $order->payment_status;
        $order->update($validated);

        if ($prevPaymentStatus !== $validated['payment_status']) {
            if ($validated['payment_status'] === 'dp_paid') {
                PakasirService::sendCustomerPaymentSuccessWa($order, 'dp');
            } elseif ($validated['payment_status'] === 'fully_paid') {
                PakasirService::sendCustomerPaymentSuccessWa($order, 'full');
            }
        }

        return back()->with('success', 'Status pesanan dan pembayaran berhasil diperbarui.');
    }

    public function sendWaReminder(Order $order)
    {
        PakasirService::sendCustomerInvoiceWa($order);
        return back()->with('success', 'Pesan tagihan resmi berhasil dikirimkan ke WhatsApp Klien.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Data pesanan berhasil dihapus.');
    }
}
