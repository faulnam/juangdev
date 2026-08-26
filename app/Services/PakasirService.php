<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    /**
     * Get Pakasir Payment Link for an Order.
     */
    public static function getPaymentUrl(Order $order, string $type = 'dp'): string
    {
        $pakasirSlug = env('PAKASIR_SLUG') ?? SiteSetting::where('key', 'pakasir_slug')->value('value') ?? 'juangdev';
        $amount = ($type === 'dp') ? $order->dp_amount : $order->remaining_amount;

        // Returns Pakasir payment link or public invoice page URL
        return route('invoice.show', $order->invoice_number);
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

        $fonnteToken = config('services.fonnte.token') ?? env('FONNTE_TOKEN');
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
            . "- Tagihan Uang Muka (DP 50%): " . $order->formatted_dp . "\n\n"
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
            . "- Tagihan DP: " . $order->formatted_dp . "\n\n"
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
        if ($paymentType === 'dp') {
            $msg = "KONFIRMASI PEMBAYARAN UANG MUKA (DP 50%)\n"
                . "JuangDev Digital Solutions\n\n"
                . "Kepada Yth. Bapak/Ibu " . $order->customer_name . ",\n\n"
                . "Dengan ini kami mengonfirmasikan bahwa pembayaran Uang Muka (DP 50%) untuk tagihan nomor " . $order->invoice_number . " telah berhasil diterima dan diverifikasi oleh sistem.\n\n"
                . "Rincian Pembayaran:\n"
                . "- Nama Proyek: " . ($order->project_name ?? $order->service_name) . "\n"
                . "- Jumlah DP Diterima: " . $order->formatted_dp . "\n"
                . "- Sisa Pelunasan: " . $order->formatted_remaining . "\n"
                . "- Status Proyek: Dalam Pengerjaan\n\n"
                . "Tautan Pemantauan Proyek & Pelunasan:\n"
                . $order->invoice_url . "\n\n"
                . "Tim teknis JuangDev telah memulai pengerjaan proyek Anda. Terima kasih atas kepercayaan dan kerja sama Anda.\n\n"
                . "Hormat kami,\n"
                . "Tim Manajemen JuangDev";
        } else {
            $msg = "KONFIRMASI PELUNASAN DAN SERAH TERIMA PROYEK\n"
                . "JuangDev Digital Solutions\n\n"
                . "Kepada Yth. Bapak/Ibu " . $order->customer_name . ",\n\n"
                . "Dengan ini kami mengonfirmasikan bahwa pembayaran pelunasan untuk tagihan nomor " . $order->invoice_number . " (Proyek: " . ($order->project_name ?? $order->service_name) . ") telah berhasil diterima secara penuh.\n\n"
                . "Rincian Transaksi:\n"
                . "- Status Pembayaran: LUNAS 100%\n"
                . "- Total Pembayaran: " . $order->formatted_total . "\n\n"
                . "Seluruh berkas, dokumen proyek, dan akses akun dapat Anda pantau melalui tautan resmi berikut:\n"
                . $order->invoice_url . "\n\n"
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
            . "- Nomor WhatsApp Klien: " . $order->customer_phone . "\n"
            . "- Nama Proyek: " . ($order->project_name ?? $order->service_name) . "\n"
            . "- Jumlah Diterima: " . ($paymentType === 'dp' ? $order->formatted_dp : $order->formatted_total) . "\n"
            . "- Status Pembayaran: " . ($paymentType === 'dp' ? 'DP 50% LUNAS' : 'LUNAS 100%') . "\n\n"
            . "Tautan Invoice Resmi:\n"
            . $order->invoice_url . "\n\n"
            . "Sistem telah memperbarui status pesanan secara otomatis.";

        self::sendWaNotification($adminPhone, $adminMsg);
    }
}
