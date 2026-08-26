@extends('layouts.admin')

@section('title', 'Data Akun Pelanggan Terdaftar — JuangDev')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Akun Pelanggan Terdaftar</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Daftar seluruh customer yang telah membuat akun di website JuangDev (Email/WhatsApp &amp; Google Firebase)</p>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center font-bold">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Akun Terdaftar</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $totalRegistered }} <span class="text-xs font-medium text-slate-500">Customer</span></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <i data-lucide="mail" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Daftar Email / WhatsApp</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $totalEmailUsers }} <span class="text-xs font-medium text-slate-500">Akun</span></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <i data-lucide="globe" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Google Firebase SSO</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $totalGoogleUsers }} <span class="text-xs font-medium text-slate-500">Akun</span></p>
            </div>
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
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors cursor-pointer">
                Cari Akun
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Customer &amp; Identitas</th>
                        <th class="py-3.5 px-6">Kontak WhatsApp</th>
                        <th class="py-3.5 px-6 text-center">Metode Daftar</th>
                        <th class="py-3.5 px-6 text-center">Total Pesanan</th>
                        <th class="py-3.5 px-6 text-right">Total Belanja (DP/Lunas)</th>
                        <th class="py-3.5 px-6 text-center">Tanggal Registrasi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 text-xs">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    @if($c->avatar)
                                        <img src="{{ $c->avatar }}" alt="{{ $c->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-blue-100 text-[#2563EB] flex items-center justify-center font-black text-sm shrink-0">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-slate-900 font-bold text-sm">{{ $c->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-normal">{{ $c->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                @if($c->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->phone) }}" target="_blank" class="font-bold text-emerald-700 hover:underline flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        <span>{{ $c->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Belum diisi</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($c->firebase_uid)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                        <i data-lucide="globe" class="w-3 h-3"></i>
                                        <span>Google Firebase</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                        <i data-lucide="mail" class="w-3 h-3"></i>
                                        <span>Email &amp; HP</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-slate-100 text-slate-800 font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $c->orders_count ?? 0 }} Pesanan
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-black text-slate-900">
                                Rp {{ number_format($c->total_spent ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center text-slate-500 font-medium">
                                {{ $c->created_at ? $c->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.customers.show', $c->id) }}" class="px-3.5 py-1.5 rounded-lg bg-slate-100 text-slate-800 font-bold text-xs hover:bg-slate-200 transition-colors inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail &amp; Proyek</span>
                                </a>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="users" class="w-6 h-6 text-slate-400"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Belum Ada Data Customer</p>
                                <p class="text-xs text-slate-400 mt-0.5">Data customer yang mendaftar atau order akan muncul di sini.</p>
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
