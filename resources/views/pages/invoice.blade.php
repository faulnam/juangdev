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
                            onclick="window.print()" 
                            class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-3.5 py-1.5 rounded-full transition-all print:hidden"
                        >
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak Resi</span>
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

<!-- Thermal Paper Receipt Print Area (Visible ONLY when printing) -->
<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #receipt-print-area, #receipt-print-area * {
        visibility: visible !important;
    }
    #receipt-print-area {
        position: absolute !important;
        left: 50% !important;
        top: 20px !important;
        transform: translateX(-50%) !important;
        width: 100% !important;
        max-width: 360px !important;
        display: block !important;
        background: #ffffff !important;
        color: #000000 !important;
        border: 1px dashed #000000 !important;
        font-family: monospace !important;
    }
}
</style>

<div id="receipt-print-area" class="hidden print:block font-mono text-black text-xs p-6 bg-white max-w-sm mx-auto border border-dashed border-slate-300">
    <div class="text-center space-y-1 mb-4">
        <h2 class="text-base font-black tracking-widest uppercase">JUANGDEV</h2>
        <p class="text-[10px]">Digital Solutions &amp; Software House</p>
        <p class="text-[9px]">WhatsApp: +62 859-1716-81988 | halo@juangdev.com</p>
    </div>

    <p class="text-center text-[10px] my-2">**************************************************</p>
    <p class="text-center font-bold uppercase tracking-wider text-xs">OFFICIAL ORDER RECEIPT</p>
    <p class="text-center text-[10px] my-2">**************************************************</p>

    <div class="space-y-1 text-[11px] mb-3">
        <div class="flex justify-between"><span>No. Invoice:</span><span class="font-bold">{{ $order->invoice_number }}</span></div>
        <div class="flex justify-between"><span>Tanggal:</span><span>{{ $order->created_at->format('d M Y H:i') }} WIB</span></div>
        <div class="flex justify-between"><span>Klien:</span><span class="font-bold">{{ $order->customer_name }}</span></div>
        <div class="flex justify-between"><span>No. WA:</span><span>{{ $order->customer_phone }}</span></div>
        <div class="flex justify-between"><span>Proyek:</span><span>{{ $order->project_name ?? '-' }}</span></div>
    </div>

    <p class="text-center text-[10px] my-2">--------------------------------------------------</p>

    <div class="space-y-1.5 text-[11px] mb-3">
        <div class="flex justify-between font-bold">
            <span>Deskripsi Layanan</span>
            <span>Biaya</span>
        </div>
        <div class="flex justify-between">
            <span>{{ $order->service_name }}</span>
            <span>{{ $order->formatted_total }}</span>
        </div>
        @if($order->package_name)
            <div class="flex justify-between text-[10px] text-slate-600 pl-2">
                <span>Paket: {{ $order->package_name }}</span>
            </div>
        @endif
    </div>

    <p class="text-center text-[10px] my-2">==================================================</p>

    <div class="space-y-1 text-[11px] mb-4">
        <div class="flex justify-between font-bold text-xs">
            <span>Total Investasi Proyek</span>
            <span>{{ $order->formatted_total }}</span>
        </div>
        <div class="flex justify-between text-blue-600 font-bold">
            <span>Tagihan DP (50%)</span>
            <span>{{ $order->formatted_dp }}</span>
        </div>
        <div class="flex justify-between text-slate-600">
            <span>Sisa Pelunasan (50%)</span>
            <span>{{ $order->formatted_remaining }}</span>
        </div>
        <div class="flex justify-between pt-1 border-t border-dotted border-slate-300">
            <span>Status Pembayaran:</span>
            <span class="font-bold uppercase text-amber-600">
                @if($order->payment_status === 'fully_paid') LUNAS 100%
                @elseif($order->payment_status === 'dp_paid') DP 50% LUNAS
                @else MENUNGGU PEMBAYARAN @endif
            </span>
        </div>
    </div>

    <p class="text-center text-[10px] my-2">**************************************************</p>
    
    <div class="text-center space-y-2 mt-4">
        <p class="font-bold tracking-widest text-xs">THANK YOU FOR YOUR BUSINESS!</p>
        <div class="inline-block px-4 py-2 border border-black font-mono text-[10px] tracking-widest">
            |||||||||||||||||||||||||||||||||||||||||||||||
        </div>
        <p class="text-[9px] text-slate-500">JUANGDEV - YOUR DIGITAL GROWTH PARTNER</p>
    </div>
</div>
@endsection
