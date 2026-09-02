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
<div id="receipt-print-area" class="hidden print:block text-slate-900 bg-white max-w-lg mx-auto" style="font-family: 'Courier New', Courier, 'Courier Prime', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace;">
    <div class="receipt-card bg-white p-4 sm:p-6 relative text-slate-900 shadow-none space-y-3.5">
        
        <!-- Header: Brand Favicon Logo & Company Info -->
        <div class="text-center pb-4 border-b-2 border-dashed border-slate-300">
            <div class="flex flex-col items-center justify-center gap-1.5 mb-1">
                <img src="{{ asset('logo2.png') }}" alt="JuangDev Logo" class="w-12 h-12 object-contain mx-auto drop-shadow-xs">
                <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Juang<span class="text-[#2563EB]">Dev</span></span>
            </div>
            <p class="text-xs font-black tracking-widest text-slate-700 uppercase">JUANG SOLUSI DIGITAL</p>
            <p class="text-[10px] text-slate-500 font-medium tracking-tight">Software House &amp; Digital Solution Partner</p>

            <div class="mt-3 pt-2.5 border-t border-dashed border-slate-200">
                <h2 class="rec-title text-sm sm:text-base font-black text-slate-900 uppercase tracking-wider">
                    @if($isPaidFull)
                        *** BUKTI TRANSAKSI RESMI (LUNAS 100%) ***
                    @elseif($isDpPaid)
                        *** BUKTI PEMBAYARAN UANG MUKA (DP 50%) ***
                    @else
                        *** TAGIHAN TRANSAKSI RESMI (INVOICE) ***
                    @endif
                </h2>
                <p class="rec-status-subtitle text-[10px] font-bold text-slate-500 mt-0.5 tracking-tight uppercase">
                    Bukti Pembayaran Elektronik Sah &amp; Terverifikasi
                </p>
            </div>
        </div>

        <!-- Key-Value Transaction Details (Formal Struk Layout) -->
        <div class="py-1 space-y-2 text-xs text-slate-800 font-medium">
            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">WAKTU TRANSAKSI</span>
                <span class="rec-date font-bold text-slate-900 text-right">
                    {{ isset($order) && $order->created_at ? $order->created_at->format('Y-m-d H:i:s') . ' WIB' : date('Y-m-d H:i:s') . ' WIB' }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">NO. REFERENSI</span>
                <span class="rec-inv font-black text-slate-900 text-right">
                    #{{ isset($order) ? $order->invoice_number : '-' }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">NAMA PELANGGAN</span>
                <div class="text-right">
                    <div class="rec-name font-bold text-slate-900 uppercase">{{ isset($order) ? $order->customer_name : '-' }}</div>
                    <div class="rec-phone text-[11px] text-slate-500 font-semibold">{{ $maskedPhone }}</div>
                </div>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">JENIS TRANSAKSI</span>
                <span class="rec-trx-type font-bold text-slate-900 text-right uppercase">{{ $trxType }}</span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">METODE PEMBAYARAN</span>
                <span class="rec-method font-bold text-slate-900 text-right">QRIS STANDAR INDONESIA</span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">PENYEDIA JASA</span>
                <span class="font-black text-slate-900 text-right">JUANG SOLUSI DIGITAL</span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">LAYANAN &amp; PAKET</span>
                <div class="text-right">
                    <span class="rec-service font-bold text-slate-900 uppercase">{{ isset($order) ? $order->service_name : '-' }}</span>
                    @if(isset($order) && $order->package_name)
                        <span class="rec-pkg block text-[11px] text-slate-500 font-semibold">PAKET: {{ strtoupper($order->package_name) }}</span>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">TEMPLATE DESAIN</span>
                <span class="rec-boilerplate font-bold text-slate-900 text-right uppercase">
                    @php
                        $bpTitle = '-';
                        if (isset($order)) {
                            $bpTitle = $order->boilerplate_name ?? ($order->boilerplate->title ?? ($order->service_name ? 'KUSTOM / SESUAI BRIEF' : '-'));
                        }
                    @endphp
                    {{ $bpTitle }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-4">
                <span class="text-slate-500 shrink-0 uppercase tracking-tight">NAMA PROYEK</span>
                <span class="rec-notes font-bold text-slate-900 text-right uppercase">
                    {{ isset($order) && $order->project_name ? $order->project_name : 'PROYEK DIGITAL JUANGDEV' }}
                </span>
            </div>
        </div>

        <!-- Dotted Separator -->
        <div class="border-t-2 border-dashed border-slate-300 my-2.5"></div>

        <!-- Amount Breakdown Table -->
        <div class="py-1 space-y-1.5 text-xs text-slate-800 font-medium">
            @if(isset($order) && $order->has_discount)
                <div class="flex justify-between items-center text-slate-500">
                    <span class="uppercase tracking-tight">NILAI ASLI (SEBELUM DISKON)</span>
                    <span class="rec-orig-cost font-semibold line-through">
                        {{ $order->formatted_original_amount }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-emerald-700 font-bold">
                    <span class="uppercase tracking-tight">POTONGAN DISKON PROMO</span>
                    <span class="rec-disc-cost">
                        - {{ $order->formatted_discount_amount }}
                    </span>
                </div>
            @endif

            <div class="flex justify-between items-center">
                <span class="text-slate-500 uppercase tracking-tight">{{ isset($order) && $order->has_discount ? 'TOTAL NILAI KONTRAK (SETELAH DISKON)' : 'TOTAL NILAI KONTRAK' }}</span>
                <span class="rec-total-cost font-bold text-slate-900">
                    {{ isset($order) ? $order->formatted_total : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500 uppercase tracking-tight">TAGIHAN UANG MUKA (DP 50%)</span>
                <span class="rec-dp font-semibold text-slate-900">
                    {{ isset($order) ? $order->formatted_dp : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500 uppercase tracking-tight">SISA PELUNASAN (50%)</span>
                <span class="rec-rem font-semibold text-slate-900">
                    {{ isset($order) ? ($order->payment_status === 'fully_paid' ? 'Rp 0 [LUNAS]' : $order->formatted_remaining) : 'Rp 0' }}
                </span>
            </div>

            @if($feeAmount > 0)
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 uppercase tracking-tight">BIAYA LAYANAN / ADMIN GATEWAY</span>
                    <span class="rec-fee font-semibold text-slate-900">
                        {{ $feeFormatted }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Solid Double Separator -->
        <div class="border-t-2 border-b-2 border-slate-900 py-1.5 my-2.5">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-xs font-black text-slate-900 uppercase tracking-tight block">TOTAL PEMBAYARAN</span>
                    <span class="text-[10px] text-slate-500 uppercase">SUDAH TERMASUK BIAYA SISTEM</span>
                </div>
                <span class="rec-total-highlight text-lg sm:text-xl font-black text-[#2563EB] tracking-tight font-mono">
                    {{ $currentPaidAmount }}
                </span>
            </div>
        </div>

        <!-- Authentic Verification Stamp & Legal Disclaimer -->
        <div class="mt-3 pt-3 border-t border-dashed border-slate-300 text-center space-y-1">
            <div class="inline-block border border-emerald-600 text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider mb-1">
                ✓ TERVERIFIKASI SISTEM ELEKTRONIK JUANGDEV
            </div>
            <p class="text-[10px] text-slate-600 font-bold uppercase tracking-wider">
                &copy; {{ date('Y') }} JUANG SOLUSI DIGITAL (JUANGDEV)
            </p>
            <p class="text-[9px] text-slate-500 leading-relaxed font-mono">
                *Bebas biaya maintenance 1 thn sejak serah terima. Perpanjangan maintenance thn berikutnya: Rp 200.000/thn (pure maintenance).
            </p>
            <p class="text-[9px] text-slate-400 leading-relaxed font-mono">
                Bukti transaksi ini diterbitkan secara elektronik dan sah secara hukum perundang-undangan Republik Indonesia.
            </p>
        </div>

    </div>
</div>
