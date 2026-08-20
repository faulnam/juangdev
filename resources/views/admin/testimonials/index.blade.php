@extends('layouts.admin')

@section('title', 'Testimoni & Reviews')
@section('page_title', 'Kelola Testimoni Klien')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.8fr] gap-8 items-start" x-data="{ search: '' }">
        
        <!-- Form Add Testimonial -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-black text-slate-900 mb-4">Tambah Testimoni Baru</h3>
            
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Klien *</label>
                    <input type="text" name="name" required placeholder="Contoh: Sarah Wijaya" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Role / Jabatan *</label>
                        <input type="text" name="role" required placeholder="CEO of TechFlow" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Rating (1-5) *</label>
                        <select name="rating" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                            <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                            <option value="3">⭐⭐⭐ (3 Bintang)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Testimoni *</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan ulasan klien mengenai hasil kerja JuangDev..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Avatar Foto Klien</label>
                    <input 
                        type="file" 
                        name="avatar_file" 
                        accept="image/*"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-slate-50/50 mb-2"
                    >
                    <input type="text" name="avatar_url" placeholder="Atau URL gambar (Opsional)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Testimoni
                </button>
            </form>
        </div>

        <!-- Testimonials List -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="text-base font-black text-slate-900">Daftar Ulasan Klien ({{ $testimonials->count() }})</h3>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari ulasan / klien..." 
                        class="pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-full sm:w-56"
                    >
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($testimonials as $t)
                    <div 
                        x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                        class="p-6 hover:bg-slate-50/80 transition-colors flex items-start justify-between gap-4"
                    >
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-1 text-amber-500">
                                @for($i = 0; $i < ($t->rating ?? 5); $i++)
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-500"></i>
                                @endfor
                            </div>
                            <p class="text-slate-800 text-sm font-medium leading-relaxed italic">
                                &ldquo;{{ $t->content }}&rdquo;
                            </p>
                            <div class="flex items-center gap-2 pt-1">
                                @if($t->avatar_url)
                                    <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                @endif
                                <p class="text-xs font-bold text-slate-900">{{ $t->name }}</p>
                                <span class="text-slate-300">•</span>
                                <p class="text-xs text-slate-500">{{ $t->role }}</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus testimoni ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm font-medium">
                        Belum ada testimoni klien.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
