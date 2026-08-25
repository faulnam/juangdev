@extends('layouts.admin')

@section('title', 'Add-on Features')
@section('page_title', 'Kelola Fitur Add-on Estimator')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.8fr] gap-8 items-start" x-data="{ 
        search: '', 
        editModalOpen: false, 
        editItem: { id: null, title: '', price: 0, description: '', is_active: 1, display_order: 0, popular: 0 },
        openEdit(item) {
            this.editItem = { ...item };
            this.editModalOpen = true;
        }
    }">
        
        <!-- Form Add New Feature -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-black text-slate-900 mb-4">Tambah Fitur Add-on Baru</h3>
            
            <form action="{{ route('admin.service-features.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Fitur Add-on *</label>
                    <input type="text" name="title" required placeholder="Contoh: Payment Gateway Integration" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tambahan Biaya (Rp) *</label>
                    <input type="number" name="price" required placeholder="Contoh: 500000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Keterangan singkat mengenai add-on..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Tampilan *</label>
                        <select name="is_active" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                            <option value="1" selected>Aktif (Tampil)</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan (Order)</label>
                        <input type="number" name="display_order" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] shadow-md transition-all cursor-pointer">
                    Simpan Fitur Add-on
                </button>
            </form>
        </div>

        <!-- Features Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-black text-slate-900">Daftar Fitur Add-on</h3>
                    <p class="text-xs text-slate-400 font-medium">Add-on yang berstatus aktif akan muncul di pilihan Estimator Interaktif.</p>
                </div>
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
                            <th class="py-3 px-6">Tambahan Biaya</th>
                            <th class="py-3 px-6">Status</th>
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
                                <td class="py-3.5 px-6 font-bold text-emerald-600">
                                    + Rp {{ number_format($feature->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-6">
                                    @if($feature->is_active)
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
                                <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button 
                                            type="button" 
                                            @click="openEdit({{ json_encode($feature) }})"
                                            class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer"
                                            title="Edit"
                                        >
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <form action="{{ route('admin.service-features.destroy', $feature->id) }}" method="POST" onsubmit="return confirm('Hapus fitur ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
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

        <!-- Edit Feature Modal -->
        <div 
            x-show="editModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        >
            <div 
                @click.away="editModalOpen = false"
                class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-slate-100 space-y-4"
            >
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-black text-slate-900">Edit Fitur Add-on</h4>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                </div>

                <form :action="'/admin/service-features/' + editItem.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Fitur *</label>
                        <input type="text" name="title" x-model="editItem.title" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tambahan Biaya (Rp) *</label>
                        <input type="number" name="price" x-model="editItem.price" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                        <textarea name="description" x-model="editItem.description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Tampilan *</label>
                            <select name="is_active" x-model="editItem.is_active" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white">
                                <option value="1">Aktif (Tampil)</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan (Order)</label>
                            <input type="number" name="display_order" x-model="editItem.display_order" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] shadow-md transition-all cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
