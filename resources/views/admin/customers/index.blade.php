@extends('layouts.admin')

@section('title', 'Data Pelanggan — JuangDev')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Data Pelanggan</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Daftar seluruh klien &amp; pelanggan yang melakukan pemesanan proyek di JuangDev</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center gap-3">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari Nama Pelanggan / WhatsApp / Email..."
                class="w-full sm:w-80 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
            >
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                Cari Pelanggan
            </button>
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Pelanggan</th>
                        <th class="py-3.5 px-6">Kontak WhatsApp / Email</th>
                        <th class="py-3.5 px-6 text-center">Total Pesanan</th>
                        <th class="py-3.5 px-6 text-right">Total Transaksi</th>
                        <th class="py-3.5 px-6 text-center">Pesanan Terakhir</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 text-xs">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#2563EB] flex items-center justify-center font-black text-sm shrink-0">
                                        {{ strtoupper(substr($c->customer_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-bold text-sm">{{ $c->customer_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                <p class="font-bold text-slate-900">{{ $c->customer_phone }}</p>
                                <p class="text-[11px] text-slate-400 font-normal">{{ $c->customer_email }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-blue-50 text-[#2563EB] font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $c->total_orders }} Pesanan
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-black text-slate-900">
                                Rp {{ number_format($c->total_spent, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center text-slate-500 font-medium">
                                {{ $c->last_order_at ? \Carbon\Carbon::parse($c->last_order_at)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.customers.show', urlencode($c->customer_phone)) }}" class="px-3.5 py-1.5 rounded-lg bg-slate-100 text-slate-800 font-bold text-xs hover:bg-slate-200 transition-colors inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Riwayat Proyek</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada data pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
