@extends('layouts.admin')

@section('title', 'Kelola Bagian Tentang Kami (About)')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Bagian Tentang Kami (About Section)</h1>
            <p class="text-xs text-slate-500 mt-1">Atur teks judul, deskripsi, nilai statistik, dan 3 kartu visual Bento Grid Tentang JuangDev di Beranda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('home') }}#about" 
                target="_blank" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors"
            >
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Lihat di Beranda</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.about-section.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Header Judul & Deskripsi -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="heading" class="w-4 h-4 text-[#2563EB]"></i>
                    <span>Header Bagian Tentang Kami</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teks Judul Awal</label>
                    <input 
                        type="text" 
                        name="about_title_1" 
                        value="{{ $settings['about_title_1'] ?? 'Tentang' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                        placeholder="Tentang"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teks Judul Highlight (Biru / Italic)</label>
                    <input 
                        type="text" 
                        name="about_title_highlight" 
                        value="{{ $settings['about_title_highlight'] ?? 'JuangDev' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                        placeholder="JuangDev"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Utama</label>
                    <textarea 
                        name="about_desc" 
                        rows="3"
                        class="w-full text-xs rounded-xl border border-slate-200 p-3.5 outline-none focus:border-[#2563EB] leading-relaxed"
                    >{{ $settings['about_desc'] ?? 'JuangDev hadir sebagai studio teknologi dan mitra strategis yang berfokus membangun website profesional, aplikasi web kustom, dan produk digital inovatif yang dirancang khusus untuk mengakselerasi pertumbuhan bisnis Anda.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: 3 Bento Cards Visual Editor -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- BENTO CARD 1 (Navy / Kiri: Laptop + Stats) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <span class="inline-block text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase mb-1">Bento Card 1 (Kiri)</span>
                        <h3 class="text-sm font-bold text-slate-900">Mockup Laptop &amp; 2 Statistik</h3>
                    </div>

                    <!-- Laptop Mockup Upload -->
                    <div class="space-y-2 mb-4">
                        <label class="block text-xs font-bold text-slate-700">Gambar Mockup Laptop</label>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                            @php $card1Img = $settings['about_card1_image'] ?? '/about-laptop.png'; @endphp
                            <img src="{{ $card1Img }}" alt="Laptop Mockup" class="h-28 object-contain mb-2 rounded-lg bg-[#05111f] p-2">
                            <input type="file" name="about_card1_image_file" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-full file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-[#2563EB]">
                        </div>
                    </div>

                    <!-- Stat 1 (100% Transparansi) -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2 mb-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Statistik 1 (Kiri - Lime)</p>
                        <input type="text" name="about_card1_stat1_val" value="{{ $settings['about_card1_stat1_val'] ?? '100%' }}" placeholder="100%" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        <input type="text" name="about_card1_stat1_label" value="{{ $settings['about_card1_stat1_label'] ?? 'Transparansi Penuh' }}" placeholder="Transparansi Penuh" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                    </div>

                    <!-- Stat 2 (30 Hari Dukungan) -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Statistik 2 (Kanan - Putih)</p>
                        <input type="text" name="about_card1_stat2_val" value="{{ $settings['about_card1_stat2_val'] ?? '30 Hari' }}" placeholder="30 Hari" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        <input type="text" name="about_card1_stat2_label" value="{{ $settings['about_card1_stat2_label'] ?? 'Dukungan Purna Jual' }}" placeholder="Dukungan Purna Jual" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                    </div>
                </div>
            </div>

            <!-- BENTO CARD 2 (Lime / Tengah: Judul, Deskripsi & Tablet) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <span class="inline-block text-[10px] font-black text-lime-700 bg-lime-100 px-2 py-0.5 rounded-md uppercase mb-1">Bento Card 2 (Tengah)</span>
                        <h3 class="text-sm font-bold text-slate-900">Solusi Digital (Kartu Lime)</h3>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Kartu</label>
                            <input 
                                type="text" 
                                name="about_card2_title" 
                                value="{{ $settings['about_card2_title'] ?? 'Solusi Digital Untuk Bisnis Anda' }}" 
                                class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kartu</label>
                            <textarea 
                                name="about_card2_desc" 
                                rows="3"
                                class="w-full text-xs rounded-xl border border-slate-200 p-3 outline-none focus:border-[#2563EB] leading-relaxed"
                            >{{ $settings['about_card2_desc'] ?? 'Kami menghadirkan layanan pembuatan website kustom mulai dari Landing Page, Company Profile, hingga E-Commerce yang cepat, responsif, dan ramah SEO.' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Gambar Tablet Dashboard</label>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                                @php $card2Img = $settings['about_card2_image'] ?? '/about-tablet.png'; @endphp
                                <img src="{{ $card2Img }}" alt="Tablet Dashboard" class="h-28 object-cover object-top mb-2 rounded-lg border border-slate-200">
                                <input type="file" name="about_card2_image_file" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-full file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-[#2563EB]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BENTO CARD 3 (Navy / Kanan: Tim Kolaborasi & Dukungan) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <span class="inline-block text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase mb-1">Bento Card 3 (Kanan)</span>
                        <h3 class="text-sm font-bold text-slate-900">Kolaborasi Tim &amp; Transparansi</h3>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Gambar Kolaborasi Tim</label>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                                @php $card3Img = $settings['about_card3_image'] ?? '/about-team.png'; @endphp
                                <img src="{{ $card3Img }}" alt="Team Collaboration" class="h-28 object-cover mb-2 rounded-lg border border-slate-200">
                                <input type="file" name="about_card3_image_file" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-full file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-[#2563EB]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Kartu</label>
                            <input 
                                type="text" 
                                name="about_card3_title" 
                                value="{{ $settings['about_card3_title'] ?? 'Transparansi & Dukungan Berkelanjutan' }}" 
                                class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kartu</label>
                            <textarea 
                                name="about_card3_desc" 
                                rows="3"
                                class="w-full text-xs rounded-xl border border-slate-200 p-3 outline-none focus:border-[#2563EB] leading-relaxed"
                            >{{ $settings['about_card3_desc'] ?? 'Kami memberikan transparansi penuh mulai dari pembaruan progres mingguan hingga serah terima lengkap, didukung oleh layanan perawatan secara berkala.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button Footer -->
        <div class="mt-8 flex items-center justify-end gap-3 sticky bottom-6 bg-white/90 backdrop-blur-md p-4 rounded-2xl border border-slate-200/80 shadow-lg">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-xs font-bold text-white bg-[#2563EB] hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all cursor-pointer"
            >
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Simpan Perubahan Tentang Kami</span>
            </button>
        </div>

    </form>
</div>
@endsection
