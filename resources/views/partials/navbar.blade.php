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
            if (value) { document.body.style.overflow = 'hidden'; } 
            else { document.body.style.overflow = ''; }
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
                            href="{{ route('home') }}#estimator"
                            @click="scrollTo('estimator')"
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
         (Logo is hidden outside on mobile, only hamburger is shown.
          Transparent initially, white rounded bg when scrolled)
         ========================================== -->
    <div class="md:hidden fixed top-4 right-4 z-50 pointer-events-auto">
        <button 
            @click="mobileOpen = !mobileOpen"
            :class="scrolled 
                ? 'bg-white/95 text-slate-900 shadow-xl border border-slate-200/90 hover:bg-slate-50' 
                : '{{ $isLightHeaderPage ? 'bg-white/90 text-slate-900 shadow-md border border-slate-200/80' : 'bg-white/15 text-white hover:bg-white/25 border border-white/20' }} backdrop-blur-md'"
            class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 active:scale-95 focus:outline-none cursor-pointer"
            aria-label="Buka Menu Navigasi"
        >
            <i x-show="!mobileOpen" data-lucide="menu" class="w-6 h-6 stroke-[2.5]"></i>
            <i x-show="mobileOpen" x-cloak data-lucide="x" class="w-6 h-6 stroke-[2.5]"></i>
        </button>
    </div>

    <!-- ==========================================
         3. MOBILE SLIDE-OVER DRAWER (From Right to Left)
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
        class="pointer-events-auto fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 md:hidden"
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
        class="pointer-events-auto fixed top-0 right-0 bottom-0 w-[84vw] max-w-[340px] bg-[#071542] text-white shadow-2xl z-50 flex flex-col justify-between overflow-y-auto border-l border-white/10 md:hidden"
    >
        <!-- Top Drawer Header: Logo & Close Button -->
        <div class="p-5 pb-4 border-b border-white/10 flex items-center justify-between shrink-0 bg-[#0A1E5E]/80 backdrop-blur-md">
            <a href="{{ route('home') }}" @click="mobileOpen = false" class="flex items-center group" aria-label="JuangDev">
                <img 
                    src="{{ asset('logo4.png') }}?v={{ filemtime(public_path('logo4.png')) }}" 
                    alt="JuangDev" 
                    class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105"
                >
            </a>

            <button 
                @click="mobileOpen = false"
                class="w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer"
                aria-label="Tutup Menu"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Middle Drawer Body: Clean Typography Navigation -->
        <div class="flex-1 px-6 py-6 space-y-1 overflow-y-auto">
            <!-- Beranda -->
            <a 
                href="{{ route('home') }}" 
                @click="mobileOpen = false" 
                class="flex items-center justify-between py-3.5 text-lg font-bold transition-all {{ $currentRoute == 'home' ? 'text-[#C7F236]' : 'text-white/80 hover:text-white' }}"
            >
                <span>Beranda</span>
                @if($currentRoute == 'home')
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C7F236]"></span>
                @endif
            </a>

            <!-- Layanan Accordion -->
            <div x-data="{ openServices: {{ in_array($currentRoute, ['services']) ? 'true' : 'false' }} }" class="flex flex-col border-y border-white/5 py-1">
                <button 
                    type="button" 
                    @click="openServices = !openServices"
                    class="flex items-center justify-between w-full py-3 text-lg font-bold transition-all {{ in_array($currentRoute, ['services']) ? 'text-[#C7F236]' : 'text-white/80 hover:text-white' }}"
                >
                    <span>Layanan</span>
                    <svg class="w-4 h-4 text-white/50 transition-transform duration-200" :class="openServices ? 'rotate-180 text-[#C7F236]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="openServices" x-cloak class="pl-4 pb-2 pt-1 flex flex-col gap-2 space-y-1">
                    <a 
                        href="{{ route('services') }}" 
                        @click="mobileOpen = false" 
                        class="text-sm font-medium text-white/70 hover:text-[#C7F236] transition-colors py-1 block"
                    >
                        Semua Layanan
                    </a>
                    <a 
                        href="{{ route('home') }}#pricing" 
                        @click="mobileOpen = false" 
                        class="text-sm font-medium text-white/70 hover:text-[#C7F236] transition-colors py-1 block"
                    >
                        Paket &amp; Harga
                    </a>
                    <a 
                        href="{{ route('home') }}#estimator" 
                        @click="mobileOpen = false" 
                        class="text-sm font-medium text-white/70 hover:text-[#C7F236] transition-colors py-1 block"
                    >
                        Estimator Biaya
                    </a>
                </div>
            </div>

            <!-- Portofolio -->
            <a 
                href="{{ route('portfolio') }}" 
                @click="mobileOpen = false"
                class="flex items-center justify-between py-3.5 text-lg font-bold transition-all {{ $currentRoute == 'portfolio' ? 'text-[#C7F236]' : 'text-white/80 hover:text-white' }}"
            >
                <span>Portofolio</span>
                @if($currentRoute == 'portfolio')
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C7F236]"></span>
                @endif
            </a>

            <!-- Blog -->
            <a 
                href="{{ route('blog') }}" 
                @click="mobileOpen = false"
                class="flex items-center justify-between py-3.5 text-lg font-bold transition-all {{ str_starts_with($currentRoute ?? '', 'blog') ? 'text-[#C7F236]' : 'text-white/80 hover:text-white' }}"
            >
                <span>Blog &amp; Artikel</span>
                @if(str_starts_with($currentRoute ?? '', 'blog'))
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C7F236]"></span>
                @endif
            </a>

            <!-- Kontak -->
            <a 
                href="{{ route('contact') }}" 
                @click="mobileOpen = false"
                class="flex items-center justify-between py-3.5 text-lg font-bold transition-all {{ $currentRoute == 'contact' ? 'text-[#C7F236]' : 'text-white/80 hover:text-white' }}"
            >
                <span>Hubungi Kami</span>
                @if($currentRoute == 'contact')
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C7F236]"></span>
                @endif
            </a>
        </div>

        <!-- Bottom Drawer Footer: WhatsApp CTA & Info -->
        <div class="p-6 border-t border-white/10 bg-black/20 shrink-0 space-y-4">
            <a 
                href="{{ $whatsappUrl }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-2 rounded-full py-3.5 px-5 text-sm font-bold bg-[#C7F236] text-[#0A1E5E] hover:bg-[#b5dd2a] shadow-lg shadow-[#C7F236]/20 transition-all duration-200"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>

            <div class="text-center text-xs text-white/50 space-y-1 pt-1">
                <p class="font-medium text-white/70">{{ $settings['phone'] ?? '+62 838-5217-4877' }}</p>
                <p>{{ $settings['working_hours'] ?? 'Senin - Sabtu: 09:00 - 18:00 WIB' }}</p>
            </div>
        </div>
    </div>
</header>
