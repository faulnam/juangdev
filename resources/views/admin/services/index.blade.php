@extends('layouts.admin')

@section('title', 'Layanan Utama')
@section('page_title', 'Kelola Layanan Utama')

@section('content')
    <div x-data="{ search: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari layanan, slug, atau fitur..." 
                        class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-64 sm:w-80 bg-white shadow-sm"
                    >
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $services->count() }} layanan</span>
            </div>
            
            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A1E5E] text-[#C7F236] text-xs font-bold hover:bg-[#122d78] shadow-md transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Layanan Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Nama Layanan</th>
                            <th class="py-3.5 px-6">Slug</th>
                            <th class="py-3.5 px-6">Base Price (Estimator)</th>
                            <th class="py-3.5 px-6">Starting Price (Display)</th>
                            <th class="py-3.5 px-6">Fitur</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($services as $service)
                            <tr 
                                x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50/80 transition-colors"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#2563EB] flex items-center justify-center font-black text-xs">
                                            <i data-lucide="{{ $service->icon ?? 'globe' }}" class="w-4 h-4"></i>
                                        </div>
                                        <span>{{ $service->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs font-mono text-slate-500">{{ $service->slug }}</td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-600">
                                    {{ $service->starting_price ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500 max-w-xs truncate">
                                    {{ is_array($service->features) ? implode(', ', $service->features) : $service->features }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($service->is_active)
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">Aktif</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 text-sm font-medium">
                                    Belum ada layanan yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
