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

        // Payment status filter
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Project status filter
        if ($request->filled('project_status')) {
            $query->where('project_status', $request->project_status);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%");
            });
        }

        // Counts for quick tab badges
        $counts = [
            'all' => Order::count(),
            'unpaid' => Order::where('payment_status', 'unpaid')->count(),
            'dp_paid' => Order::where('payment_status', 'dp_paid')->count(),
            'fully_paid' => Order::where('payment_status', 'fully_paid')->count(),
            'in_progress' => Order::where('project_status', 'in_progress')->count(),
            'completed' => Order::where('project_status', 'completed')->count(),
        ];

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    /**
     * Export orders list to Excel / CSV format.
     */
    public function export(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('project_status')) {
            $query->where('project_status', $request->project_status);
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

        $orders = $query->get();
        $filename = 'Laporan-Pesanan-JuangDev-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'No. Invoice',
            'Waktu Transaksi',
            'Nama Klien',
            'No. WhatsApp',
            'Email',
            'Layanan',
            'Paket',
            'Nama Proyek',
            'Total Nilai (Rp)',
            'Tagihan DP 50% (Rp)',
            'Sisa Pelunasan (Rp)',
            'Status Pembayaran',
            'Status Pengerjaan',
            'Skema Pembayaran'
        ];

        $callback = function() use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($orders as $ord) {
                fputcsv($file, [
                    $ord->invoice_number,
                    $ord->created_at ? $ord->created_at->format('Y-m-d H:i:s') : '-',
                    $ord->customer_name,
                    $ord->customer_phone,
                    $ord->customer_email,
                    $ord->service_name,
                    $ord->package_name ?? '-',
                    $ord->project_name ?? '-',
                    $ord->total_amount,
                    $ord->dp_amount,
                    $ord->remaining_amount,
                    strtoupper(str_replace('_', ' ', $ord->payment_status)),
                    strtoupper(str_replace('_', ' ', $ord->project_status)),
                    $ord->payment_scheme === 'full_100' ? 'Lunas 100%' : 'DP 50%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
