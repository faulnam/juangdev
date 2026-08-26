<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    /**
     * Get Project Slug and API Key.
     */
    public static function getCredentials(): array
    {
        return [
            'slug' => config('services.pakasir.slug') ?? env('PAKASIR_SLUG'),
            'api_key' => config('services.pakasir.api_key') ?? env('PAKASIR_API_KEY'),
            'url' => config('services.pakasir.url') ?? env('PAKASIR_URL'),
        ];
    }

    /**
     * Create Pakasir Transaction & get genuine payment data (QRIS / VA / Link).
     */
    public static function createTransaction(Order $order, string $channel = 'qris', string $type = 'dp'): array
    {
        $creds = self::getCredentials();
        $slug = $creds['slug'];
        $apiKey = $creds['api_key'];
        $baseUrl = $creds['url'];

        $amount = ($type === 'dp') ? $order->dp_amount : ($type === 'remaining' ? $order->remaining_amount : $order->total_amount);
        $orderId = $order->invoice_number . ($type === 'remaining' ? '-PELUNASAN' : '');
        $redirectUrl = route('customer.orders.show', $order->invoice_number);

        $directPayUrl = "{$baseUrl}/pay/{$slug}/{$amount}?order_id={$orderId}&redirect=" . urlencode($redirectUrl);

        $methodMap = [
            'qris' => 'qris',
            'va_bni' => 'bni_va',
            'va_bri' => 'bri_va',
            'va_permata' => 'permata_va',
        ];

        $targetMethod = $methodMap[$channel] ?? 'qris';

        try {
            $response = Http::withoutVerifying()
                ->timeout(12)
                ->asJson()
                ->post("{$baseUrl}/api/transactioncreate/{$targetMethod}", [
                    'project' => $slug,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'api_key' => $apiKey,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $payment = $data['payment'] ?? [];

                if (!empty($payment)) {
                    $paymentNumber = $payment['payment_number'] ?? null;
                    $qrImageUrl = null;
                    
                    if ($payment['payment_method'] === 'qris' && !empty($paymentNumber)) {
                        // Real ASPI QR String generated from Pakasir
                        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=" . urlencode($paymentNumber);
                    } else {
                        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=" . urlencode($directPayUrl);
                    }

                    return [
                        'success' => true,
                        'payment_url' => $directPayUrl,
                        'payment_method' => $payment['payment_method'] ?? $channel,
                        'payment_number' => $paymentNumber,
                        'total_payment' => $payment['total_payment'] ?? $amount,
                        'fee' => $payment['fee'] ?? 0,
                        'expired_at' => $payment['expired_at'] ?? null,
                        'qr_image_url' => $qrImageUrl,
                        'qr_string' => $paymentNumber,
                    ];
                }
            } else {
                Log::warning("Pakasir API transactioncreate/{$targetMethod} returned HTTP " . $response->status() . ": " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Pakasir Create Transaction Exception: ' . $e->getMessage());
        }

        // Fallback standard payment link
        return [
            'success' => true,
            'payment_url' => $directPayUrl,
            'payment_method' => $channel,
            'payment_number' => null,
            'total_payment' => $amount,
            'fee' => 0,
            'expired_at' => null,
            'qr_image_url' => "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=" . urlencode($directPayUrl),
            'qr_string' => $directPayUrl,
        ];
    }

    /**
     * Check Transaction Status directly with Pakasir API.
     */
    public static function checkTransactionStatus(Order $order, string $type = 'dp'): array
    {
        $creds = self::getCredentials();
        $slug = $creds['slug'];
        $apiKey = $creds['api_key'];
        $baseUrl = $creds['url'];

        $amount = ($type === 'dp') ? $order->dp_amount : ($type === 'remaining' ? $order->remaining_amount : $order->total_amount);
        $orderId = $order->invoice_number . ($type === 'remaining' ? '-PELUNASAN' : '');

        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get("{$baseUrl}/api/transactiondetail", [
                    'project' => $slug,
                    'amount' => $amount,
                    'order_id' => $orderId,
                    'api_key' => $apiKey,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $trx = $data['transaction'] ?? [];
                $status = strtolower($trx['status'] ?? '');

                if ($status === 'completed' || $status === 'paid' || $status === 'success') {
                    if ($type === 'dp' || $order->payment_scheme === 'full_100' || $order->payment_status === 'unpaid') {
                        $newStatus = ($order->payment_scheme === 'full_100') ? 'fully_paid' : 'dp_paid';
                        $order->payment_status = $newStatus;
                        $order->project_status = ($newStatus === 'fully_paid') ? 'completed' : 'in_progress';
                        if ($newStatus === 'fully_paid') {
                            $order->remaining_amount = 0;
                        }
                        $order->save();

                        self::sendCustomerPaymentSuccessWa($order, $newStatus === 'fully_paid' ? 'full' : 'dp');
                    } elseif ($type === 'remaining' || $order->payment_status === 'dp_paid') {
                        $order->payment_status = 'fully_paid';
                        $order->remaining_amount = 0;
                        $order->project_status = 'completed';
                        $order->save();

                        self::sendCustomerPaymentSuccessWa($order, 'full');
                    }

                    return ['paid' => true, 'status' => $order->payment_status, 'order' => $order];
                }
            }
        } catch (\Throwable $e) {
            Log::error('Pakasir Check Status Exception: ' . $e->getMessage());
        }

        return ['paid' => ($order->payment_status !== 'unpaid'), 'status' => $order->payment_status, 'order' => $order];
    }

    /**
     * Get Pakasir Payment Link for an Order.
     */
    public static function getPaymentUrl(Order $order, string $type = 'dp'): string
    {
        $res = self::createTransaction($order, 'qris', $type);
        return $res['payment_url'] ?? route('invoice.show', $order->invoice_number);
    }

    /**
     * Send Formal Corporate WhatsApp Message via Fonnte (Strictly NO Emojis).
     */
    public static function sendWaNotification(string $phone, string $message): bool
    {
        $targetPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($targetPhone, '0')) {
            $targetPhone = '62' . substr($targetPhone, 1);
        } elseif (str_starts_with($targetPhone, '8')) {
            $targetPhone = '628' . substr($targetPhone, 1);
        }

        $fonnteToken = config('services.fonnte.token') ?? env('FONNTE_TOKEN') ?? SiteSetting::where('key', 'fonnte_token')->value('value');
        if (empty($fonnteToken)) {
            Log::warning('Fonnte WA Send Warning: FONNTE_TOKEN is empty.');
            return false;
        }

        try {
            $response = Http::withoutVerifying()
                ->withOptions(['verify' => false, 'timeout' => 15])
                ->withHeaders([
                    'Authorization' => $fonnteToken,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $targetPhone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            Log::info("Fonnte WA Sent to {$targetPhone}: ", $response->json() ?? [$response->body()]);
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Fonnte WA Send Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Formal WA Invoice Created Notification to Customer.
     */
    public static function sendCustomerInvoiceWa(Order $order): void
    {
        $msg = "TAGIHAN RESMI DAN KONFIRMASI PESANAN\n"
            . "JuangDev Digital Solutions\n\n"
            . "Kepada Yth. Bapak/Ibu " . $order->customer_name . ",\n\n"
            . "Terima kasih atas kepercayaan Anda memilih JuangDev sebagai mitra pengembangan solusi digital.\n\n"
            . "Berikut rincian tagihan resmi pesanan Anda:\n"
            . "- Nomor Tagihan: " . $order->invoice_number . "\n"
            . "- Nama Proyek: " . ($order->project_name ?? $order->service_name) . "\n"
            . "- Layanan Utama: " . $order->service_name . "\n"
            . "- Paket Pilihan: " . ($order->package_name ?? '-') . "\n"
            . "- Total Nilai Proyek: " . $order->formatted_total . "\n"
            . "- Tagihan Uang Muka (DP 50%): " . $order->formatted_dp . "\n"
            . "- Sisa Pelunasan (50%): " . $order->formatted_remaining . "\n\n"
            . "Tautan Resmi Tagihan & Pembayaran:\n"
            . $order->invoice_url . "\n\n"
            . "Pembayaran Uang Muka (DP 50%) maupun Pelunasan dapat dilakukan secara mandiri melalui tautan resmi di atas.\n\n"
            . "Hormat kami,\n"
            . "Tim Manajemen JuangDev";

        self::sendWaNotification($order->customer_phone, $msg);
    }

    /**
     * Send Formal WA Admin Alert for New Order.
     */
    public static function sendAdminNewOrderWa(Order $order): void
    {
        $adminPhone = env('ADMIN_WA_NUMBER') ?? SiteSetting::where('key', 'whatsapp_number')->value('value') ?? '62859171681988';

        $msg = "PEMBERITAHUAN PESANAN PROYEK BARU\n"
            . "JuangDev Digital Solutions\n\n"
            . "Kepada Tim Admin JuangDev,\n\n"
            . "Telah diterima pesanan proyek baru melalui Estimator Biaya dengan rincian sebagai berikut:\n\n"
            . "- Nomor Tagihan: " . $order->invoice_number . "\n"
            . "- Nama Klien: " . $order->customer_name . "\n"
            . "- Nomor WhatsApp: " . $order->customer_phone . "\n"
            . "- Alamat Email: " . $order->customer_email . "\n"
            . "- Nama Proyek: " . ($order->project_name ?? '-') . "\n"
            . "- Layanan: " . $order->service_name . "\n"
            . "- Paket: " . ($order->package_name ?? '-') . "\n"
            . "- Total Nilai Proyek: " . $order->formatted_total . "\n"
            . "- Tagihan DP (50%): " . $order->formatted_dp . "\n"
            . "- Sisa Pelunasan (50%): " . $order->formatted_remaining . "\n\n"
            . "Tautan Invoice Resmi:\n"
            . $order->invoice_url . "\n\n"
            . "Pesan ini disampaikan secara otomatis oleh sistem JuangDev.";

        self::sendWaNotification($adminPhone, $msg);
    }

    /**
     * Send Formal WA Payment Received Confirmation to Customer.
     */
    public static function sendCustomerPaymentSuccessWa(Order $order, string $paymentType = 'dp'): void
    {
        $orderDetailUrl = route('customer.orders.show', $order->invoice_number);

        if ($paymentType === 'dp') {
            $msg = "KONFIRMASI PEMBAYARAN UANG MUKA (DP 50%)\n"
                . "JuangDev Digital Solutions\n\n"
                . "Kepada Yth. Bapak/Ibu " . $order->customer_name . ",\n\n"
                . "Dengan ini kami mengonfirmasikan bahwa pembayaran Uang Muka (DP 50%) untuk tagihan nomor " . $order->invoice_number . " telah berhasil diterima dan diverifikasi oleh sistem.\n\n"
                . "Rincian Transaksi:\n"
                . "- Nama Proyek: " . ($order->project_name ?? $order->service_name) . "\n"
                . "- Layanan: " . $order->service_name . "\n"
                . "- Paket: " . ($order->package_name ?? '-') . "\n"
                . "- Total Nilai Proyek: " . $order->formatted_total . "\n"
                . "- Jumlah DP Diterima: " . $order->formatted_dp . " (LUNAS)\n"
                . "- Sisa Pelunasan (50%): " . $order->formatted_remaining . "\n"
                . "- Status Pembayaran: DP 50% LUNAS\n"
                . "- Status Pengerjaan: Dalam Pengerjaan\n\n"
                . "KETENTUAN SISA PELUNASAN:\n"
                . "Sisa tagihan sebesar " . $order->formatted_remaining . " dapat Anda lunasi saat pengerjaan proyek telah selesai melalui halaman Detail Pesanan di akun Anda.\n\n"
                . "Tautan Detail Pesanan di Akun Anda:\n"
                . $orderDetailUrl . "\n\n"
                . "Tautan Invoice Resmi & Bukti Transaksi:\n"
                . $order->invoice_url . "\n\n"
                . "Tim teknis JuangDev telah memulai pengerjaan proyek Anda. Terima kasih atas kerja sama Anda.\n\n"
                . "Hormat kami,\n"
                . "Tim Manajemen JuangDev";
        } else {
            $pelunasanAmount = ($order->payment_scheme === 'full_100') ? $order->formatted_total : 'Rp ' . number_format($order->total_amount - $order->dp_amount, 0, ',', '.');
            $msg = "KONFIRMASI PELUNASAN DAN SERAH TERIMA PROYEK\n"
                . "JuangDev Digital Solutions\n\n"
                . "Kepada Yth. Bapak/Ibu " . $order->customer_name . ",\n\n"
                . "Dengan ini kami mengonfirmasikan bahwa pembayaran pelunasan untuk tagihan nomor " . $order->invoice_number . " (Proyek: " . ($order->project_name ?? $order->service_name) . ") telah berhasil diterima dan diverifikasi oleh sistem.\n\n"
                . "Rincian Transaksi:\n"
                . "- Total Nilai Proyek: " . $order->formatted_total . "\n"
                . "- Uang Muka (DP 50%): " . $order->formatted_dp . " (Lunas Sebelumnya)\n"
                . "- Pembayaran Pelunasan: " . $pelunasanAmount . " (Lunas Diterima)\n"
                . "- Sisa Tagihan: Rp 0 (LUNAS 100%)\n"
                . "- Status Pembayaran: LUNAS SEPENUHNYA (100%)\n"
                . "- Status Proyek: Selesai / Serah Terima\n\n"
                . "Seluruh rincian progres, berkas proyek, dan akses akun dapat Anda unduh dan pantau melalui tautan berikut:\n"
                . "Detail Pesanan di Akun Anda: " . $orderDetailUrl . "\n"
                . "Invoice Resmi & Bukti Transaksi: " . $order->invoice_url . "\n\n"
                . "Terima kasih atas kemitraan dan kepercayaan Anda bersama JuangDev.\n\n"
                . "Hormat kami,\n"
                . "Tim Manajemen JuangDev";
        }

        // Send to Customer
        self::sendWaNotification($order->customer_phone, $msg);

        // Send to Admin
        $adminPhone = env('ADMIN_WA_NUMBER') ?? SiteSetting::where('key', 'whatsapp_number')->value('value') ?? '62859171681988';
        $adminMsg = "PEMBERITAHUAN PENERIMAAN PEMBAYARAN (" . ($paymentType === 'dp' ? 'DP 50%' : 'LUNAS 100%') . ")\n"
            . "JuangDev Digital Solutions\n\n"
            . "Kepada Tim Admin JuangDev,\n\n"
            . "Telah diterima pembayaran " . ($paymentType === 'dp' ? 'DP 50%' : 'Pelunasan 100%') . " untuk tagihan nomor " . $order->invoice_number . ".\n\n"
            . "Rincian Transaksi:\n"
            . "- Nama Klien: " . $order->customer_name . "\n"
            . "- Nomor WhatsApp: " . $order->customer_phone . "\n"
            . "- Nama Proyek: " . ($order->project_name ?? $order->service_name) . "\n"
            . "- Total Proyek: " . $order->formatted_total . "\n"
            . "- Jumlah Diterima: " . ($paymentType === 'dp' ? $order->formatted_dp : ($order->payment_scheme === 'full_100' ? $order->formatted_total : 'Rp ' . number_format($order->total_amount - $order->dp_amount, 0, ',', '.'))) . "\n"
            . "- Status Pembayaran: " . ($paymentType === 'dp' ? 'DP 50% LUNAS' : 'LUNAS 100%') . "\n\n"
            . "Tautan Invoice Resmi:\n"
            . $order->invoice_url . "\n\n"
            . "Sistem telah memperbarui status pesanan secara otomatis.";

        self::sendWaNotification($adminPhone, $adminMsg);
    }
}
