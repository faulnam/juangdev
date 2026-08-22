@extends('layouts.admin')

@section('title', 'Riwayat Pelanggan ' . $customerName)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-[#2563EB] hover:underline inline-flex items-center gap-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Data Pelanggan</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900">{{ $customerName }}</h1>
            <p class="text-slate-500 text-sm font-medium mt-0.5">{{ $phone }} • {{ $customerEmail }}</p>
        </div>
    </div>

    <!-- Orders History Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>Riwayat Pesanan Proyek ({{ $orders->count() }})</span>
            <span class="text-xs font-bold text-[#2563EB]">Total Spent: Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Invoice</th>
                        <th class="py-3 px-4">Layanan</th>
                        <th class="py-3 px-4 text-right">Total Biaya</th>
                        <th class="py-3 px-4 text-center">Status Bayar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $ord)
                        <tr>
                            <td class="py-3 px-4 font-mono font-bold text-[#2563EB]">#{{ $ord->invoice_number }}</td>
                            <td class="py-3 px-4 font-bold">{{ $ord->service_name }} <span class="block text-[10px] font-normal text-slate-400">{{ $ord->package_name }}</span></td>
                            <td class="py-3 px-4 text-right font-black">{{ $ord->formatted_total }}</td>
                            <td class="py-3 px-4 text-center uppercase font-bold">
                                @if($ord->payment_status === 'fully_paid')
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Lunas 100%</span>
                                @elseif($ord->payment_status === 'dp_paid')
                                    <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded">DP 50%</span>
                                @else
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded">Unpaid</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2.5 py-1 bg-slate-100 text-slate-800 font-bold rounded hover:bg-slate-200">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Belum ada riwayat pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
