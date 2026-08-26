@extends('layouts.app')

@section('title', 'Invoice #' . $order->invoice_number . ' — JuangDev')
@section('meta_robots', 'noindex, nofollow')
@section('meta_description', 'Detail tagihan resmi transaksi pemesanan layanan di JuangDev.')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '62859171681988';
    $whatsappMsg = urlencode("Halo Tim JuangDev, saya ingin mengonfirmasi tagihan invoice #" . $order->invoice_number);
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

@section('content')
<section class="pt-32 pb-20 md:pt-40 md:pb-28 bg-[#f8f9fc]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="mb-8 p-6 rounded-2xl bg-emerald-50 border-2 border-emerald-500 text-emerald-900 flex items-center gap-4 shadow-lg shadow-emerald-500/10">
                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="check" class="w-6 h-6 stroke-[3]"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg">Status Pembayaran Diperbarui</h4>
                    <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Invoice Paper Card -->
        <div class="bg-white rounded-[2.5rem] border-2 border-slate-200 shadow-2xl overflow-hidden">
            
            <!-- Header Banner -->
            <div class="bg-[#0A1E5E] text-white p-8 sm:p-12 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-blue-600/30 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl font-serif font-black tracking-tight text-white">Juang<span class="text-[#C7F236]">Dev</span></span>
                            <span class="bg-white/10 text-white/90 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">Official Invoice</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            #{{ $order->invoice_number }}
                        </h1>
                        <p class="text-white/70 text-xs font-medium mt-1">
                            Diterbitkan pada {{ $order->created_at ? $order->created_at->format('d F Y, H:i') : '-' }} WIB
                        </p>
                    </div>

                    <!-- Payment Status Badge & Print Action -->
                    <div class="text-left md:text-right flex flex-col md:items-end gap-3">
                        <div>
                            <span class="text-xs font-bold text-white/70 uppercase tracking-wider block mb-1">Status Tagihan</span>
                            @if($order->payment_status === 'fully_paid')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-4 py-2 rounded-full font-black text-sm uppercase tracking-wide shadow-lg shadow-emerald-500/30">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> LUNAS 100%
                                </span>
                            @elseif($order->payment_status === 'dp_paid')
                                <span class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-4 py-2 rounded-full font-black text-sm uppercase tracking-wide shadow-lg shadow-amber-500/30">
                                    <i data-lucide="clock" class="w-4 h-4"></i> DP 50% LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-rose-500 text-white px-4 py-2 rounded-full font-black text-sm uppercase tracking-wide shadow-lg shadow-rose-500/30">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i> MENUNGGU PEMBAYARAN
                                </span>
                            @endif
                        </div>

                        <button 
                            type="button" 
                            onclick="printThermalReceipt()" 
                            class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-3.5 py-1.5 rounded-full transition-all print:hidden cursor-pointer"
                        >
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak Resi Formal</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Body Details -->
            <div class="p-8 sm:p-12 space-y-10">
                
                <!-- Client & Project Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-8 border-b border-slate-100">
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-3">Informasi Pemesan / Klien</h4>
                        <div class="space-y-1 text-slate-800 text-sm font-semibold">
                            <p class="text-base font-black text-slate-900">{{ $order->customer_name }}</p>
                            <p class="text-slate-600 font-normal">{{ $order->customer_email }}</p>
                            <p class="text-slate-600 font-normal">{{ $order->customer_phone }}</p>
                            @if($order->project_name)
                                <p class="text-[#2563EB] font-bold mt-2">Proyek: {{ $order->project_name }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-3">Status Pengerjaan Proyek</h4>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Status Pengerjaan:</span>
                                <span class="font-bold uppercase text-slate-900 bg-slate-200 px-2.5 py-0.5 rounded">
                                    {{ str_replace('_', ' ', $order->project_status) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Skema Pembayaran:</span>
                                <span class="font-bold text-[#2563EB]">
                                    {{ $order->payment_scheme === 'full_100' ? 'Pelunasan Langsung (100%)' : 'Uang Muka (DP 50%)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Item Breakdown Table -->
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">Rincian Layanan &amp; Biaya</h4>
                    <div class="border-2 border-slate-100 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3.5 px-6">Item Layanan</th>
                                    <th class="py-3.5 px-6 text-right">Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                <tr>
                                    <td class="py-4 px-6">
                                        <p class="font-bold text-slate-900">{{ $order->service_name }}</p>
                                        @if($order->package_name)
                                            <p class="text-xs text-slate-500 mt-0.5">Paket: {{ $order->package_name }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-slate-900">
                                        {{ $order->formatted_total }}
                                    </td>
                                </tr>

                                @if(!empty($order->addons))
                                    @foreach($order->addons as $addon)
                                        <tr>
                                            <td class="py-3 px-6 text-slate-600">
                                                Fitur Tambahan: {{ is_array($addon) ? ($addon['title'] ?? '-') : $addon }}
                                            </td>
                                            <td class="py-3 px-6 text-right text-slate-600 font-medium">
                                                Termaasuk
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot class="bg-slate-50 border-t-2 border-slate-100 font-bold text-slate-900">
                                <tr>
                                    <td class="py-3.5 px-6 text-right uppercase text-xs text-slate-500">Total Investasi Proyek:</td>
                                    <td class="py-3.5 px-6 text-right text-base text-slate-900 font-black">{{ $order->formatted_total }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3.5 px-6 text-right uppercase text-xs text-slate-500">Tagihan DP 50%:</td>
                                    <td class="py-3.5 px-6 text-right text-base text-[#2563EB] font-black">{{ $order->formatted_dp }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3.5 px-6 text-right uppercase text-xs text-slate-500">Sisa Pelunasan (50%):</td>
                                    <td class="py-3.5 px-6 text-right text-base text-slate-700 font-black">
                                        {{ $order->payment_status === 'fully_paid' ? 'Rp 0 (LUNAS)' : $order->formatted_remaining }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Payment Action Box (Pakasir Payment Gateway Integration) -->
                <div class="bg-gradient-to-br from-[#0A1E5E] to-[#122d78] text-white rounded-3xl p-8 sm:p-10 shadow-xl relative overflow-hidden">
                    <div class="relative z-10 space-y-6">
                        
                        @if($order->payment_status === 'unpaid')
                            <div>
                                <span class="bg-[#C7F236] text-[#0A1E5E] text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full mb-3 inline-block">
                                    Langkah 1: Pembayaran Uang Muka (DP 50%)
                                </span>
                                <h3 class="text-2xl font-black text-white">
                                    Bayar DP 50% sebesar {{ $order->formatted_dp }}
                                </h3>
                                <p class="text-white/80 text-xs mt-1 font-medium leading-relaxed max-w-xl">
                                    Pembayaran dapat dilakukan secara instan via Pakasir Payment Gateway (QRIS BCA/GoPay/OVO/ShopeePay &amp; Virtual Account Bank).
                                </p>
                            </div>

                            <form action="{{ route('invoice.pay', $order->invoice_number) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="dp">
                                <button 
                                    type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-[#C7F236] hover:bg-[#b5dd2a] text-[#0A1E5E] font-black text-base transition-all shadow-xl shadow-[#C7F236]/20"
                                >
                                    <span>Bayar DP 50% Instant via Pakasir</span>
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                </button>
                            </form>

                        @elseif($order->payment_status === 'dp_paid')
                            <div>
                                <span class="bg-amber-400 text-slate-900 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full mb-3 inline-block">
                                    Langkah 2: Pelunasan Sisa 50%
                                </span>
                                <h3 class="text-2xl font-black text-white">
                                    Bayar Sisa Pelunasan sebesar {{ $order->formatted_remaining }}
                                </h3>
                                <p class="text-white/80 text-xs mt-1 font-medium leading-relaxed max-w-xl">
                                    Proyek Anda sedang dalam pengerjaan atau telah selesai. Silakan lakukan pelunasan sisa 50% untuk serah terima proyek secara utuh.
                                </p>
                            </div>

                            <form action="{{ route('invoice.pay', $order->invoice_number) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="remaining">
                                <button 
                                    type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-[#C7F236] hover:bg-[#b5dd2a] text-[#0A1E5E] font-black text-base transition-all shadow-xl shadow-[#C7F236]/20"
                                >
                                    <span>Bayar Pelunasan 50% via Pakasir</span>
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                </button>
                            </form>

                        @else
                            <div>
                                <span class="bg-emerald-400 text-slate-900 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full mb-3 inline-block">
                                    Tagihan Lunas Sepenuhnya
                                </span>
                                <h3 class="text-2xl font-black text-white">
                                    Terima Kasih, Tagihan Ini Telah LUNAS 100%
                                </h3>
                                <p class="text-white/80 text-xs mt-1 font-medium leading-relaxed max-w-xl">
                                    Pembayaran penuh telah diverifikasi. Tim teknis JuangDev akan menyerahkan seluruh berkas &amp; kredensial proyek kepada Anda.
                                </p>
                            </div>

                            <a 
                                href="{{ $whatsappUrl }}" 
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white text-[#0A1E5E] font-bold text-sm hover:bg-slate-100 transition-all shadow-md"
                            >
                                <span>Hubungi Tim Serah Terima Proyek</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                            </a>
                        @endif

                    </div>
                </div>

                <!-- Footer Notes -->
                <div class="text-center text-xs text-slate-400 font-medium space-y-1 print:hidden">
                    <p>Sistem Invoice &amp; Pembayaran Resmi JuangDev Digital Solutions</p>
                    <p>Apabila ada pertanyaan mengenai invoice ini, silakan hubungi tim kami via WhatsApp +62 859-1716-81988.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Formal E-Receipt Component -->
@include('partials.receipt-modal')

<script>
function formatRupiah(num) {
    if (!num && num !== 0) return 'Rp 0';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function printThermalReceipt() {
    var receiptEl = document.getElementById('receipt-print-area');
    if (!receiptEl) {
        window.print();
        return;
    }

    var clone = receiptEl.cloneNode(true);
    clone.classList.remove('hidden', 'print:block');
    clone.style.display = 'block';

    var printWin = window.open('', '_blank', 'width=800,height=950');
    if (!printWin) {
        window.print();
        return;
    }

    var css = [
        '@page { size: A4 portrait; margin: 20mm 15mm; }',
        '* { box-sizing: border-box; margin: 0; padding: 0; }',
        'body {',
        '  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;',
        '  background: #f8fafc; color: #0f172a;',
        '  padding: 40px 20px; display: flex; justify-content: center; align-items: flex-start;',
        '  min-height: 100vh;',
        '}',
        '.receipt-container { width: 100%; max-width: 520px; margin: 0 auto; }',
        '.receipt-card {',
        '  background: #ffffff; border-radius: 16px;',
        '  padding: 36px 32px;',
        '  border: 2px solid #cbd5e1;',
        '  box-shadow: 0 10px 25px rgba(0,0,0,0.06);',
        '  position: relative;',
        '}',
        '.text-center { text-align: center; }',
        '.flex { display: flex; justify-content: space-between; align-items: flex-start; }',
        '.items-center { align-items: center; }',
        '.font-bold { font-weight: 700; }',
        '.font-black { font-weight: 900; }',
        '.font-semibold { font-weight: 600; }',
        '.font-medium { font-weight: 500; }',
        '.font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }',
        '.uppercase { text-transform: uppercase; }',
        '.text-xs { font-size: 13px; }',
        '.text-sm { font-size: 14px; }',
        '.text-base { font-size: 16px; }',
        '.text-lg { font-size: 18px; }',
        '.text-2xl { font-size: 24px; }',
        '.text-slate-900 { color: #0f172a; }',
        '.text-slate-800 { color: #1e293b; }',
        '.text-slate-700 { color: #334155; }',
        '.text-slate-500 { color: #64748b; }',
        '.text-slate-400 { color: #94a3b8; }',
        '.text-blue { color: #2563EB; }',
        '.text-right { text-align: right; }',
        '.border-b { border-bottom: 1px solid #e2e8f0; }',
        '.border-b-2 { border-bottom: 2px solid #cbd5e1; }',
        '.border-t { border-top: 1px solid #e2e8f0; }',
        '.border-t-2 { border-top: 2px solid #0f172a; }',
        '.py-2 { padding-top: 8px; padding-bottom: 8px; }',
        '.py-4 { padding-top: 16px; padding-bottom: 16px; }',
        '.pb-5 { padding-bottom: 20px; }',
        '.mt-4 { margin-top: 16px; }',
        '.mt-5 { margin-top: 20px; }',
        '.pt-3 { padding-top: 12px; }',
        '.pt-4 { padding-top: 16px; }',
        '.space-y-2 > * + * { margin-top: 8px; }',
        '.space-y-2\\.5 > * + * { margin-top: 10px; }',
        '.my-3 { margin-top: 14px; margin-bottom: 14px; }',
        '.rec-total-highlight { color: #2563EB; font-weight: 900; font-size: 26px; }',
        '@media print {',
        '  body { background: #ffffff !important; padding: 0 !important; }',
        '  .receipt-card { box-shadow: none !important; border: 1.5px solid #0f172a !important; border-radius: 8px !important; }',
        '}'
    ].join('\n');

    printWin.document.write('<!DOCTYPE html><html><head><title>Bukti Transaksi Resmi - JuangDev</title><meta charset="utf-8"><style>' + css + '</style></head><body><div class="receipt-container">' + clone.innerHTML + '</div><scr' + 'ipt>setTimeout(function(){window.print();},400);</scr' + 'ipt></body></html>');
    printWin.document.close();
}
</script>
@endsection
