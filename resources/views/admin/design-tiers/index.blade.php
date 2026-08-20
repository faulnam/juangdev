@extends('layouts.admin')

@section('title', 'Design Tiers')
@section('page_title', 'Kelola Design Tiers Estimator')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.8fr] gap-8 items-start">
        
        <!-- Form Add Design Tier -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-black text-slate-900 mb-4">Tambah Design Tier</h3>
            
            <form action="{{ route('admin.design-tiers.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Tier *</label>
                    <input type="text" name="name" required placeholder="Contoh: Putih (Standard)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga Tambahan (Rp) *</label>
                    <input type="number" name="price" required placeholder="0 untuk gratis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Keterangan tier..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Tier
                </button>
            </form>
        </div>

        <!-- Design Tiers Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Daftar Design Tiers</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-6">Nama Tier</th>
                            <th class="py-3 px-6">Deskripsi</th>
                            <th class="py-3 px-6">Tambahan Biaya</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($tiers as $tier)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-6 font-bold text-slate-900">
                                    {{ $tier->name }}
                                </td>
                                <td class="py-3.5 px-6 text-xs text-slate-500">
                                    {{ $tier->description ?? '-' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold {{ $tier->price > 0 ? 'text-amber-600' : 'text-slate-500' }}">
                                    {{ $tier->price == 0 ? 'Gratis (Rp 0)' : '+ Rp ' . number_format($tier->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <form action="{{ route('admin.design-tiers.destroy', $tier->id) }}" method="POST" onsubmit="return confirm('Hapus tier ini?')">
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
                                    Belum ada design tiers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
