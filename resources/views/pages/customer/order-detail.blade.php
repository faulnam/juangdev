@extends('layouts.app')

@section('title', 'Transaksi #' . $order->invoice_number . ' — JuangDev')
@section('meta_description', 'Detail transaksi, status pembayaran, progres proyek, dan cetak bukti invoice #' . $order->invoice_number)

@section('content')
<div class="min-h-screen bg-[#F8F9FA] text-slate-800 pt-28 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 text-xs sm:text-sm font-semibold space-y-1 shadow-xs">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Top Breadcrumb & Actions Bar (Gambar 5 Style) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Transaksi #{{ $order->invoice_number }}</h1>
                <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mt-1">
                    <a href="{{ route('home') }}" class="hover:text-slate-700 flex items-center gap-1">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i>
                        <span>Beranda</span>
                    </a>
                    <span>/</span>
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-slate-700">Akun Saya</a>
                    <span>/</span>
                    <span class="text-slate-600 font-semibold">Riwayat Transaksi</span>
                </nav>
            </div>

            <!-- Top Actions: Download PDF & Bagikan ke WA -->
            <div class="flex items-center gap-2.5">
                <button 
                    type="button" 
                    onclick="downloadReceiptPdf(this)"
                    class="px-4 py-2 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-xs font-bold shadow-xs flex items-center gap-2 transition-all cursor-pointer"
                >
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    <span>Download Resi PDF</span>
                </button>

                <a 
                    href="https://wa.me/?text={{ urlencode('Halo, berikut adalah bukti transaksi resmi pesanan JuangDev saya #' . $order->invoice_number . ' - ' . url('/invoice/' . $order->invoice_number)) }}" 
                    target="_blank"
                    class="px-4 py-2 rounded-xl bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold shadow-xs flex items-center gap-2 transition-all"
                >
                    <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                    <span>Bagikan ke WA</span>
                </a>
            </div>
        </div>

        <!-- Main 2-Column Layout (Gambar 5 Style) -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 items-start">
            
            <!-- ====================================================
                 LEFT SECTION: Transaction Details Table & Project Box
                 ==================================================== -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm space-y-6">
                
                <!-- Table Details (Gambar 5 Style) -->
                <div class="overflow-hidden rounded-2xl border border-slate-100 divide-y divide-slate-100 text-xs">
                    <div class="flex justify-between items-center p-3.5 bg-slate-50/70">
                        <span class="text-slate-500 font-medium">ID Trx.</span>
                        <span class="font-mono font-bold text-slate-900">{{ $order->invoice_number }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-white">
                        <span class="text-slate-500 font-medium">ID Pel. / Klien</span>
                        <span class="font-bold text-slate-900">{{ $order->customer_name }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-slate-50/70">
                        <span class="text-slate-500 font-medium">Tanggal Transaksi</span>
                        <span class="font-bold text-slate-900">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-white">
                        <span class="text-slate-500 font-medium">Total Nilai Proyek</span>
                        <span class="font-black text-slate-900 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-slate-50/70">
                        <span class="text-slate-500 font-medium">Uang Muka (DP 50%)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                            @if($order->payment_status !== 'unpaid')
                                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded text-[10px]">
                                    LUNAS ✓
                                </span>
                            @else
                                <span class="font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded text-[10px]">
                                    BELUM DIBAYAR
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-white">
                        <span class="text-slate-500 font-medium">Sisa Pelunasan (50%)</span>
                        <div>
                            @if($order->payment_status === 'fully_paid')
                                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded text-[10px]">
                                    Rp 0 (LUNAS ✓)
                                </span>
                            @else
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-slate-50/70">
                        <span class="text-slate-500 font-medium">Metode Pembayaran</span>
                        <span class="font-bold text-slate-900">QRIS / Virtual Account Pakasir</span>
                    </div>

                    <div class="flex justify-between items-center p-3.5 bg-white">
                        <span class="text-slate-500 font-medium">Status Tagihan</span>
                        <div>
                            @if($order->payment_status === 'fully_paid')
                                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[11px]">
                                    Sukses / Lunas 100%
                                </span>
                            @elseif($order->payment_status === 'dp_paid')
                                <span class="font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full text-[11px]">
                                    DP 50% Diterima (Sisa Rp {{ number_format($order->remaining_amount, 0, ',', '.') }})
                                </span>
                            @else
                                <span class="font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full text-[11px]">
                                    Menunggu Pembayaran
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Official Visible E-Receipt Card (Kotak Bukti Resi Langsung) -->
                <div id="receipt-print-area" class="receipt-card bg-white rounded-2xl border-2 border-dashed border-slate-300 p-5 sm:p-6 relative text-slate-800 shadow-sm overflow-hidden space-y-3.5">
                    
                    <!-- Header: Brand Logo & Company Info -->
                    <div class="text-center pb-4 border-b-2 border-slate-200">
                        <div class="inline-flex items-center justify-center gap-2 mb-0.5">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">Juang<span class="text-[#2563EB]">Dev</span></span>
                        </div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">JUANG SOLUSI DIGITAL</p>
                        <p class="text-[9px] text-slate-400 font-medium">Software House &amp; Digital Solution Partner</p>

                        <div class="mt-3 pt-2.5 border-t border-slate-100">
                            <h2 class="rec-title text-base sm:text-lg font-black text-slate-900 uppercase tracking-wide">
                                @if($order->payment_status === 'fully_paid')
                                    Bukti Transaksi Resmi (Lunas 100%)
                                @elseif($order->payment_status === 'dp_paid')
                                    Bukti Pembayaran Uang Muka (DP 50%)
                                @else
                                    Tagihan Transaksi Resmi (Invoice)
                                @endif
                            </h2>
                            <p class="rec-status-subtitle text-[10px] font-semibold text-slate-500 mt-0.5">
                                Bukti Pembayaran Elektronik Resmi JuangDev
                            </p>
                        </div>
                    </div>

                    <!-- Key-Value Transaction Details -->
                    <div class="py-1 space-y-2 text-xs text-slate-700">
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Waktu Transaksi</span>
                            <span class="rec-date font-semibold text-slate-900 text-right">
                                {{ $order->created_at ? $order->created_at->format('Y-m-d H:i:s') . ' WIB' : date('Y-m-d H:i:s') . ' WIB' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Nomor Referensi</span>
                            <span class="rec-inv font-mono font-black text-slate-900 text-right">
                                #{{ $order->invoice_number }}
                            </span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Nama Pelanggan</span>
                            <div class="text-right">
                                <div class="rec-name font-bold text-slate-900 uppercase">{{ $order->customer_name }}</div>
                                <div class="rec-phone font-mono text-[11px] text-slate-500 font-medium">
                                    @php
                                        $phone = $order->customer_phone;
                                        $masked = (strlen($phone) >= 8) ? substr($phone, 0, 4) . ' **** **** ' . substr($phone, -3) : $phone;
                                    @endphp
                                    {{ $masked }}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Jenis Transaksi</span>
                            <span class="rec-trx-type font-bold text-slate-900 text-right">
                                @if($order->payment_status === 'fully_paid')
                                    {{ $order->payment_scheme === 'full_100' ? 'Pelunasan Langsung (100%)' : 'Pelunasan Sisa Proyek (100%)' }}
                                @elseif($order->payment_status === 'dp_paid')
                                    Pembayaran Uang Muka (DP 50%)
                                @else
                                    Tagihan Menunggu Pembayaran
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Metode Pembayaran</span>
                            <span class="rec-method font-semibold text-slate-900 text-right">QRIS / Virtual Account Pakasir</span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Penyedia Jasa</span>
                            <span class="font-bold text-slate-900 text-right">JUANG SOLUSI DIGITAL</span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Layanan &amp; Paket</span>
                            <div class="text-right">
                                <span class="rec-service font-bold text-slate-900">{{ $order->service_name }}</span>
                                @if($order->package_name)
                                    <span class="rec-pkg block text-[11px] text-slate-500">Paket: {{ $order->package_name }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Nama Proyek</span>
                            <span class="rec-notes font-bold text-slate-900 text-right">
                                {{ $order->project_name ?? $order->service_name }}
                            </span>
                        </div>

                        <div class="flex justify-between items-start gap-4">
                            <span class="text-slate-500 font-medium shrink-0">Status Pengerjaan</span>
                            <span class="inline-block text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $order->project_status == 'completed' ? 'bg-blue-100 text-blue-800' : ($order->project_status == 'in_progress' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-200 text-slate-800') }}">
                                {{ $order->project_status == 'completed' ? 'Selesai' : ($order->project_status == 'in_progress' ? 'Dalam Pengerjaan' : 'Antrean Menunggu') }}
                            </span>
                        </div>

                        @if($order->addons && count($order->addons) > 0)
                            <div class="pt-1.5 border-t border-slate-100 flex justify-between items-start gap-4">
                                <span class="text-slate-500 font-medium shrink-0">Fitur Tambahan</span>
                                <div class="text-right flex flex-wrap justify-end gap-1">
                                    @foreach($order->addons as $addon)
                                        <span class="bg-slate-50 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-semibold text-slate-700">
                                            ✓ {{ is_array($addon) ? ($addon['title'] ?? '-') : $addon }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($order->notes)
                            <div class="pt-1.5 border-t border-slate-100 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Catatan Klien:</span>
                                <p class="p-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 text-xs font-medium leading-relaxed">
                                    "{{ $order->notes }}"
                                </p>
                            </div>
                        @endif

                        @if($order->attachment_path)
                            <div class="pt-1.5 border-t border-slate-100 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Lampiran File Proyek:</span>
                                <div class="p-2 rounded-lg bg-slate-50 border border-blue-200 flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <i data-lucide="paperclip" class="w-3.5 h-3.5 text-[#2563EB] shrink-0"></i>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-xs truncate">{{ $order->attachment_name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $order->formatted_attachment_size }}</p>
                                        </div>
                                    </div>
                                    <a 
                                        href="{{ $order->attachment_url }}" 
                                        target="_blank" 
                                        class="px-2.5 py-1 rounded bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-[11px] font-bold flex items-center gap-1 shrink-0 transition-colors shadow-2xs"
                                    >
                                        <i data-lucide="download" class="w-3 h-3"></i>
                                        <span>Buka File</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Dotted Separator -->
                    <div class="border-t-2 border-dashed border-slate-300 my-2.5"></div>

                    <!-- Amount Breakdown Table -->
                    <div class="py-1 space-y-1.5 text-xs text-slate-700">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Total Nilai Kontrak Proyek</span>
                            <span class="rec-total-cost font-bold text-slate-900">
                                {{ $order->formatted_total }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Tagihan Uang Muka (DP 50%)</span>
                            <span class="rec-dp font-semibold text-slate-900">
                                {{ $order->formatted_dp }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Sisa Pelunasan (50%)</span>
                            <span class="rec-rem font-semibold text-slate-900">
                                {{ $order->payment_status === 'fully_paid' ? 'Rp 0 (LUNAS ✓)' : $order->formatted_remaining }}
                            </span>
                        </div>
                    </div>

                    <!-- Solid Separator -->
                    <div class="border-t-2 border-slate-900 my-2.5"></div>

                    <!-- Total Paid Highlight -->
                    <div class="py-1 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-black text-slate-900 uppercase tracking-wider block">Total Tagihan / Pembayaran</span>
                            <span class="text-[10px] text-slate-500">Termasuk Pajak &amp; Biaya Layanan</span>
                        </div>
                        <span class="rec-total-highlight text-xl sm:text-2xl font-black text-[#2563EB] tracking-tight">
                            @if($order->payment_status === 'fully_paid')
                                {{ $order->formatted_total }}
                            @elseif($order->payment_status === 'dp_paid')
                                {{ $order->formatted_dp }}
                            @else
                                {{ $order->formatted_dp }}
                            @endif
                        </span>
                    </div>

                    <!-- Corporate Footer Disclaimer -->
                    <div class="mt-3 pt-3 border-t border-slate-200 text-center space-y-0.5">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                            &copy; {{ date('Y') }} JUANG SOLUSI DIGITAL (JUANGDEV)
                        </p>
                        <p class="text-[9px] text-slate-400 leading-relaxed">
                            Bukti transaksi ini diterbitkan secara elektronik dan sah secara hukum perundang-undangan Republik Indonesia.
                        </p>
                    </div>

                </div>

                <!-- Bottom Actions: Download PDF & Komplain/WhatsApp -->
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                    <button 
                        type="button" 
                        onclick="downloadReceiptPdf(this)"
                        class="w-full sm:w-1/2 py-3 px-4 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs"
                    >
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Download Resi PDF</span>
                    </button>

                    <a 
                        href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6283852174877' }}?text={{ urlencode('Halo Admin JuangDev, saya ingin bertanya / komplain mengenai pesanan #' . $order->invoice_number) }}" 
                        target="_blank"
                        class="w-full sm:w-1/2 py-3 px-4 rounded-xl bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs font-black flex items-center justify-center gap-2 shadow-xs transition-all"
                    >
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span>Komplain / Hubungi CS</span>
                    </a>
                </div>

            </div>

            <!-- ====================================================
                 RIGHT SECTION: QRIS / Pakasir Pay Box & Testimonial
                 ==================================================== -->
            <div class="space-y-6">
                     <!-- 1. Bayar dengan QRIS / Pakasir Card (Gambar 5 Style) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4" x-data="{
                    isChecking: false,
                    checkPaymentStatus() {
                        this.isChecking = true;
                        fetch('{{ route('orders.check-status', $order->invoice_number) }}')
                            .then(res => res.json())
                            .then(data => {
                                this.isChecking = false;
                                if (data.paid) {
                                    alert('Pembayaran terkonfirmasi berhasil!');
                                    window.location.reload();
                                } else {
                                    alert('Pembayaran belum terdeteksi. Silakan selesaikan pembayaran melalui QRIS / Pakasir di bawah ini.');
                                }
                            })
                            .catch(e => {
                                this.isChecking = false;
                                alert('Gagal memeriksa status pembayaran.');
                            });
                    }
                }">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-[#2563EB]"></i>
                            <h3 class="text-sm font-bold text-slate-900">Pembayaran QRIS / Pakasir</h3>
                        </div>
                        <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" class="h-4 w-auto">
                    </div>

                    @if($order->payment_status !== 'fully_paid')
                        <!-- QRIS Live Graphic Generated Directly from Pakasir -->
                        <div class="rounded-2xl border-2 border-slate-100 p-4 bg-slate-50/60 text-center space-y-3">
                            <div class="w-48 h-48 mx-auto bg-white rounded-2xl p-2.5 border border-slate-200 shadow-sm flex items-center justify-center">
                                <img 
                                    src="{{ $pakasirData['qr_image_url'] ?? ('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($pakasirData['payment_url'] ?? route('invoice.show', $order->invoice_number))) }}" 
                                    alt="QRIS Standar Pembayaran Pakasir" 
                                    class="w-full h-full object-contain rounded-lg"
                                >
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-700 uppercase tracking-wider">Pindai QRIS untuk Bayar Instan</p>
                                <p class="text-[10px] text-slate-500 font-medium">BCA Mobile, Livin Mandiri, BRImo, BNI, GoPay, OVO, ShopeePay, DANA</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status Notification Banner (Gambar 5 Style) -->
                    <div class="p-3.5 rounded-xl border text-xs font-bold {{ $order->payment_status === 'fully_paid' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : ($order->payment_status === 'dp_paid' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-blue-50 border-blue-200 text-blue-900') }}">
                        @if($order->payment_status === 'fully_paid')
                            <p class="flex items-center gap-1.5">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                <span>Pembayaran berhasil diselesaikan (LUNAS 100%).</span>
                            </p>
                        @elseif($order->payment_status === 'dp_paid')
                            <p class="flex items-center gap-1.5">
                                <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                                <span>Pembayaran DP 50% berhasil. Sisa pelunasan Rp {{ number_format($order->remaining_amount, 0, ',', '.') }} dapat dilunasi melalui pemindaian QRIS di atas.</span>
                            </p>
                        @else
                            <p class="flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                <span>Silakan scan QRIS di atas untuk konfirmasi pembayaran instan 24 jam otomatis.</span>
                            </p>
                        @endif
                    </div>

                    <!-- Payment Action Buttons (No External Redirect) -->
                    @if($order->payment_status !== 'fully_paid')
                        <div>
                            <button 
                                type="button" 
                                @click="checkPaymentStatus()" 
                                :disabled="isChecking"
                                class="w-full py-3 px-4 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-xs font-black shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="refresh-cw" :class="isChecking ? 'animate-spin' : ''" class="w-4 h-4"></i>
                                <span x-text="isChecking ? 'Memeriksa Pembayaran...' : 'Cek Status Pembayaran Real-Time'"></span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>

                <!-- 2. Testimonial Card (Gambar 5 Style) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="message-square" class="w-4 h-4 text-[#06B6D4]"></i>
                        <h3 class="text-sm font-bold text-slate-900">Testimonial</h3>
                    </div>

                    @if($testimonial)
                        <div class="bg-cyan-50/50 border border-cyan-200/80 rounded-2xl p-4 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-cyan-900">Testimonial Anda:</span>
                                <div class="flex text-amber-400 text-xs">
                                    @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                                        ★
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-700 italic">"{{ $testimonial->content }}"</p>
                            <p class="text-[10px] text-slate-400 font-medium">Terima kasih atas ulasan yang Anda berikan!</p>
                        </div>
                    @else
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Kamu belum mengirimkan testimonial untuk transaksi ini. Berikan ulasan Anda di bawah ini:
                        </p>

                        <form action="{{ route('customer.orders.testimonial', $order->invoice_number) }}" method="POST" class="space-y-3">
                            @csrf

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Rating Kepuasan</label>
                                <select 
                                    name="rating" 
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:border-black cursor-pointer"
                                >
                                    <option value="5">★★★★★ Sangat Puas (5 Bintang)</option>
                                    <option value="4">★★★★☆ Puas (4 Bintang)</option>
                                    <option value="3">★★★☆☆ Cukup (3 Bintang)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Pesan</label>
                                <textarea 
                                    name="content" 
                                    rows="3" 
                                    required 
                                    placeholder="Tulis ulasan pengalaman Anda menggunakan layanan JuangDev..."
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all resize-none"
                                ></textarea>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl bg-[#06B6D4] hover:bg-[#0891b2] text-white text-xs font-black shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2"
                            >
                                <span>Kirim Testimonial</span>
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    @endif
                </div>

            </div>

        </div>

    </div>
</div>


<!-- HTML2PDF Library for Direct 1-Page PDF Downloads -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function formatRupiah(num) {
    if (!num && num !== 0) return 'Rp 0';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function downloadReceiptPdf(btn) {
    var receiptEl = document.getElementById('receipt-print-area');
    if (!receiptEl) {
        printThermalReceipt();
        return;
    }

    var originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-1">⏳</span> Menyiapkan PDF...';
    }

    // Create a perfectly formatted single-page receipt container
    var clone = receiptEl.cloneNode(true);
    clone.style.width = '480px';
    clone.style.maxWidth = '480px';
    clone.style.padding = '18px 22px';
    clone.style.margin = '0 auto';
    clone.style.background = '#ffffff';
    clone.style.border = '2px solid #cbd5e1';
    clone.style.borderRadius = '14px';
    clone.style.boxSizing = 'border-box';
    clone.style.fontSize = '11.5px';
    clone.style.lineHeight = '1.35';
    clone.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif';
    clone.style.color = '#0f172a';

    var container = document.createElement('div');
    container.style.position = 'fixed';
    container.style.left = '-9999px';
    container.style.top = '0';
    container.style.width = '490px';
    container.style.background = '#ffffff';
    container.appendChild(clone);
    document.body.appendChild(container);

    var invNum = '{{ $order->invoice_number }}';
    var opt = {
        margin: [6, 6, 6, 6],
        filename: 'Resi-JuangDev-' + invNum + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2, 
            useCORS: true, 
            logging: false,
            letterRendering: true,
            scrollX: 0,
            scrollY: 0
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'portrait' 
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    if (typeof html2pdf !== 'undefined') {
        html2pdf().set(opt).from(clone).save().then(function() {
            if (document.body.contains(container)) {
                document.body.removeChild(container);
            }
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }).catch(function(err) {
            console.error('PDF generation error:', err);
            if (document.body.contains(container)) {
                document.body.removeChild(container);
            }
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
            printThermalReceipt();
        });
    } else {
        if (document.body.contains(container)) {
            document.body.removeChild(container);
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        printThermalReceipt();
    }
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
        '@page { size: A4 portrait; margin: 8mm 10mm; }',
        '* { box-sizing: border-box; margin: 0; padding: 0; }',
        'html, body {',
        '  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;',
        '  background: #ffffff; color: #0f172a;',
        '  padding: 0; margin: 0; display: flex; justify-content: center; align-items: flex-start;',
        '  min-height: 100%;',
        '}',
        '.receipt-container { width: 100%; max-width: 480px; margin: 0 auto; page-break-inside: avoid; page-break-after: avoid; page-break-before: avoid; }',
        '.receipt-card {',
        '  background: #ffffff; border-radius: 12px;',
        '  padding: 18px 22px;',
        '  border: 1.5px solid #cbd5e1;',
        '  position: relative;',
        '  page-break-inside: avoid; page-break-after: avoid; page-break-before: avoid;',
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
        '.text-xs { font-size: 11.5px; }',
        '.text-sm { font-size: 13px; }',
        '.text-base { font-size: 15px; }',
        '.text-lg { font-size: 16px; }',
        '.text-2xl { font-size: 20px; }',
        '.text-slate-900 { color: #0f172a; }',
        '.text-slate-800 { color: #1e293b; }',
        '.text-slate-700 { color: #334155; }',
        '.text-slate-500 { color: #64748b; }',
        '.text-slate-400 { color: #94a3b8; }',
        '.text-blue { color: #2563EB; }',
        '.text-right { text-align: right; }',
        '.border-b { border-bottom: 1px solid #e2e8f0; }',
        '.border-b-2 { border-bottom: 1.5px solid #cbd5e1; }',
        '.border-t { border-top: 1px solid #e2e8f0; }',
        '.border-t-2 { border-top: 1.5px solid #0f172a; }',
        '.py-1 { padding-top: 4px; padding-bottom: 4px; }',
        '.py-2 { padding-top: 6px; padding-bottom: 6px; }',
        '.pb-4 { padding-bottom: 12px; }',
        '.mt-3 { margin-top: 10px; }',
        '.pt-2 { padding-top: 8px; }',
        '.pt-3 { padding-top: 10px; }',
        '.space-y-1\\.5 > * + * { margin-top: 6px; }',
        '.space-y-2 > * + * { margin-top: 7px; }',
        '.my-2\\.5 { margin-top: 10px; margin-bottom: 10px; }',
        '.rec-total-highlight { color: #2563EB; font-weight: 900; font-size: 20px; }',
        '@media print {',
        '  body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }',
        '  .receipt-card { box-shadow: none !important; border: 1.5px solid #0f172a !important; border-radius: 8px !important; }',
        '}'
    ].join('\n');

    printWin.document.write('<!DOCTYPE html><html><head><title>Bukti Transaksi Resmi - JuangDev</title><meta charset="utf-8"><style>' + css + '</style></head><body><div class="receipt-container">' + clone.innerHTML + '</div><scr' + 'ipt>setTimeout(function(){window.print();},400);</scr' + 'ipt></body></html>');
    printWin.document.close();
}
</script>
@endsection
