@extends('layouts.app')

@section('title', 'Solusi & Layanan — JuangDev')
@section('meta_description', 'Kami menghadirkan solusi teknologi digital end-to-end untuk membantu bisnis Anda berkembang lebih cepat di era digital.')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo Tim JuangDev, saya ingin konsultasi kebutuhan pembuatan website/sistem.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $serviceTabs = [
        [
            'id' => 'landing-page',
            'title' => 'Landing Page',
            'desc' => 'Website satu halaman yang dirancang khusus untuk fokus pada satu tujuan: konversi. Sangat cocok untuk kampanye iklan, peluncuran produk, atau pendaftaran event.',
            'price' => 'Rp 99.000',
            'items' => [
                'Gratis Domain & Hosting 1 Tahun',
                'Desain Premium & Mobile Responsive',
                'Optimasi Kecepatan & Basic SEO',
                'Integrasi WhatsApp & Social Media',
                'Copywriting Persuasif',
                'Garansi Support & Maintenance',
            ]
        ],
        [
            'id' => 'company-profile',
            'title' => 'Company Profile',
            'desc' => 'Website profesional dan elegan untuk membangun kredibilitas serta menampilkan identitas, portofolio, dan keunggulan bisnis Anda ke publik.',
            'price' => 'Rp 199.000',
            'items' => [
                'Hingga 5 Halaman Konten',
                'Desain Eksklusif Brand Identity',
                'Galeri Portofolio & Layanan',
                'Form Kontak & Integrasi Google Maps',
                'Setup Domain & Email Perusahaan',
                'Garansi & Maintenance Berkala',
            ]
        ],
        [
            'id' => 'e-commerce',
            'title' => 'E-Commerce',
            'desc' => 'Toko online modern dengan sistem belanja terstruktur, lengkap dengan katalog produk, keranjang belanja, dan integrasi checkout instan.',
            'price' => 'Rp 499.000',
            'items' => [
                'Manajemen Produk & Stok Otomatis',
                'Checkout WhatsApp & Payment Gateway',
                'Perhitungan Ongkir Otomatis',
                'Dashboard Laporan Penjualan',
                'Notifikasi Pesanan Real-time',
                'Panduan Penggunaan & Support Penuh',
            ]
        ],
        [
            'id' => 'sistem-informasi',
            'title' => 'Sistem Informasi',
            'desc' => 'Sistem digitalisasi pendataan dan pelaporan untuk mempermudah operasional internal, inventaris, dan efisiensi alur kerja perusahaan Anda.',
            'price' => 'Rp 399.000',
            'items' => [
                'Arsitektur Database Terpadu',
                'Role & Hak Akses Multi-Level',
                'Dashboard Visual & Export PDF/Excel',
                'Backup Data Otomatis & Keamanan SSL',
                'Integrasi API & Notifikasi',
                'Pelatihan Tim & Garansi Teknis',
            ]
        ],
        [
            'id' => 'custom-web-app',
            'title' => 'Custom Web App',
            'desc' => 'Pengembangan aplikasi web kustom fleksibel yang dapat menggabungkan fitur dari seluruh layanan (Landing Page, Compro, E-Commerce, Sistem Informasi) sesuai kebutuhan unik bisnis Anda.',
            'price' => 'Mulai Rp 199.000',
            'items' => [
                'Desain UI/UX Custom Eksklusif',
                'Custom Business Logic & Webhook',
                'Skalabilitas Tinggi & Keamanan Ketat',
                'Full Source Code & Hak Milik Anda',
                'SLA Uptime & Dedicated Support',
                'Garansi Bug Fix & Maintenance',
            ]
        ],
    ];
@endphp

@section('content')
    <!-- 1. Hero Section -->
    <section 
        class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden text-white bg-[#071542]"
        style="background: linear-gradient(160deg, #071542 0%, #0A1E5E 50%, #122d78 100%);"
    >
        <!-- Decorative subtle grid background & right glow lighting effect -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
        <div class="absolute -top-24 right-0 w-96 h-96 rounded-full bg-[#2563EB]/25 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-blue-500/15 blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 text-center relative z-10">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight mb-4">
                Solusi &amp; <span class="font-serif italic text-[#C7F236]">Layanan Kami</span>
            </h1>
            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                Kami menghadirkan solusi teknologi digital end-to-end untuk membantu bisnis Anda berkembang lebih cepat di era digital.
            </p>
        </div>
    </section>

    <!-- 2. Interactive Showcase Section: Pembuatan Website & Sistem Informasi -->
    <section class="py-16 md:py-24 bg-white relative">
        <div 
            class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8"
            x-data="{
                activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'landing-page',
                tabs: {{ json_encode($serviceTabs) }},
                get currentTab() {
                    return this.tabs.find(t => t.id === this.activeTab) || this.tabs[0];
                },
                getWhatsappUrl(title) {
                    const msg = encodeURIComponent(`Halo Tim JuangDev, saya tertarik dengan paket ${title}. Boleh minta info detailnya?`);
                    return `https://wa.me/{{ $whatsappNumber }}?text=${msg}`;
                },
                selectEstimator(tabId) {
                    window.dispatchEvent(new CustomEvent('select-estimate-plan', { 
                        detail: { category: tabId } 
                    }));
                    const el = document.getElementById('estimator');
                    if (el) el.scrollIntoView({ behavior: 'smooth' });
                }
            }"
        >
            <!-- Section Header -->
            <div class="max-w-3xl mb-10">
                <h2 class="text-3xl md:text-4xl lg:text-[2.6rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-3">
                    {{ $settings['hero_services_title'] ?? 'Pembuatan Website & Sistem Informasi' }}
                </h2>
                <p class="text-slate-600 text-[0.95rem] md:text-base leading-relaxed font-medium">
                    {{ $settings['hero_services_desc'] ?? 'Tingkatkan kehadiran digital bisnis Anda dengan website responsif, cepat, dan profesional yang dirancang khusus untuk mendatangkan lebih banyak konversi.' }}
                </p>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex flex-wrap gap-2.5 mb-8">
                <template x-for="tab in tabs" :key="tab.id">
                    <button 
                        type="button"
                        @click="activeTab = tab.id"
                        :class="activeTab === tab.id 
                            ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/25' 
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200"
                        x-text="tab.title"
                    >
                    </button>
                </template>
            </div>

            <!-- Active Tab Content Card (Dark Navy with Dual Column) -->
            <div class="rounded-[2rem] bg-[#0A1E5E] text-white p-7 sm:p-10 lg:p-12 shadow-2xl relative overflow-hidden border border-blue-900/50">
                <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] gap-8 lg:gap-12 items-center relative z-10">
                    
                    <!-- Left: Title, Description, Price, and Buttons -->
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-[#C7F236] mb-5">
                            <i data-lucide="layers" class="w-6 h-6"></i>
                        </div>

                        <h3 class="text-2xl sm:text-3xl font-black text-white mb-3" x-text="currentTab.title"></h3>
                        
                        <p class="text-white/80 text-sm sm:text-base leading-relaxed font-medium mb-8" x-text="currentTab.desc"></p>

                        <div class="mb-8">
                            <p class="text-xs text-white/60 uppercase tracking-wider font-semibold mb-1">Mulai Dari</p>
                            <p class="text-3xl sm:text-4xl font-black text-[#C7F236]" x-text="currentTab.price"></p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3.5">
                            <a 
                                :href="'{{ route('home') }}?category=' + currentTab.id + '#pricing'"
                                class="inline-flex items-center justify-center gap-2 bg-[#C7F236] text-[#0A1E5E] font-bold text-sm px-6 py-3 rounded-xl hover:bg-[#b5dd2a] transition-all duration-200 shadow-lg"
                            >
                                <span>Mulai Proyek Ini</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 stroke-[2.5]"></i>
                            </a>

                            <a 
                                href="{{ route('portfolio') }}" 
                                class="inline-flex items-center justify-center gap-2 bg-white/10 text-white hover:bg-white/20 border border-white/20 font-bold text-sm px-6 py-3 rounded-xl transition-all duration-200"
                            >
                                <span>Lihat Projectnya</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 stroke-[2.5]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Checklist Box "Yang Anda Dapatkan:" -->
                    <div class="bg-[#071542] rounded-2xl p-6 sm:p-8 border border-white/10 shadow-inner">
                        <h4 class="text-base sm:text-lg font-black text-white mb-5 flex items-center gap-2">
                            <span class="text-[#C7F236]">Yang Anda Dapatkan:</span>
                        </h4>

                        <div class="space-y-3.5">
                            <template x-for="(item, idx) in currentTab.items" :key="idx">
                                <div class="flex items-start gap-3 text-xs sm:text-sm text-white/90">
                                    <div class="w-5 h-5 rounded-full bg-[#C7F236]/20 text-[#C7F236] flex items-center justify-center shrink-0 mt-0.5 font-bold text-xs">
                                        ✓
                                    </div>
                                    <span class="font-medium" x-text="item"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 3. Feature Showcase Section (Dapat diatur penuh via Admin Settings) -->
    <section class="py-16 md:py-24 bg-[#f8f9fc] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            <div class="rounded-[2rem] bg-[#0A1E5E] text-white p-7 sm:p-10 lg:p-12 shadow-2xl relative overflow-hidden border border-blue-900/50">
                <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1.1fr] gap-10 lg:gap-14 items-center relative z-10">
                    
                    <!-- Left: Header, 4 Value Points, Pricing & Button -->
                    <div>
                        <span class="text-[11px] uppercase font-bold tracking-wider text-[#C7F236] mb-2 inline-block">
                            {{ $settings['feature_showcase_badge'] ?? 'Enterprise & Custom Build' }}
                        </span>
                        
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-3">
                            {{ $settings['feature_showcase_title'] ?? 'Pengembangan Website & Sistem Modern' }}
                        </h2>
                        
                        <p class="text-white/80 text-sm leading-relaxed mb-6 font-medium">
                            {{ $settings['feature_showcase_desc'] ?? 'Solusi website dan aplikasi web internal yang cepat, aman, responsif di seluruh perangkat, dan mudah dikelola.' }}
                        </p>

                        <!-- 4 Clean Value Points -->
                        <div class="space-y-3.5 mb-8">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5 text-xs font-black">
                                    1
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-0.5">{{ $settings['feature_showcase_point1_title'] ?? 'Antarmuka User-Friendly & Responsif' }}</h4>
                                    <p class="text-xs text-white/70">{{ $settings['feature_showcase_point1_desc'] ?? 'Desain UI/UX intuitif yang optimal di layar laptop maupun smartphone.' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5 text-xs font-black">
                                    2
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-0.5">{{ $settings['feature_showcase_point2_title'] ?? 'Performa Cepat & Stabil' }}</h4>
                                    <p class="text-xs text-white/70">{{ $settings['feature_showcase_point2_desc'] ?? 'Arsitektur modern dan efisien untuk menjamin sistem berjalan mulus tanpa lag.' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5 text-xs font-black">
                                    3
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-0.5">{{ $settings['feature_showcase_point3_title'] ?? 'Keamanan Data Terjamin' }}</h4>
                                    <p class="text-xs text-white/70">{{ $settings['feature_showcase_point3_desc'] ?? 'Enkripsi SSL dan proteksi data berlapis untuk menjaga transaksi bisnis Anda.' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5 text-xs font-black">
                                    4
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-0.5">{{ $settings['feature_showcase_point4_title'] ?? 'Dashboard & Manajemen Terintegrasi' }}</h4>
                                    <p class="text-xs text-white/70">{{ $settings['feature_showcase_point4_desc'] ?? 'Kemudahan mengelola konten dan melihat laporan performa secara terpusat.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pt-5 border-t border-white/15">
                            <div>
                                <p class="text-xs text-white/60 font-medium">Estimasi Biaya Mulai</p>
                                <p class="text-2xl sm:text-3xl font-black text-[#C7F236]">{{ $settings['feature_showcase_price'] ?? 'Rp 999.000' }}</p>
                            </div>

                            <a 
                                href="{{ route('home') }}?category=custom-app#pricing" 
                                class="inline-flex items-center justify-center gap-2 bg-[#C7F236] text-[#0A1E5E] font-bold text-sm px-6 py-3 rounded-xl hover:bg-[#b5dd2a] transition-all duration-200 shadow-lg"
                            >
                                <span>Mulai Proyek Ini</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 stroke-[2.5]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Realistic Laptop Website Mockup -->
                    <div class="relative flex items-center justify-center">
                        <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl border border-white/15 bg-black/40 group">
                            <img 
                                src="{{ $settings['feature_showcase_image'] ?? '/services-laptop.jpg' }}" 
                                alt="Modern Dashboard Website on Laptop Mockup" 
                                class="w-full h-auto object-cover object-center transition-transform duration-500 group-hover:scale-105"
                            >
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 4. Pertanyaan yang Sering Diajukan (FAQ Accordion) -->
    @include('partials.faq')

    <!-- 5. Siap Meluncurkan Proyek Impian Anda? (Final CTA Banner diletakkan di bawah FAQ) -->
    @include('partials.final-cta')
@endsection
