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

            <!-- Top Actions: Cetak & Bagikan ke WA (Gambar 5 Style) -->
            <div class="flex items-center gap-2.5">
                <button 
                    type="button" 
                    onclick="printThermalReceipt()"
                    class="px-4 py-2 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-xs font-bold shadow-xs flex items-center gap-2 transition-all cursor-pointer"
                >
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak Bukti Resi</span>
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

                <!-- Project Specifications Dotted Box (Gambar 5 Token Box Style) -->
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-5 sm:p-6 text-center space-y-3 bg-slate-50/50">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">INFORMASI SPESIFIKASI PROYEK</p>
                    
                    <div class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        {{ $order->project_name ?? $order->service_name }}
                    </div>

                    <div class="text-xs text-slate-600 font-medium space-y-1">
                        <p class="font-bold text-slate-800">{{ $order->service_name }} — Paket {{ $order->package_name ?? 'Kustom' }}</p>
                        <p class="text-slate-500">Klien: {{ $order->customer_name }} • WhatsApp: {{ $order->customer_phone }}</p>
                        
                        @if($order->addons && count($order->addons) > 0)
                            <div class="pt-2 flex flex-wrap justify-center gap-1.5">
                                @foreach($order->addons as $addon)
                                    <span class="bg-white border border-slate-200 px-2.5 py-0.5 rounded-md text-[10px] font-semibold text-slate-700">
                                        ✓ {{ is_array($addon) ? ($addon['title'] ?? '-') : $addon }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="pt-2">
                            <span class="inline-block text-[11px] font-bold px-3 py-1 rounded-full {{ $order->project_status == 'completed' ? 'bg-blue-100 text-blue-800' : ($order->project_status == 'in_progress' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-200 text-slate-800') }}">
                                Status Proyek: {{ $order->project_status == 'completed' ? 'Selesai' : ($order->project_status == 'in_progress' ? 'Dalam Pengerjaan' : 'Antrean Menunggu') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions: Print & Komplain/WhatsApp (Gambar 5 Style) -->
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                    <button 
                        type="button" 
                        onclick="printThermalReceipt()"
                        class="w-full sm:w-1/2 py-3 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-800 text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer"
                    >
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>Cetak Bukti Resi</span>
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
                                <span>Pembayaran DP 50% berhasil. Sisa pelunasan Rp {{ number_format($order->remaining_amount, 0, ',', '.') }} dapat dilunasi melalui QR di atas atau tombol di bawah.</span>
                            </p>
                        @else
                            <p class="flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                <span>Silakan scan QRIS di atas atau bayar via Pakasir untuk konfirmasi otomatis.</span>
                            </p>
                        @endif
                    </div>

                    <!-- Payment Action Buttons -->
                    @if($order->payment_status !== 'fully_paid')
                        <div class="space-y-2">
                            <a 
                                href="{{ $pakasirData['payment_url'] ?? '#' }}" 
                                target="_blank"
                                class="w-full py-3.5 px-4 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-xs font-black shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer text-center"
                            >
                                <span>Buka Gateway Pembayaran Pakasir</span>
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>

                            <button 
                                type="button" 
                                @click="checkPaymentStatus()" 
                                :disabled="isChecking"
                                class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="refresh-cw" :class="isChecking ? 'animate-spin' : ''" class="w-3.5 h-3.5"></i>
                                <span x-text="isChecking ? 'Memeriksa Pembayaran...' : 'Cek Status Pembayaran Real-Time'"></span>
                            </button>
                        </div>
                    @endif
                </div>      </div>

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
