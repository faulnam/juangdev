@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai pembuatan website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
    $currentRoute = Route::currentRouteName();
    $isLightHeaderPage = in_array($currentRoute, ['blog.show']);
@endphp

<header 
    x-data="{ 
        scrolled: false, 
        mobileOpen: false 
    }" 
    x-init="
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 });
        $watch('mobileOpen', value => {
            if (value) { 
                document.body.style.overflow = 'hidden'; 
                $nextTick(() => { if (window.lucide) { lucide.createIcons(); } });
            } else { 
                document.body.style.overflow = ''; 
            }
        });
    "
    class="fixed top-0 left-0 right-0 z-50 w-full flex justify-center pointer-events-none"
>
    <!-- ==========================================
         1. DESKTOP NAVBAR (Visible on md: and above)
         ========================================== -->
    <div 
        :class="scrolled 
            ? 'w-[calc(100%-2rem)] max-w-[58rem] mt-4 rounded-2xl bg-white/95 backdrop-blur-xl border border-slate-200/80 shadow-xl shadow-black/5 h-16' 
            : '{{ $isLightHeaderPage ? 'max-w-7xl mt-0 rounded-none bg-white/90 backdrop-blur-md border-b border-slate-200/60 h-20 shadow-xs' : 'max-w-7xl mt-0 rounded-none bg-transparent border-transparent h-20' }}'"
        class="hidden md:flex pointer-events-auto transition-all duration-500 ease-in-out w-full px-6 lg:px-8 items-center justify-between mx-auto"
    >
        <!-- Logo (Desktop: Dynamic swap unscrolled logo3 <-> scrolled logo2) -->
        <a href="{{ route('home') }}" class="flex items-center group py-0.5" aria-label="JuangDev — Beranda">
            <div class="relative h-12 md:h-13 w-12 md:w-13 flex items-center justify-center shrink-0">
                <!-- Logo 3 (Belum di-scroll / Dark Hero) -->
                <img 
                    src="{{ asset('logo3.png') }}?v={{ filemtime(public_path('logo3.png')) }}" 
                    alt="JuangDev Logo" 
                    class="w-full h-full object-contain transition-all duration-300 transform group-hover:scale-105"
                    :class="scrolled ? 'opacity-0 scale-90 absolute pointer-events-none' : 'opacity-100 scale-100 relative'"
                >
                <!-- Logo 2 (Saat di-scroll / White bar) -->
                <img 
                    src="{{ asset('logo2.png') }}?v={{ filemtime(public_path('logo2.png')) }}" 
                    alt="JuangDev Logo" 
                    class="w-full h-full object-contain transition-all duration-300 transform group-hover:scale-105"
                    :class="scrolled ? 'opacity-100 scale-100 relative' : 'opacity-0 scale-90 absolute pointer-events-none'"
                >
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="flex items-center gap-1" aria-label="Main navigation">
            <a 
                href="{{ route('home') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'home' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'home' ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3.5 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Beranda
            </a>
            
            <!-- Layanan Dropdown -->
            <div 
                x-data="{ 
                    open: false,
                    timeout: null,
                    show() { clearTimeout(this.timeout); this.open = true; },
                    hide() { this.timeout = setTimeout(() => { this.open = false; }, 200); },
                    scrollTo(id) {
                        this.open = false;
                        if (window.location.pathname === '/' || window.location.pathname === '') {
                            const el = document.getElementById(id);
                            if (el) {
                                el.scrollIntoView({ behavior: 'smooth' });
                            }
                        }
                    }
                }"
                @mouseenter="show()"
                @mouseleave="hide()"
                @click.away="open = false"
                class="relative"
            >
                <button 
                    type="button"
                    @click="open = !open"
                    :class="scrolled 
                        ? '{{ in_array($currentRoute, ['services']) ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                        : '{{ in_array($currentRoute, ['services']) ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                    class="px-3.5 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-1.5 cursor-pointer focus:outline-none"
                    aria-expanded="open"
                >
                    <span>Layanan</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180 text-[#2563EB]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Compact Dropdown Popup -->
                <div 
                    x-show="open" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1.5 scale-98"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                    class="absolute left-0 top-full pt-2 z-50 w-52"
                >
                    <div class="bg-white rounded-xl p-1.5 border border-slate-200/90 shadow-lg shadow-slate-900/5 space-y-0.5">
                        <a 
                            href="{{ route('services') }}" 
                            @click="open = false"
                            class="flex flex-col px-3 py-2 rounded-lg text-slate-700 hover:text-slate-950 hover:bg-slate-100/80 transition-colors"
                        >
                            <span class="text-xs font-semibold">Semua Layanan</span>
                            <span class="text-[10px] text-slate-400 font-normal">Katalog &amp; Solusi Web</span>
                        </a>

                        <a 
                            href="{{ route('home') }}#pricing" 
                            @click="scrollTo('pricing')"
                            class="flex flex-col px-3 py-2 rounded-lg text-slate-700 hover:text-slate-950 hover:bg-slate-100/80 transition-colors"
                        >
                            <span class="text-xs font-semibold">Paket &amp; Harga</span>
                            <span class="text-[10px] text-slate-400 font-normal">Daftar Biaya &amp; Investasi</span>
                        </a>

                        <a 
                            href="{{ route('estimator') }}" 
                            @click="open = false"
                            class="flex flex-col px-3 py-2 rounded-lg text-slate-700 hover:text-slate-950 hover:bg-slate-100/80 transition-colors"
                        >
                            <span class="text-xs font-semibold">Estimator Biaya</span>
                            <span class="text-[10px] text-slate-400 font-normal">Simulasi Biaya Proyek</span>
                        </a>
                    </div>
                </div>
            </div>

            <a 
                href="{{ route('portfolio') }}" 
                :class="scrolled 
                    ? '{{ $currentRoute == 'portfolio' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'portfolio' ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? ($currentRoute == 'portfolio' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium') : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3.5 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Portofolio
            </a>

            <a 
                href="{{ route('blog') }}" 
                :class="scrolled 
                    ? '{{ str_starts_with($currentRoute ?? '', 'blog') ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ str_starts_with($currentRoute ?? '', 'blog') ? ($isLightHeaderPage ? 'text-white bg-[#0A1E5E]' : 'text-[#0A1E5E] bg-[#C7F236]') : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3.5 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Blog
            </a>

            <a 
                href="{{ route('contact') }}" 
                :class="scrolled 
                    ? '{{ $currentRoute == 'contact' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'contact' ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3.5 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Kontak
            </a>
        </nav>

        <!-- Desktop CTA -->
        <div>
            <a 
                href="{{ route('contact') }}" 
                :class="scrolled
                    ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] hover:bg-[#1a4fd4] hover:border-[#1a4fd4] shadow-md shadow-[#2563EB]/20'
                    : '{{ $isLightHeaderPage ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] hover:bg-[#1a4fd4]' : 'bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_20px_-5px_rgba(199,242,54,0.35)]' }}'"
                class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 group"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
            </a>
        </div>
    </div>

    <!-- ==========================================
         2. MOBILE FLOATING HAMBURGER BUTTON
         ========================================== -->
    <div class="md:hidden fixed top-4 right-4 z-50 pointer-events-auto">
        <button 
            @click="mobileOpen = !mobileOpen"
            :class="scrolled 
                ? 'bg-white/95 text-slate-900 shadow-lg shadow-slate-900/10 border border-slate-200/90 hover:bg-slate-50' 
                : '{{ $isLightHeaderPage ? 'bg-white/90 text-slate-900 shadow-sm border border-slate-200/80' : 'bg-white/15 text-white hover:bg-white/25 border border-white/20' }} backdrop-blur-md'"
            class="w-11 h-11 rounded-full flex items-center justify-center transition-all duration-300 active:scale-95 focus:outline-none cursor-pointer"
            aria-label="Buka Menu Navigasi"
        >
            <i x-show="!mobileOpen" data-lucide="menu" class="w-5 h-5 stroke-[2.2]"></i>
            <i x-show="mobileOpen" x-cloak data-lucide="x" class="w-5 h-5 stroke-[2.2]"></i>
        </button>
    </div>

    <!-- ==========================================
         3. MOBILE SLIDE-OVER DRAWER
         ========================================== -->
    <!-- Backdrop Overlay -->
    <div 
        x-show="mobileOpen" 
        x-cloak
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="pointer-events-auto fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-[60] md:hidden"
    ></div>

    <!-- Slide-over Drawer Panel -->
    <div 
        x-show="mobileOpen" 
        x-cloak
        x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="pointer-events-auto fixed top-0 right-0 bottom-0 w-[84vw] max-w-[320px] bg-white text-slate-800 shadow-2xl z-[70] flex flex-col justify-between overflow-y-auto border-l border-slate-200/80 md:hidden"
            <!-- Top Drawer Header: Hero Pattern Background -->
        <div class="relative bg-[#0A1E5E] text-white px-5 py-3.5 shrink-0 overflow-hidden border-b border-white/10">
            <!-- Hero Grid Pattern (Kotak-Kotak seperti Hero Section) -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff10_1px,transparent_1px),linear-gradient(to_bottom,#ffffff10_1px,transparent_1px)] bg-[size:1.25rem_1.25rem] pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between">
                <a href="{{ route('home') }}" @click="mobileOpen = false" class="flex items-center group py-0.5" aria-label="JuangDev">
                    <img 
                        src="{{ asset('logo3.png') }}?v={{ filemtime(public_path('logo3.png')) }}" 
                        alt="JuangDev" 
                        class="h-7 w-auto object-contain transition-transform group-hover:scale-105"
                    >
                </a>

                <button 
                    @click="mobileOpen = false"
                    class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer border border-white/15 active:scale-95"
                    aria-label="Tutup Menu"
                >
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>

        <!-- Middle Drawer Body: Modern Card Navigation -->
        <div class="flex-1 p-4 space-y-2 overflow-y-auto bg-slate-50/70">
            <!-- Beranda -->
            <a 
                href="{{ route('home') }}" 
                @click="mobileOpen = false" 
                class="flex items-center p-3.5 rounded-2xl transition-all {{ $currentRoute == 'home' ? 'bg-white text-slate-900 font-bold shadow-sm border border-slate-200 ring-1 ring-slate-900/5' : 'bg-white/80 hover:bg-white text-slate-700 hover:text-slate-950 font-semibold border border-slate-200/60 hover:border-slate-300 shadow-2xs hover:shadow-xs' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full {{ $currentRoute == 'home' ? 'bg-[#0A1E5E]' : 'bg-slate-300' }}"></span>
                    <span class="text-sm">Beranda</span>
                </div>
            </a>

            <!-- Layanan Accordion (Satu-satunya dengan tanda panah dropdown) -->
            <div x-data="{ openServices: {{ in_array($currentRoute, ['services', 'estimator']) ? 'true' : 'false' }} }" class="flex flex-col">
                <button 
                    type="button" 
                    @click="openServices = !openServices"
                    class="flex items-center justify-between p-3.5 rounded-2xl w-full transition-all {{ in_array($currentRoute, ['services', 'estimator']) ? 'bg-white text-slate-900 font-bold shadow-sm border border-slate-200 ring-1 ring-slate-900/5' : 'bg-white/80 hover:bg-white text-slate-700 hover:text-slate-950 font-semibold border border-slate-200/60 hover:border-slate-300 shadow-2xs hover:shadow-xs' }}"
                >
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ in_array($currentRoute, ['services', 'estimator']) ? 'bg-[#0A1E5E]' : 'bg-slate-300' }}"></span>
                        <span class="text-sm">Layanan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 {{ in_array($currentRoute, ['services', 'estimator']) ? 'text-slate-900' : 'text-slate-400' }}" :class="openServices ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Submenu Layanan (Card Submenu) -->
                <div x-show="openServices" x-cloak class="mt-1.5 p-2 bg-slate-100/80 rounded-2xl border border-slate-200/70 space-y-1">
                    <a 
                        href="{{ route('services') }}" 
                        @click="mobileOpen = false" 
                        class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ $currentRoute == 'services' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-white/70' }}"
                    >
                        <span>Semua Layanan</span>
                        <span class="text-[10px] text-slate-400 font-normal">Katalog</span>
                    </a>
                    <a 
                        href="{{ route('home') }}#pricing" 
                        @click="mobileOpen = false" 
                        class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-950 hover:bg-white/70 transition-all"
                    >
                        <span>Paket &amp; Harga</span>
                        <span class="text-[10px] text-slate-400 font-normal">Biaya</span>
                    </a>
                    <a 
                        href="{{ route('estimator') }}" 
                        @click="mobileOpen = false" 
                        class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ $currentRoute == 'estimator' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-white/70' }}"
                    >
                        <span>Estimator Biaya</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Simulasi</span>
                    </a>
                </div>
            </div>

            <!-- Portofolio -->
            <a 
                href="{{ route('portfolio') }}" 
                @click="mobileOpen = false" 
                class="flex items-center p-3.5 rounded-2xl transition-all {{ $currentRoute == 'portfolio' ? 'bg-white text-slate-900 font-bold shadow-sm border border-slate-200 ring-1 ring-slate-900/5' : 'bg-white/80 hover:bg-white text-slate-700 hover:text-slate-950 font-semibold border border-slate-200/60 hover:border-slate-300 shadow-2xs hover:shadow-xs' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full {{ $currentRoute == 'portfolio' ? 'bg-[#0A1E5E]' : 'bg-slate-300' }}"></span>
                    <span class="text-sm">Portofolio</span>
                </div>
            </a>

            <!-- Blog -->
            <a 
                href="{{ route('blog') }}" 
                @click="mobileOpen = false" 
                class="flex items-center p-3.5 rounded-2xl transition-all {{ str_starts_with($currentRoute ?? '', 'blog') ? 'bg-white text-slate-900 font-bold shadow-sm border border-slate-200 ring-1 ring-slate-900/5' : 'bg-white/80 hover:bg-white text-slate-700 hover:text-slate-950 font-semibold border border-slate-200/60 hover:border-slate-300 shadow-2xs hover:shadow-xs' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full {{ str_starts_with($currentRoute ?? '', 'blog') ? 'bg-[#0A1E5E]' : 'bg-slate-300' }}"></span>
                    <span class="text-sm">Blog &amp; Artikel</span>
                </div>
            </a>

            <!-- Kontak -->
            <a 
                href="{{ route('contact') }}" 
                @click="mobileOpen = false" 
                class="flex items-center p-3.5 rounded-2xl transition-all {{ $currentRoute == 'contact' ? 'bg-white text-slate-900 font-bold shadow-sm border border-slate-200 ring-1 ring-slate-900/5' : 'bg-white/80 hover:bg-white text-slate-700 hover:text-slate-950 font-semibold border border-slate-200/60 hover:border-slate-300 shadow-2xs hover:shadow-xs' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full {{ $currentRoute == 'contact' ? 'bg-[#0A1E5E]' : 'bg-slate-300' }}"></span>
                    <span class="text-sm">Hubungi Kami</span>
                </div>
            </a>
        </div>

        <!-- Bottom Drawer Footer: Clean CTA & Info -->
        <div class="p-4 sm:p-5 border-t border-slate-200/80 bg-white shrink-0 space-y-3">
            <a 
                href="{{ $whatsappUrl }}" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="w-full inline-flex items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold bg-[#2563EB] hover:bg-[#1d4ed8] text-white shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all duration-200 group"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
            </a>

            <div class="text-center text-[11px] text-slate-500 space-y-0.5 pt-0.5">
                <p class="font-semibold text-slate-700">{{ $settings['phone'] ?? '+62 838-5217-4877' }}</p>
                <p class="text-slate-400">{{ $settings['working_hours'] ?? 'Senin - Sabtu: 09:00 - 18:00 WIB' }}</p>
            </div>
        </div>
    </div>
</header>
