@extends('layouts.admin')

@section('title', 'Kelola Hero Section Tiap Halaman')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'home' }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Hero Section Tiap Halaman</h1>
            <p class="text-xs text-slate-500 mt-1">Atur headline, deskripsi, tombol aksi, angka statistik, dan visual hero banner untuk semua halaman utama.</p>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('home') }}" 
                target="_blank" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors"
            >
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Lihat Website</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Page Selection Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 hide-scrollbar">
        <button 
            type="button"
            @click="activeTab = 'home'"
            :class="activeTab === 'home' ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80 font-medium'"
            class="px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 whitespace-nowrap transition-all"
        >
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Beranda (Home)</span>
        </button>

        <button 
            type="button"
            @click="activeTab = 'services'"
            :class="activeTab === 'services' ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80 font-medium'"
            class="px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 whitespace-nowrap transition-all"
        >
            <i data-lucide="briefcase" class="w-4 h-4"></i>
            <span>Halaman Layanan</span>
        </button>

        <button 
            type="button"
            @click="activeTab = 'portfolio'"
            :class="activeTab === 'portfolio' ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80 font-medium'"
            class="px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 whitespace-nowrap transition-all"
        >
            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
            <span>Halaman Portofolio</span>
        </button>

        <button 
            type="button"
            @click="activeTab = 'blog'"
            :class="activeTab === 'blog' ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80 font-medium'"
            class="px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 whitespace-nowrap transition-all"
        >
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>Halaman Blog</span>
        </button>

        <button 
            type="button"
            @click="activeTab = 'contact'"
            :class="activeTab === 'contact' ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80 font-medium'"
            class="px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 whitespace-nowrap transition-all"
        >
            <i data-lucide="phone" class="w-4 h-4"></i>
            <span>Halaman Kontak</span>
        </button>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.hero-sections.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- TAB 1: HERO BERANDA (HOME) -->
        <div x-show="activeTab === 'home'" x-cloak class="space-y-6">
            
            <!-- Headline & Content Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="type" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Headline &amp; Teks Utama Hero Beranda</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Atur struktur teks 3 baris judul utama dengan efek warna teks italic &amp; lime.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Baris 1 — Teks Utama</label>
                        <input 
                            type="text" 
                            name="hero_home_title_1" 
                            value="{{ $settings['hero_home_title_1'] ?? 'Bangun' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none"
                            placeholder="Contoh: Bangun"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Baris 1 — Teks Highlight (Warna Lime/Italic)</label>
                        <input 
                            type="text" 
                            name="hero_home_title_highlight_1" 
                            value="{{ $settings['hero_home_title_highlight_1'] ?? 'Website' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none"
                            placeholder="Contoh: Website"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Baris 2 — Teks Utama</label>
                        <input 
                            type="text" 
                            name="hero_home_title_2" 
                            value="{{ $settings['hero_home_title_2'] ?? 'Aplikasi' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none"
                            placeholder="Contoh: Aplikasi"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Baris 2 — Teks Highlight (Warna Lime/Italic)</label>
                        <input 
                            type="text" 
                            name="hero_home_title_highlight_2" 
                            value="{{ $settings['hero_home_title_highlight_2'] ?? 'Web Kustom' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none"
                            placeholder="Contoh: Web Kustom"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Baris 3 — Penutup Headline</label>
                        <input 
                            type="text" 
                            name="hero_home_title_3" 
                            value="{{ $settings['hero_home_title_3'] ?? 'Yang Memajukan Bisnis Anda.' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none"
                            placeholder="Contoh: Yang Memajukan Bisnis Anda."
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Paragraf Deskripsi Hero</label>
                        <textarea 
                            name="hero_home_desc" 
                            rows="3"
                            class="w-full text-xs rounded-xl border border-slate-200 p-3.5 focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] outline-none leading-relaxed"
                            placeholder="Tuliskan deskripsi singkat mengenai layanan dan keunggulan JuangDev..."
                        >{{ $settings['hero_home_desc'] ?? 'JuangDev membantu bisnis, UMKM, dan perusahaan membangun website profesional, aplikasi web kustom, toko online, sistem informasi, dan solusi digital modern yang mempercepat pertumbuhan bisnis.' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons & Trust Indicators -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Tombol CTA -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-[#2563EB]"></i>
                            <span>Tombol Aksi (CTA)</span>
                        </h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tombol Utama (Lime)</label>
                        <input 
                            type="text" 
                            name="hero_home_cta_primary_text" 
                            value="{{ $settings['hero_home_cta_primary_text'] ?? 'Mulai Proyek Anda' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 focus:border-[#2563EB] outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tombol Kedua (Transparan)</label>
                        <input 
                            type="text" 
                            name="hero_home_cta_secondary_text" 
                            value="{{ $settings['hero_home_cta_secondary_text'] ?? 'Lihat Portofolio' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 focus:border-[#2563EB] outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Link URL Tombol Kedua</label>
                        <input 
                            type="text" 
                            name="hero_home_cta_secondary_url" 
                            value="{{ $settings['hero_home_cta_secondary_url'] ?? '/portfolio' }}" 
                            class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 focus:border-[#2563EB] outline-none"
                        >
                    </div>
                </div>

                <!-- 4 Indikator Statistik (Bawah Tombol) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-[#2563EB]"></i>
                            <span>4 Indikator Statistik Bawah Hero</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 1 Nilai</label>
                            <input type="text" name="hero_home_stat_1_val" value="{{ $settings['hero_home_stat_1_val'] ?? '5.0' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 1 Label</label>
                            <input type="text" name="hero_home_stat_1_label" value="{{ $settings['hero_home_stat_1_label'] ?? 'Penilaian' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 2 Nilai</label>
                            <input type="text" name="hero_home_stat_2_val" value="{{ $settings['hero_home_stat_2_val'] ?? '100+' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 2 Label</label>
                            <input type="text" name="hero_home_stat_2_label" value="{{ $settings['hero_home_stat_2_label'] ?? 'Proyek Selesai' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 3 Nilai</label>
                            <input type="text" name="hero_home_stat_3_val" value="{{ $settings['hero_home_stat_3_val'] ?? '50+' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 3 Label</label>
                            <input type="text" name="hero_home_stat_3_label" value="{{ $settings['hero_home_stat_3_label'] ?? 'Klien Puas' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 4 Nilai</label>
                            <input type="text" name="hero_home_stat_4_val" value="{{ $settings['hero_home_stat_4_val'] ?? '3+' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Stat 4 Label</label>
                            <input type="text" name="hero_home_stat_4_label" value="{{ $settings['hero_home_stat_4_label'] ?? 'Tahun Pengalaman' }}" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image & Floating Badges Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Foto Banner Hero &amp; Kartu Melayang (Floating Badges)</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Upload gambar orang/banner hero di sebelah kanan dan sesuaikan teks kartu melayang di sekitarnya.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    
                    <!-- Upload Hero Photo -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700">Foto Model/Banner Hero</label>
                        <div class="p-4 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
                            @php $heroImg = !empty($settings['hero_home_image']) ? $settings['hero_home_image'] : '/orang.png'; @endphp
                            <img src="{{ $heroImg }}" alt="Hero banner" class="h-44 object-contain mb-3 rounded-xl bg-[#0A1E5E]/10 p-1">
                            <input type="file" name="hero_home_image_file" accept="image/*" class="text-[11px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#2563EB] hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Floating Badges Form -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Badge 1: Kepuasan Klien -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Floating Badge 1 (Kiri Atas)</p>
                            <input type="text" name="hero_home_badge_1_val" value="{{ $settings['hero_home_badge_1_val'] ?? '99%' }}" placeholder="99%" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                            <input type="text" name="hero_home_badge_1_label" value="{{ $settings['hero_home_badge_1_label'] ?? 'KEPUASAN KLIEN' }}" placeholder="KEPUASAN KLIEN" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <!-- Badge 2: Penilaian -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Floating Badge 2 (Kanan Atas)</p>
                            <input type="text" name="hero_home_badge_5_val" value="{{ $settings['hero_home_badge_5_val'] ?? '5.0 Penilaian' }}" placeholder="5.0 Penilaian" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                            <input type="text" name="hero_home_badge_5_label" value="{{ $settings['hero_home_badge_5_label'] ?? '100+ PROYEK SELESAI' }}" placeholder="100+ PROYEK SELESAI" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <!-- Badge 3: Pengerjaan Cepat -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Floating Badge 3 (Kiri Bawah)</p>
                            <input type="text" name="hero_home_badge_2_title" value="{{ $settings['hero_home_badge_2_title'] ?? 'Pengerjaan Cepat' }}" placeholder="Pengerjaan Cepat" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                            <input type="text" name="hero_home_badge_2_sub" value="{{ $settings['hero_home_badge_2_sub'] ?? 'KUALITAS PREMIUM' }}" placeholder="KUALITAS PREMIUM" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <!-- Badge 4: Dukungan 24/7 -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Floating Badge 4 (Kanan Tengah)</p>
                            <input type="text" name="hero_home_badge_3_title" value="{{ $settings['hero_home_badge_3_title'] ?? '24/7' }}" placeholder="24/7" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                            <input type="text" name="hero_home_badge_3_sub" value="{{ $settings['hero_home_badge_3_sub'] ?? 'DUKUNGAN SIAP BANTU' }}" placeholder="DUKUNGAN SIAP BANTU" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                        </div>

                        <!-- Badge 5: Mulai 499K -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-2 sm:col-span-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Floating Badge 5 (Kanan Bawah)</p>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="hero_home_badge_4_val" value="{{ $settings['hero_home_badge_4_val'] ?? 'Mulai 499K' }}" placeholder="Mulai 499K" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                                <input type="text" name="hero_home_badge_4_label" value="{{ $settings['hero_home_badge_4_label'] ?? 'SISTEM INFORMASI' }}" placeholder="SISTEM INFORMASI" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 outline-none">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 2: HERO LAYANAN (SERVICES) -->
        <div x-show="activeTab === 'services'" x-cloak class="space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="briefcase" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Hero Section — Halaman Solusi &amp; Layanan (/services)</span>
                    </h2>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Utama Hero Layanan</label>
                    <input 
                        type="text" 
                        name="hero_services_title" 
                        value="{{ $settings['hero_services_title'] ?? 'Pembuatan Website & Sistem Informasi' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Subjudul Hero Layanan</label>
                    <textarea 
                        name="hero_services_desc" 
                        rows="3"
                        class="w-full text-xs rounded-xl border border-slate-200 p-3.5 outline-none focus:border-[#2563EB] leading-relaxed"
                    >{{ $settings['hero_services_desc'] ?? 'Tingkatkan kehadiran digital bisnis Anda dengan website responsif, cepat, dan profesional yang dirancang khusus untuk mendatangkan lebih banyak konversi.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- TAB 3: HERO PORTOFOLIO -->
        <div x-show="activeTab === 'portfolio'" x-cloak class="space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="folder-kanban" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Hero Section — Halaman Portofolio (/portfolio)</span>
                    </h2>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Utama Hero Portofolio</label>
                    <input 
                        type="text" 
                        name="hero_portfolio_title" 
                        value="{{ $settings['hero_portfolio_title'] ?? 'Portofolio Proyek & Studi Kasus' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Subjudul Hero Portofolio</label>
                    <textarea 
                        name="hero_portfolio_desc" 
                        rows="3"
                        class="w-full text-xs rounded-xl border border-slate-200 p-3.5 outline-none focus:border-[#2563EB] leading-relaxed"
                    >{{ $settings['hero_portfolio_desc'] ?? 'Jelajahi berbagai proyek nyata yang telah kami bangun untuk beragam industri — mulai dari bisnis berkembang hingga perusahaan besar.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- TAB 4: HERO BLOG -->
        <div x-show="activeTab === 'blog'" x-cloak class="space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Hero Section — Halaman Blog &amp; Artikel (/blog)</span>
                    </h2>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Utama Hero Blog</label>
                    <input 
                        type="text" 
                        name="hero_blog_title" 
                        value="{{ $settings['hero_blog_title'] ?? 'Blog & Wawasan Digital' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Subjudul Hero Blog</label>
                    <textarea 
                        name="hero_blog_desc" 
                        rows="3"
                        class="w-full text-xs rounded-xl border border-slate-200 p-3.5 outline-none focus:border-[#2563EB] leading-relaxed"
                    >{{ $settings['hero_blog_desc'] ?? 'Temukan panduan praktis, tren teknologi terkini, strategi e-commerce, dan tips pengembangan aplikasi untuk mempercepat pertumbuhan bisnis Anda.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- TAB 5: HERO KONTAK -->
        <div x-show="activeTab === 'contact'" x-cloak class="space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Hero Section — Halaman Kontak (/contact)</span>
                    </h2>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Utama Hero Kontak</label>
                    <input 
                        type="text" 
                        name="hero_contact_title" 
                        value="{{ $settings['hero_contact_title'] ?? 'Mari Bangun Sesuatu yang Luar Biasa Bersama' }}" 
                        class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Subjudul Hero Kontak</label>
                    <textarea 
                        name="hero_contact_desc" 
                        rows="3"
                        class="w-full text-xs rounded-xl border border-slate-200 p-3.5 outline-none focus:border-[#2563EB] leading-relaxed"
                    >{{ $settings['hero_contact_desc'] ?? 'Memiliki ide proyek atau ingin bertanya mengenai layanan kami? Kirimkan pesan kepada kami atau hubungi langsung via WhatsApp.' }}</textarea>
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
                <span>Simpan Perubahan Hero Section</span>
            </button>
        </div>

    </form>
</div>
@endsection
