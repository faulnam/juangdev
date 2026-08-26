@extends('layouts.admin')

@section('title', 'Detail Akun Pelanggan — ' . $customerName)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-[#2563EB] hover:underline inline-flex items-center gap-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Data Akun Pelanggan</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900">{{ $customerName }}</h1>
            <p class="text-slate-500 text-sm font-medium mt-0.5">{{ $phone }} • {{ $customerEmail }}</p>
        </div>

        @if(!empty($phone) && $phone !== '-')
            <div>
                <a 
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all"
                >
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Kirim WhatsApp ke Pelanggan</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Customer Profile Info Card -->
    @if(isset($user))
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
            <div class="bg-slate-50 p-4 rounded-xl">
                <span class="text-slate-400 block mb-1 font-semibold uppercase text-[10px]">Metode Pendaftaran</span>
                @if($user->firebase_uid)
                    <span class="font-bold text-amber-700 flex items-center gap-1.5">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                        <span>Google Firebase SSO</span>
                    </span>
                @else
                    <span class="font-bold text-blue-700 flex items-center gap-1.5">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        <span>Email &amp; Password</span>
                    </span>
                @endif
            </div>

            <div class="bg-slate-50 p-4 rounded-xl">
                <span class="text-slate-400 block mb-1 font-semibold uppercase text-[10px]">Tanggal Terdaftar</span>
                <span class="font-bold text-slate-900">{{ $user->created_at->format('d F Y, H:i') }} WIB</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl">
                <span class="text-slate-400 block mb-1 font-semibold uppercase text-[10px]">Total Nilai Proyek</span>
                <span class="font-black text-[#2563EB] text-sm">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl">
                <span class="text-slate-400 block mb-1 font-semibold uppercase text-[10px]">Total Terbayar (DP/Lunas)</span>
                <span class="font-black text-emerald-600 text-sm">
                    Rp {{ number_format($orders->whereIn('payment_status', ['dp_paid', 'fully_paid'])->sum('total_amount'), 0, ',', '.') }}
                </span>
            </div>
        </div>
    @endif

    <!-- Orders History Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>Riwayat Pesanan Proyek ({{ $orders->count() }})</span>
            <span class="text-xs font-bold text-[#2563EB]">Total Proyek: Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Invoice</th>
                        <th class="py-3 px-4">Layanan &amp; Paket</th>
                        <th class="py-3 px-4 text-right">Total Biaya</th>
                        <th class="py-3 px-4 text-right">DP 50%</th>
                        <th class="py-3 px-4 text-center">Status Pembayaran</th>
                        <th class="py-3 px-4 text-center">Status Proyek</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-[#2563EB]">#{{ $ord->invoice_number }}</td>
                            <td class="py-3 px-4 font-bold">
                                {{ $ord->service_name }}
                                <span class="block text-[10px] font-normal text-slate-400">{{ $ord->package_name ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-black">{{ $ord->formatted_total }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-600">{{ $ord->formatted_dp }}</td>
                            <td class="py-3 px-4 text-center uppercase font-bold">
                                @if($ord->payment_status === 'fully_paid')
                                    <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full text-[10px]">Lunas 100%</span>
                                @elseif($ord->payment_status === 'dp_paid')
                                    <span class="text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full text-[10px]">DP 50%</span>
                                @else
                                    <span class="text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full text-[10px]">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center uppercase font-bold">
                                @if($ord->project_status === 'completed')
                                    <span class="text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full text-[10px]">Selesai</span>
                                @elseif($ord->project_status === 'in_progress')
                                    <span class="text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-full text-[10px]">Pengerjaan</span>
                                @else
                                    <span class="text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full text-[10px]">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2.5 py-1 bg-slate-100 text-slate-800 font-bold rounded-lg hover:bg-slate-200 transition-colors">
                                    Lihat Pesanan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">Belum ada riwayat pesanan untuk pelanggan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
