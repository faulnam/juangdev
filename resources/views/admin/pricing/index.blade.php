@extends('layouts.admin')

@section('title', 'Paket Harga')
@section('page_title', 'Kelola Paket Harga & Investasi')

@section('content')
    <div x-data="{ search: '', selectedCategory: 'all' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari paket, kategori, fitur..." 
                        class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-64 sm:w-72 bg-white shadow-sm"
                    >
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $plans->count() }} paket</span>
            </div>
            
            <a href="{{ route('admin.pricing.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A1E5E] text-[#C7F236] text-xs font-bold hover:bg-[#122d78] shadow-md transition-all cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Paket Baru</span>
            </a>
        </div>

        <!-- Filter Kategori Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-2">
            <button 
                type="button"
                @click="selectedCategory = 'all'" 
                :class="selectedCategory === 'all' ? 'bg-[#0A1E5E] text-[#C7F236]' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer shadow-2xs"
            >
                Semua Layanan
            </button>
            @foreach($categories as $catKey => $catName)
                <button 
                    type="button"
                    @click="selectedCategory = '{{ $catKey }}'" 
                    :class="selectedCategory === '{{ $catKey }}' ? 'bg-[#0A1E5E] text-[#C7F236]' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer shadow-2xs"
                >
                    {{ $catName }}
                </button>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Nama Paket</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Harga</th>
                            <th class="py-3.5 px-6">Fitur</th>
                            <th class="py-3.5 px-6">Badge / Populer</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($plans as $plan)
                            <tr 
                                x-show="(selectedCategory === 'all' || '{{ $plan->category }}' === selectedCategory) && (search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase()))"
                                class="hover:bg-slate-50/80 transition-colors"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $plan->name }}
                                    <p class="text-xs text-slate-400 font-normal mt-0.5">{{ $plan->description }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-md">
                                        {{ $categories[$plan->category] ?? $plan->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-black text-slate-900">
                                    Rp {{ $plan->price }}
                                    @if($plan->period)
                                        <span class="text-xs font-normal text-slate-400">/ {{ $plan->period }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500 max-w-xs truncate">
                                    {{ is_array($plan->features) ? implode(', ', $plan->features) : $plan->features }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($plan->popular)
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">Paling Populer</span>
                                    @elseif($plan->badge)
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">{{ $plan->badge }}</span>
                                    @else
                                        <span class="text-slate-300 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($plan->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.pricing.edit', $plan->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.pricing.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 text-sm font-medium">
                                    Belum ada paket harga.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
