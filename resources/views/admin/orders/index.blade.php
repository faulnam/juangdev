@extends('layouts.admin')

@section('title', 'Daftar Pesanan & Invoice Pakasir')

@section('content')
<div class="space-y-6" x-data="{ search: '{{ request('search') }}' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Pesanan &amp; Invoice</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Kelola transaksi proyek, status pembayaran DP 50% / Pelunasan Pakasir</p>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari Invoice / Nama Klien / No WA..."
                class="w-full sm:w-64 px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
            >
            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                <option value="">-- Semua Status Bayar --</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="dp_paid" {{ request('status') === 'dp_paid' ? 'selected' : '' }}>DP 50% Lunas</option>
                <option value="fully_paid" {{ request('status') === 'fully_paid' ? 'selected' : '' }}>Lunas 100%</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">
                Filter
            </button>
        </form>
    </div>

    <!-- Orders Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">No. Invoice</th>
                        <th class="py-3.5 px-6">Klien</th>
                        <th class="py-3.5 px-6">Layanan / Proyek</th>
                        <th class="py-3.5 px-6 text-right">Total Biaya</th>
                        <th class="py-3.5 px-6 text-center">Status Bayar</th>
                        <th class="py-3.5 px-6 text-center">Status Proyek</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 text-xs">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-[#2563EB]">
                                <a href="{{ route('invoice.show', $ord->invoice_number) }}" target="_blank" class="hover:underline flex items-center gap-1">
                                    <span>#{{ $ord->invoice_number }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                                </a>
                                <p class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $ord->created_at ? $ord->created_at->format('d M Y, H:i') : '-' }}</p>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $ord->customer_name }}
                                <p class="text-[11px] font-normal text-slate-500 mt-0.5">{{ $ord->customer_phone }}</p>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $ord->service_name }}
                                @if($ord->package_name)
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ $ord->package_name }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-slate-900">
                                {{ $ord->formatted_total }}
                                <span class="block text-[10px] text-[#2563EB] font-normal">DP: {{ $ord->formatted_dp }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($ord->payment_status === 'fully_paid')
                                    <span class="bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">
                                        Lunas 100%
                                    </span>
                                @elseif($ord->payment_status === 'dp_paid')
                                    <span class="bg-amber-100 text-amber-800 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">
                                        DP 50% Lunas
                                    </span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px] uppercase">
                                    {{ str_replace('_', ' ', $ord->project_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-[#2563EB] font-bold text-xs hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada data pesanan proyek.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
