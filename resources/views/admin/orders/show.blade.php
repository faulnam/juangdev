@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
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

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.orders.send-wa', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 shadow-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Invoice Formal ke WA Klien</span>
                </button>
            </form>

            <a href="{{ route('invoice.show', $order->invoice_number) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs flex items-center gap-2">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Buka Invoice Publik</span>
            </a>
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

                    <div class="flex items-center justify-between text-sm">
                        <span class="font-bold text-slate-700">Total Investasi Proyek:</span>
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
@endsection
