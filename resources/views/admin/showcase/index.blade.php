@extends('layouts.admin')

@section('title', 'Showcase Layanan')
@section('page_title', 'Kelola Showcase Layanan')

@section('content')
    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.showcase.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-black text-slate-900">Banner Showcase (Pengembangan Website &amp; Sistem Modern)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Atur judul, deskripsi, harga mulai, serta poin keunggulan showcase layanan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Badge Teks</label>
                    <input 
                        type="text" 
                        name="feature_showcase_badge" 
                        value="{{ $settings['feature_showcase_badge'] ?? 'Enterprise & Custom Build' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Biaya Mulai</label>
                    <input 
                        type="text" 
                        name="feature_showcase_price" 
                        value="{{ $settings['feature_showcase_price'] ?? 'Rp 999.000' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Utama Showcase</label>
                <input 
                    type="text" 
                    name="feature_showcase_title" 
                    value="{{ $settings['feature_showcase_title'] ?? 'Pengembangan Website & Sistem Modern' }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Ringkas</label>
                <textarea 
                    name="feature_showcase_desc" 
                    rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ $settings['feature_showcase_desc'] ?? 'Solusi website dan aplikasi web internal yang cepat, aman, responsif di seluruh perangkat, dan mudah dikelola.' }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Gambar Mockup / Laptop Showcase</label>
                
                @php $currentImg = $settings['feature_showcase_image'] ?? '/services-laptop.jpg'; @endphp
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-28 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-900 shrink-0">
                        <img src="{{ $currentImg }}" alt="Mockup Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="text-xs text-slate-500">
                        <p class="font-bold text-slate-700 mb-0.5">Gambar Saat Ini:</p>
                        <p class="truncate max-w-xs text-slate-500">{{ $currentImg }}</p>
                    </div>
                </div>

                <input 
                    type="file" 
                    name="feature_showcase_image_file" 
                    accept="image/*"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-slate-50 text-slate-700 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0A1E5E] file:text-[#C7F236] hover:file:bg-[#122d78] cursor-pointer"
                >
                <input type="hidden" name="feature_showcase_image" value="{{ $currentImg }}">
                <p class="text-[11px] text-slate-400 mt-1">Upload file gambar baru (JPG, PNG, WebP) untuk menggantikan gambar mockup laptop.</p>
            </div>

            <!-- 4 Value Points -->
            <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">4 Poin Keunggulan Utama</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 1: Judul</label>
                        <input type="text" name="feature_showcase_point1_title" value="{{ $settings['feature_showcase_point1_title'] ?? 'Antarmuka User-Friendly & Responsif' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 1: Deskripsi</label>
                        <input type="text" name="feature_showcase_point1_desc" value="{{ $settings['feature_showcase_point1_desc'] ?? 'Desain UI/UX intuitif yang optimal di layar laptop maupun smartphone.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 2: Judul</label>
                        <input type="text" name="feature_showcase_point2_title" value="{{ $settings['feature_showcase_point2_title'] ?? 'Performa Cepat & Stabil' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 2: Deskripsi</label>
                        <input type="text" name="feature_showcase_point2_desc" value="{{ $settings['feature_showcase_point2_desc'] ?? 'Arsitektur modern dan efisien untuk menjamin sistem berjalan mulus tanpa lag.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 3: Judul</label>
                        <input type="text" name="feature_showcase_point3_title" value="{{ $settings['feature_showcase_point3_title'] ?? 'Keamanan Data Terjamin' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 3: Deskripsi</label>
                        <input type="text" name="feature_showcase_point3_desc" value="{{ $settings['feature_showcase_point3_desc'] ?? 'Enkripsi SSL dan proteksi data berlapis untuk menjaga transaksi bisnis Anda.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 4: Judul</label>
                        <input type="text" name="feature_showcase_point4_title" value="{{ $settings['feature_showcase_point4_title'] ?? 'Dashboard & Manajemen Terintegrasi' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Point 4: Deskripsi</label>
                        <input type="text" name="feature_showcase_point4_desc" value="{{ $settings['feature_showcase_point4_desc'] ?? 'Kemudahan mengelola konten dan melihat laporan performa secara terpusat.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Perubahan Showcase
                </button>
            </div>
        </form>
    </div>
@endsection
