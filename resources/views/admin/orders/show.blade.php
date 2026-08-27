@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
@php
    $clientPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
    if (str_starts_with($clientPhone, '0')) {
        $clientPhone = '62' . substr($clientPhone, 1);
    }
    
    $statusText = match($order->project_status) {
        'in_progress' => 'sedang dalam proses pengerjaan (development & setup sistem)',
        'completed' => 'telah SELESAI dan siap untuk tahap serah terima (handover)',
        'pending' => 'telah masuk ke antrean pengerjaan tim JuangDev',
        default => 'sedang dalam peninjauan tim teknis'
    };

    $waUpdateText = "Halo Kak {$order->customer_name},\n\nKami dari tim *JuangDev* ingin menginformasikan update status pengerjaan proyek Anda (*{$order->project_name}* - #{$order->invoice_number}).\n\nStatus saat ini: Proyek Anda *{$statusText}*.\n\nDetail spesifikasi & resi transaksi dapat dipantau langsung di:\n" . route('invoice.show', $order->invoice_number) . "\n\nJika ada kebutuhan atau pertanyaan tambahan, silakan balas pesan ini. Terima kasih! 🙏\n— JuangDev Team";
    $waUpdateUrl = "https://wa.me/{$clientPhone}?text=" . urlencode($waUpdateText);
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#2563EB] hover:underline inline-flex items-center gap-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900">Detail Pesanan #{{ $order->invoice_number }}</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ $waUpdateUrl }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1ebc59] text-white font-bold text-xs flex items-center gap-2 shadow-xs transition-all">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Kirim Update Progress WA</span>
            </a>

            <form action="{{ route('admin.orders.send-wa', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 shadow-xs">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Tagihan Formal WA</span>
                </button>
            </form>

            <button 
                type="button" 
                onclick="downloadReceiptPdf(this)" 
                class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-2 shadow-xs transition-all cursor-pointer"
            >
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Download Resi PDF</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left 2 Cols: Info & Line Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Informasi Pemesan &amp; Proyek</h3>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">Nama Klien</p>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $order->customer_name }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">Nomor WhatsApp</p>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $order->customer_phone }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">Alamat Email</p>
                        <p class="font-medium text-slate-800 mt-0.5">{{ $order->customer_email }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">Nama Proyek</p>
                        <p class="font-medium text-[#2563EB] mt-0.5">{{ $order->project_name ?? '-' }}</p>
                    </div>
                </div>

                @if($order->notes)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-slate-400 font-bold uppercase text-[10px]">Catatan / Brief Klien</p>
                        <p class="text-slate-700 text-xs mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100 font-medium">
                            "{{ $order->notes }}"
                        </p>
                    </div>
                @endif

                @if($order->attachment_path)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-slate-400 font-bold uppercase text-[10px] mb-2">Berkas Lampiran dari Klien</p>
                        <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-[#2563EB] text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-xs truncate">{{ $order->attachment_name ?? 'Berkas Lampiran' }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $order->formatted_attachment_size ?? 'Dokumen' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a 
                                    href="{{ $order->attachment_url }}" 
                                    target="_blank" 
                                    class="px-3 py-1.5 rounded-lg bg-white hover:bg-blue-50 text-[#2563EB] border border-blue-200 text-xs font-bold flex items-center gap-1.5 shadow-2xs transition-all"
                                >
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>Buka / Unduh</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Financial Breakdown Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Rincian Pembayaran &amp; Biaya</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Layanan Utama:</span>
                        <span class="font-bold text-slate-900">{{ $order->service_name }}</span>
                    </div>

                    @if($order->package_name)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Paket Pilihan:</span>
                            <span class="font-bold text-slate-900">{{ $order->package_name }}</span>
                        </div>
                    @endif

                    <hr class="border-slate-100">

                    @if($order->has_discount)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Harga Normal (Sebelum Diskon):</span>
                            <span class="font-semibold text-slate-400 line-through">{{ $order->formatted_original_amount }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs text-rose-600 font-bold">
                            <span>Potongan Diskon Promo:</span>
                            <span>- {{ $order->formatted_discount_amount }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-sm">
                        <span class="font-bold text-slate-700">{{ $order->has_discount ? 'Total Investasi (Setelah Diskon):' : 'Total Investasi Proyek:' }}</span>
                        <span class="font-black text-slate-900 text-base">{{ $order->formatted_total }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-[#2563EB]">
                        <span class="font-bold">Tagihan Uang Muka (DP 50%):</span>
                        <span class="font-black">{{ $order->formatted_dp }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span class="font-bold">Sisa Pelunasan (50%):</span>
                        <span class="font-black">{{ $order->formatted_remaining }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Col: Update Status Form -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Update Status Pesanan</h3>

            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Pembayaran Pakasir</label>
                    <select name="payment_status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-[#2563EB]">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Dibayar (UNPAID)</option>
                        <option value="dp_paid" {{ $order->payment_status === 'dp_paid' ? 'selected' : '' }}>DP 50% Lunas (DP PAID)</option>
                        <option value="fully_paid" {{ $order->payment_status === 'fully_paid' ? 'selected' : '' }}>Lunas 100% (FULLY PAID)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Pengerjaan Proyek</label>
                    <select name="project_status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-[#2563EB]">
                        <option value="pending" {{ $order->project_status === 'pending' ? 'selected' : '' }}>Pending / Menunggu Pembayaran</option>
                        <option value="in_progress" {{ $order->project_status === 'in_progress' ? 'selected' : '' }}>Dalam Pengerjaan (In Progress)</option>
                        <option value="completed" {{ $order->project_status === 'completed' ? 'selected' : '' }}>Selesai / Ready for Handover</option>
                        <option value="cancelled" {{ $order->project_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-bold text-xs shadow-md">
                    Simpan Perubahan Status
                </button>
            </form>

            <hr class="border-slate-100">

            <!-- Delete Order -->
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs">
                    Hapus Pesanan Ini
                </button>
            </form>
        </div>

    </div>
</div>

<!-- Formal E-Receipt Component for Print / PDF Generation -->
@include('partials.receipt-modal')

@push('scripts')
<!-- HTML2PDF Library for Direct 1-Page PDF Downloads -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function downloadReceiptPdf(btn) {
    var receiptEl = document.getElementById('receipt-print-area');
    if (!receiptEl) {
        window.print();
        return;
    }

    var originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-1">⏳</span> Menyiapkan PDF...';
    }

    // Create a perfectly formatted tailored receipt slip container (No outer box border)
    var clone = receiptEl.cloneNode(true);
    clone.classList.remove('hidden', 'print:block');
    clone.style.display = 'block';
    clone.style.width = '480px';
    clone.style.maxWidth = '480px';
    clone.style.padding = '10px 14px';
    clone.style.margin = '0 auto';
    clone.style.background = '#ffffff';
    clone.style.border = 'none';
    clone.style.boxShadow = 'none';
    clone.style.borderRadius = '0px';
    clone.style.boxSizing = 'border-box';
    clone.style.fontSize = '12px';
    clone.style.lineHeight = '1.4';
    clone.style.fontFamily = "'Courier New', Courier, 'Courier Prime', SFMono-Regular, Consolas, monospace";
    clone.style.color = '#0f172a';

    var container = document.createElement('div');
    container.style.position = 'fixed';
    container.style.left = '-9999px';
    container.style.top = '0';
    container.style.width = '500px';
    container.style.background = '#ffffff';
    container.appendChild(clone);
    document.body.appendChild(container);

    // Calculate dynamic slip height in mm so the PDF page fits the receipt without huge blank bottom space
    var slipWidthMm = 135; // Formal e-receipt slip standard width
    var contentRatio = clone.offsetHeight / (clone.offsetWidth || 480);
    var slipHeightMm = Math.ceil(slipWidthMm * contentRatio) + 10;

    var invNum = '{{ $order->invoice_number }}';
    var opt = {
        margin: [4, 4, 4, 4],
        filename: 'Resi-JuangDev-' + invNum + '.pdf',
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { 
            scale: 2.5, 
            useCORS: true, 
            logging: false,
            letterRendering: true,
            scrollX: 0,
            scrollY: 0
        },
        jsPDF: { 
            unit: 'mm', 
            format: [slipWidthMm, slipHeightMm], 
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
                if (window.lucide) lucide.createIcons();
            }
        }).catch(function(err) {
            console.error('PDF generation error:', err);
            if (document.body.contains(container)) {
                document.body.removeChild(container);
            }
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                if (window.lucide) lucide.createIcons();
            }
            window.print();
        });
    } else {
        if (document.body.contains(container)) {
            document.body.removeChild(container);
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (window.lucide) lucide.createIcons();
        }
        window.print();
    }
}
</script>
@endpush
@endsection
