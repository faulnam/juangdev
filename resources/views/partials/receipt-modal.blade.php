@php
    $isPaidFull = isset($order) && $order->payment_status === 'fully_paid';
    $isDpPaid = isset($order) && $order->payment_status === 'dp_paid';
    $isUnpaid = isset($order) && $order->payment_status === 'unpaid';

    // Mask phone number: e.g. 0859171681988 -> 0859 **** **** 988
    $rawPhone = isset($order) ? $order->customer_phone : '';
    $maskedPhone = $rawPhone;
    if (strlen($rawPhone) >= 8) {
        $prefix = substr($rawPhone, 0, 4);
        $suffix = substr($rawPhone, -3);
        $maskedPhone = $prefix . ' **** **** ' . $suffix;
    }

    $feeAmount = isset($pakasirData) && !empty($pakasirData['fee']) ? $pakasirData['fee'] : 0;
    $feeFormatted = $feeAmount > 0 ? 'Rp ' . number_format($feeAmount, 0, ',', '.') : 'Rp 0';

    $currentPaidAmount = 'Rp 0';
    $trxType = 'Pembayaran Tagihan Proyek';
    if ($isPaidFull) {
        $currentPaidAmount = isset($order) ? $order->formatted_total : 'Rp 0';
        $trxType = ($order->payment_scheme === 'full_100') ? 'Pelunasan Langsung (100%)' : 'Pelunasan Sisa Proyek (100%)';
    } elseif ($isDpPaid) {
        $currentPaidAmount = isset($order) ? $order->formatted_dp : 'Rp 0';
        $trxType = 'Pembayaran Uang Muka (DP 50%)';
    } else {
        $baseDp = isset($order) ? $order->dp_amount : 0;
        $currentPaidAmount = 'Rp ' . number_format($baseDp + $feeAmount, 0, ',', '.');
        $trxType = 'Tagihan Menunggu Pembayaran';
    }
@endphp

<!-- Formal Corporate E-Receipt Component -->
<div id="receipt-print-area" class="hidden print:block font-sans text-slate-900 bg-white max-w-lg mx-auto">
    <div class="receipt-card bg-white rounded-2xl border-2 border-slate-300 p-8 relative text-slate-800 shadow-none">
        
        <!-- Header: Brand Logo & Company Info -->
        <div class="text-center pb-5 border-b-2 border-slate-200">
            <div class="inline-flex items-center justify-center gap-2 mb-1">
                <span class="text-2xl font-black tracking-tight text-slate-900">Juang<span class="text-[#2563EB]">Dev</span></span>
            </div>
            <p class="text-[11px] font-bold tracking-widest text-slate-500 uppercase">PT JUANG SOLUSI DIGITAL</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Software House & Digital Solution Partner</p>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <h2 class="rec-title text-base sm:text-lg font-black text-slate-900 uppercase tracking-wide">
                    @if($isPaidFull)
                        Bukti Transaksi Resmi (Lunas 100%)
                    @elseif($isDpPaid)
                        Bukti Pembayaran Uang Muka (DP 50%)
                    @else
                        Tagihan Transaksi Resmi (Invoice)
                    @endif
                </h2>
                <p class="rec-status-subtitle text-[11px] font-semibold text-slate-500 mt-0.5">
                    Bukti Pembayaran Elektronik Resmi JuangDev
                </p>
            </div>
        </div>

        <!-- Key-Value Transaction Details -->
        <div class="py-4 space-y-2.5 text-xs text-slate-700">
            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Waktu Transaksi</span>
                <span class="rec-date font-semibold text-slate-900 text-right">
                    {{ isset($order) && $order->created_at ? $order->created_at->format('Y-m-d H:i:s') . ' WIB' : date('Y-m-d H:i:s') . ' WIB' }}
                </span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Nomor Referensi</span>
                <span class="rec-inv font-mono font-black text-slate-900 text-right">
                    {{ isset($order) ? $order->invoice_number : '-' }}
                </span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Nama Pelanggan</span>
                <div class="text-right">
                    <div class="rec-name font-bold text-slate-900 uppercase">{{ isset($order) ? $order->customer_name : '-' }}</div>
                    <div class="rec-phone font-mono text-[11px] text-slate-500 font-medium">{{ $maskedPhone }}</div>
                </div>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Jenis Transaksi</span>
                <span class="rec-trx-type font-bold text-slate-900 text-right">{{ $trxType }}</span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Metode Pembayaran</span>
                <span class="rec-method font-semibold text-slate-900 text-right">QRIS / Virtual Account Pakasir</span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Penyedia Jasa</span>
                <span class="font-bold text-slate-900 text-right">PT JUANG SOLUSI DIGITAL</span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Layanan / Proyek</span>
                <div class="text-right">
                    <span class="rec-service font-bold text-slate-900">{{ isset($order) ? $order->service_name : '-' }}</span>
                    @if(isset($order) && $order->package_name)
                        <span class="rec-pkg block text-[11px] text-slate-500">Paket: {{ $order->package_name }}</span>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Nama Proyek / Catatan</span>
                <span class="rec-notes font-medium text-slate-800 text-right">
                    {{ isset($order) && $order->project_name ? $order->project_name : 'Pembayaran Resmi Proyek JuangDev' }}
                </span>
            </div>
        </div>

        <!-- Dotted Separator -->
        <div class="border-t-2 border-dashed border-slate-300 my-3"></div>

        <!-- Amount Breakdown Table -->
        <div class="py-2 space-y-2 text-xs text-slate-700">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium">Total Nilai Kontrak Proyek</span>
                <span class="rec-total-cost font-bold text-slate-900">
                    {{ isset($order) ? $order->formatted_total : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium">Tagihan Uang Muka (DP 50%)</span>
                <span class="rec-dp font-semibold text-slate-900">
                    {{ isset($order) ? $order->formatted_dp : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium">Sisa Pelunasan (50%)</span>
                <span class="rec-rem font-semibold text-slate-900">
                    {{ isset($order) ? ($order->payment_status === 'fully_paid' ? 'Rp 0 (LUNAS)' : $order->formatted_remaining) : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium">Biaya Layanan / Admin Gateway</span>
                <span class="rec-fee font-semibold text-slate-900">
                    {{ $feeFormatted }}
                </span>
            </div>
        </div>

        <!-- Solid Separator -->
        <div class="border-t-2 border-slate-900 my-3"></div>

        <!-- Total Paid Highlight -->
        <div class="py-2 flex justify-between items-center">
            <div>
                <span class="text-xs font-black text-slate-900 uppercase tracking-wider block">Total Tagihan / Pembayaran</span>
                <span class="text-[10px] text-slate-500">Termasuk Pajak &amp; Biaya Layanan</span>
            </div>
            <span class="rec-total-highlight text-2xl font-black text-[#2563EB] tracking-tight">
                {{ $currentPaidAmount }}
            </span>
        </div>

        <!-- Corporate Footer Disclaimer -->
        <div class="mt-5 pt-4 border-t border-slate-200 text-center space-y-1">
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                &copy; {{ date('Y') }} PT JUANG SOLUSI DIGITAL (JUANGDEV)
            </p>
            <p class="text-[9px] text-slate-400 leading-relaxed">
                Bukti transaksi ini diterbitkan secara elektronik dan sah secara hukum perundang-undangan Republik Indonesia.
            </p>
        </div>

    </div>
</div>
