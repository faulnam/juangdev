@extends('layouts.admin')

@section('title', 'Add-on Features')
@section('page_title', 'Kelola Fitur Add-on Estimator')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.8fr] gap-8 items-start" x-data="{ search: '' }">
        
        <!-- Form Add New Feature -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-black text-slate-900 mb-4">Tambah Fitur Add-on</h3>
            
            <form action="{{ route('admin.service-features.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Layanan</label>
                    <select name="service_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="">Semua Layanan (Global)</option>
                        @foreach($services as $srv)
                            <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Fitur *</label>
                    <input type="text" name="title" required placeholder="Contoh: Payment Gateway Integration" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga (Rp) *</label>
                    <input type="number" name="price" required placeholder="800000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Keterangan singkat mengenai fitur..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Fitur Add-on
                </button>
            </form>
        </div>

        <!-- Features Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="text-base font-black text-slate-900">Daftar Fitur Add-on</h3>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari fitur add-on..." 
                        class="pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-full sm:w-56"
                    >
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-6">Nama Fitur</th>
                            <th class="py-3 px-6">Layanan</th>
                            <th class="py-3 px-6">Tambahan Biaya</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($features as $feature)
                            <tr 
                                x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50/80 transition-colors"
                            >
                                <td class="py-3.5 px-6 font-bold text-slate-900">
                                    {{ $feature->title }}
                                    @if($feature->description)
                                        <p class="text-xs text-slate-400 font-normal mt-0.5">{{ $feature->description }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 text-xs font-semibold text-slate-600">
                                    {{ $feature->service ? $feature->service->name : 'Semua Layanan' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-emerald-600">
                                    + Rp {{ number_format($feature->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <form action="{{ route('admin.service-features.destroy', $feature->id) }}" method="POST" onsubmit="return confirm('Hapus fitur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada fitur add-on.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
