<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SiteSetting;
use App\Services\PakasirService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Store new order from Estimator.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'project_name' => 'nullable|string|max:255',
            'service_name' => 'required|string|max:255',
            'package_name' => 'nullable|string|max:255',
            'addons' => 'nullable|array',
            'total_amount' => 'required|numeric|min:0',
            'payment_scheme' => 'required|string|in:dp_50,full_100',
            'notes' => 'nullable|string',
        ]);

        $invNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $token = Str::random(32);

        $total = (int) $validated['total_amount'];
        $isFull = ($validated['payment_scheme'] === 'full_100');
        $dp = $isFull ? $total : (int) round($total * 0.5);
        $initialStatus = $isFull ? 'fully_paid' : 'dp_paid';
        $initialProjectStatus = $isFull ? 'completed' : 'in_progress';
        $remaining = $isFull ? 0 : ($total - $dp);

        $order = Order::create([
            'invoice_number' => $invNumber,
            'token' => $token,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'project_name' => $validated['project_name'] ?? null,
            'service_name' => $validated['service_name'],
            'package_name' => $validated['package_name'] ?? null,
            'addons' => $validated['addons'] ?? [],
            'total_amount' => $total,
            'dp_amount' => $dp,
            'remaining_amount' => $remaining,
            'payment_scheme' => $validated['payment_scheme'],
            'payment_status' => $initialStatus,
            'project_status' => $initialProjectStatus,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Send payment confirmation WA directly (1 WA for customer, 1 for admin)
        PakasirService::sendCustomerPaymentSuccessWa($order, $isFull ? 'full' : 'dp');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'invoice_number' => $order->invoice_number,
                'invoice_url' => route('invoice.show', $order->invoice_number),
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'project_name' => $order->project_name,
                'service_name' => $order->service_name,
                'package_name' => $order->package_name,
                'total_amount' => $order->total_amount,
                'dp_amount' => $order->dp_amount,
                'remaining_amount' => $order->remaining_amount,
                'payment_scheme' => $order->payment_scheme,
                'payment_status' => $order->payment_status,
                'project_status' => $order->project_status,
                'created_at' => $order->created_at->toISOString(),
            ]);
        }

        return redirect()->route('invoice.show', $order->invoice_number);
    }

    /**
     * Show order invoice on dedicated invoice page.
     */
    public function show($invoiceNumber)
    {
        $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();
        $order->refresh();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.invoice', compact('order', 'settings'));
    }

    /**
     * Process Pakasir payment action / simulation.
     * Has idempotency guards to prevent double-processing on page refresh.
     */
    public function pay(Request $request, $invoiceNumber)
    {
        $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();
        $type = $request->input('type', 'dp'); // 'dp' or 'remaining' or 'full'

        // IDEMPOTENCY GUARD: Prevent re-processing if already paid
        if ($order->payment_status === 'fully_paid') {
            // Already fully paid, just redirect without sending WA again
            Log::info('Payment SKIPPED (already fully_paid): ' . $order->invoice_number);
            return redirect()->route('invoice.show', ['invoiceNumber' => $order->invoice_number, 't' => time()])
                ->with('success', 'Pembayaran sudah lunas sebelumnya.');
        }

        if (($type === 'dp') && $order->payment_status === 'dp_paid') {
            // DP already paid, just redirect without sending WA again
            Log::info('Payment SKIPPED (dp already paid): ' . $order->invoice_number);
            return redirect()->route('invoice.show', ['invoiceNumber' => $order->invoice_number, 't' => time()])
                ->with('success', 'Pembayaran DP sudah diterima sebelumnya.');
        }

        if ($type === 'dp' || $type === 'full') {
            $newStatus = ($order->payment_scheme === 'full_100' || $type === 'full') ? 'fully_paid' : 'dp_paid';
            $order->payment_status = $newStatus;
            $order->project_status = ($newStatus === 'fully_paid') ? 'completed' : 'in_progress';
            $order->remaining_amount = ($newStatus === 'fully_paid') ? 0 : ($order->total_amount - $order->dp_amount);
            $order->save();

            $freshOrder = Order::find($order->id);
            Log::info('Payment processed: ' . $freshOrder->invoice_number . ' -> ' . $freshOrder->payment_status);
            PakasirService::sendCustomerPaymentSuccessWa($freshOrder, $freshOrder->payment_status === 'fully_paid' ? 'full' : 'dp');
        } elseif ($type === 'remaining') {
            $order->payment_status = 'fully_paid';
            $order->remaining_amount = 0;
            $order->project_status = 'completed';
            $order->save();

            $freshOrder = Order::find($order->id);
            Log::info('Pelunasan processed: ' . $freshOrder->invoice_number . ' -> ' . $freshOrder->payment_status);
            PakasirService::sendCustomerPaymentSuccessWa($freshOrder, 'full');
        }

        return redirect()->route('invoice.show', ['invoiceNumber' => $order->invoice_number, 't' => time()])
            ->with('success', 'Pembayaran berhasil diproses dan dikonfirmasi.');
    }

    /**
     * Handle Pakasir Webhook.
     */
    public function webhook(Request $request)
    {
        Log::info('Pakasir Webhook Payload: ', $request->all());

        $invoiceNumber = $request->input('invoice_number') ?? $request->input('order_id');
        $status = strtolower($request->input('status') ?? 'paid');

        if ($invoiceNumber) {
            $order = Order::where('invoice_number', $invoiceNumber)->first();
            if ($order && in_array($status, ['paid', 'success', 'completed'])) {
                if ($order->payment_status === 'unpaid') {
                    $order->update([
                        'payment_status' => ($order->payment_scheme === 'full_100') ? 'fully_paid' : 'dp_paid',
                        'project_status' => 'in_progress',
                    ]);
                    PakasirService::sendCustomerPaymentSuccessWa($order, $order->payment_status === 'fully_paid' ? 'full' : 'dp');
                } elseif ($order->payment_status === 'dp_paid') {
                    $order->update([
                        'payment_status' => 'fully_paid',
                        'remaining_amount' => 0,
                        'project_status' => 'completed',
                    ]);
                    PakasirService::sendCustomerPaymentSuccessWa($order, 'full');
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
