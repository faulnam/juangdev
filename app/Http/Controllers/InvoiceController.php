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
        if ($request->has('addons') && is_string($request->input('addons'))) {
            $decoded = json_decode($request->input('addons'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['addons' => $decoded]);
            }
        }

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
            'payment_channel' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z,png,jpg,jpeg,webp|max:20480',
        ]);

        $invNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $token = Str::random(32);

        $total = (int) $validated['total_amount'];
        $isFull = ($validated['payment_scheme'] === 'full_100');
        $dp = $isFull ? $total : (int) round($total * 0.5);
        $remaining = $isFull ? 0 : ($total - $dp);

        $userId = auth()->id();
        if (!$userId) {
            $existingUser = \App\Models\User::where('email', $validated['customer_email'])->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentSize = null;

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentSize = $file->getSize();

            $destinationFolder = 'uploads/orders/attachments';
            $destinationPath = public_path($destinationFolder);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $ext = strtolower($file->getClientOriginalExtension());
            $safeName = time() . '_' . Str::random(8) . ($ext ? '.' . $ext : '');
            $file->move($destinationPath, $safeName);
            $attachmentPath = '/' . $destinationFolder . '/' . $safeName;
        }

        // INITIAL ORDER IS ALWAYS UNPAID AND PROJECT PENDING
        $order = Order::create([
            'user_id' => $userId,
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
            'payment_status' => 'unpaid',
            'project_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
        ]);

        // WA notification is strictly sent ONLY after payment is completed (DP 50% or Full)

        // Generate genuine Pakasir Transaction (Live ASPI QRIS string, VA, or Direct Link)
        $channel = $request->input('payment_channel', 'qris');
        $type = $isFull ? 'full' : 'dp';
        $pakasirData = PakasirService::createTransaction($order, $channel, $type);

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
                'notes' => $order->notes,
                'attachment_path' => $order->attachment_path,
                'attachment_name' => $order->attachment_name,
                'attachment_url' => $order->attachment_url,
                'formatted_attachment_size' => $order->formatted_attachment_size,
                'pakasir' => $pakasirData,
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

        // Generate real Pakasir transaction data for this invoice
        $type = ($order->payment_status === 'dp_paid') ? 'remaining' : ($order->payment_scheme === 'full_100' ? 'full' : 'dp');
        $pakasirData = PakasirService::createTransaction($order, 'qris', $type);

        return view('pages.invoice', compact('order', 'settings', 'pakasirData'));
    }

    /**
     * Check real-time payment status via Pakasir API.
     */
    public function checkStatus(Request $request, $invoiceNumber)
    {
        $order = Order::where('invoice_number', $invoiceNumber)->first();
        if (!$order) {
            return response()->json(['paid' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $type = $request->input('type', $order->payment_status === 'dp_paid' ? 'remaining' : 'dp');
        $result = PakasirService::checkTransactionStatus($order, $type);

        return response()->json([
            'paid' => $result['paid'] ?? false,
            'payment_status' => $order->payment_status,
            'project_status' => $order->project_status,
            'remaining_amount' => $order->remaining_amount,
            'formatted_remaining' => $order->formatted_remaining,
        ]);
    }

    /**
     * Process Pakasir payment action: Redirect customer directly to official Pakasir Gateway.
     */
    public function pay(Request $request, $invoiceNumber)
    {
        $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();
        $type = $request->input('type', ($order->payment_status === 'dp_paid') ? 'remaining' : 'dp');

        // IDEMPOTENCY GUARD: Prevent re-processing if already paid
        if ($order->payment_status === 'fully_paid') {
            Log::info('Payment SKIPPED (already fully_paid): ' . $order->invoice_number);
            return redirect()->route('customer.orders.show', $order->invoice_number)
                ->with('success', 'Pembayaran sudah lunas sebelumnya.');
        }

        if (($type === 'dp') && $order->payment_status === 'dp_paid') {
            Log::info('Payment SKIPPED (dp already paid): ' . $order->invoice_number);
            return redirect()->route('customer.orders.show', $order->invoice_number)
                ->with('success', 'Pembayaran DP sudah diterima sebelumnya.');
        }

        // Direct user to official Pakasir payment gateway
        $pakasirData = PakasirService::createTransaction($order, 'qris', $type);
        return redirect($pakasirData['payment_url']);
    }

    /**
     * Handle Pakasir Webhook.
     */
    public function webhook(Request $request)
    {
        Log::info('Pakasir Webhook Payload: ', $request->all());

        $rawOrderId = $request->input('order_id') ?? $request->input('invoice_number');
        $status = strtolower($request->input('status') ?? 'completed');
        $isPelunasan = str_contains($rawOrderId, '-PELUNASAN');
        $invoiceNumber = str_replace('-PELUNASAN', '', $rawOrderId);

        if ($invoiceNumber) {
            $order = Order::where('invoice_number', $invoiceNumber)->first();
            if ($order && in_array($status, ['paid', 'success', 'completed'])) {
                if ($isPelunasan || $order->payment_status === 'dp_paid') {
                    $order->update([
                        'payment_status' => 'fully_paid',
                        'remaining_amount' => 0,
                        'project_status' => 'completed',
                    ]);
                    PakasirService::sendCustomerPaymentSuccessWa($order, 'full');
                } else {
                    $newStatus = ($order->payment_scheme === 'full_100') ? 'fully_paid' : 'dp_paid';
                    $order->update([
                        'payment_status' => $newStatus,
                        'project_status' => ($newStatus === 'fully_paid') ? 'completed' : 'in_progress',
                        'remaining_amount' => ($newStatus === 'fully_paid') ? 0 : ($order->total_amount - $order->dp_amount),
                    ]);
                    PakasirService::sendCustomerPaymentSuccessWa($order, $newStatus === 'fully_paid' ? 'full' : 'dp');
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
