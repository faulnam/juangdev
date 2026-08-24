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
    <div 
        :class="scrolled 
            ? 'max-w-[58rem] mt-4 rounded-2xl bg-white/95 backdrop-blur-xl border border-slate-200/80 shadow-xl shadow-black/5 h-14 md:h-16' 
            : '{{ $isLightHeaderPage ? 'max-w-7xl mt-0 rounded-none bg-white/90 backdrop-blur-md border-b border-slate-200/60 h-16 md:h-20 shadow-xs' : 'max-w-7xl mt-0 rounded-none bg-transparent border-transparent h-16 md:h-20' }}'"
        class="pointer-events-auto transition-all duration-500 ease-in-out w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between mx-auto"
    >
        <!-- Logo (Only Icon, Enlarged & Dynamic) -->
        <a href="{{ route('home') }}" class="flex items-center group py-0.5" aria-label="JuangDev — Beranda">
            <div class="relative h-11 sm:h-12 md:h-13 w-11 sm:w-12 md:w-13 flex items-center justify-center shrink-0">
                <!-- Logo 3 (Belum di-scroll) -->
                <img 
                    src="{{ asset('logo3.png') }}?v={{ filemtime(public_path('logo3.png')) }}" 
                    alt="JuangDev Logo" 
                    class="w-full h-full object-contain transition-all duration-300 transform group-hover:scale-105"
                    :class="scrolled ? 'opacity-0 scale-90 absolute pointer-events-none' : 'opacity-100 scale-100 relative'"
                >
                <!-- Logo 2 (Saat di-scroll) -->
                <img 
                    src="{{ asset('logo2.png') }}?v={{ filemtime(public_path('logo2.png')) }}" 
                    alt="JuangDev Logo" 
                    class="w-full h-full object-contain transition-all duration-300 transform group-hover:scale-105"
                    :class="scrolled ? 'opacity-100 scale-100 relative' : 'opacity-0 scale-90 absolute pointer-events-none'"
                >
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-1" aria-label="Main navigation">
            <a 
                href="{{ route('home') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'home' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'home' ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Beranda
            </a>
            
            <!-- Layanan Dropdown (Simple & Compact) -->
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
                    class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-1.5 cursor-pointer focus:outline-none"
                    aria-expanded="open"
                >
                    <span>Layanan</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180 text-[#2563EB]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Compact Dropdown Popup (Clean, Formal & Minimalist) -->
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
                        <!-- Menu 1: Semua Layanan -->
                        <a 
                            href="{{ route('services') }}"
                            @click="open = false"
                            class="flex flex-col px-3 py-2 rounded-lg text-slate-700 hover:text-slate-950 hover:bg-slate-100/80 transition-colors"
                        >
                            <span class="text-xs font-semibold">Semua Layanan</span>
                            <span class="text-[10px] text-slate-400 font-normal">Katalog &amp; Solusi Web</span>
                        </a>

                        <!-- Menu 2: Paket & Harga -->
                        <a 
                            href="{{ route('home') }}#pricing"
                            @click="scrollTo('pricing')"
                            class="flex flex-col px-3 py-2 rounded-lg text-slate-700 hover:text-slate-950 hover:bg-slate-100/80 transition-colors"
                        >
                            <span class="text-xs font-semibold">Paket &amp; Harga</span>
                            <span class="text-[10px] text-slate-400 font-normal">Daftar Biaya &amp; Investasi</span>
                        </a>

                        <!-- Menu 3: Estimator Biaya -->
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
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Portofolio
            </a>

            <a 
                href="{{ route('blog') }}" 
                :class="scrolled 
                    ? '{{ str_starts_with($currentRoute ?? '', 'blog') ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ str_starts_with($currentRoute ?? '', 'blog') ? ($isLightHeaderPage ? 'text-white bg-[#0A1E5E]' : 'text-[#0A1E5E] bg-[#C7F236]') : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Blog
            </a>

            <a 
                href="{{ route('contact') }}" 
                :class="scrolled 
                    ? '{{ $currentRoute == 'contact' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'contact' ? 'text-[#0A1E5E] bg-[#C7F236]' : ($isLightHeaderPage ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium') }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Kontak
            </a>
        </nav>

        <!-- Desktop CTA -->
        <div class="hidden md:block">
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

        <!-- Mobile Toggle Button -->
        <button 
            @click="mobileOpen = !mobileOpen"
            :class="scrolled ? 'text-slate-800 hover:bg-slate-100' : '{{ $isLightHeaderPage ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/10' }}'"
            class="md:hidden p-2 rounded-lg transition-colors focus:outline-none"
            aria-label="Toggle menu"
        >
            <i x-show="!mobileOpen" data-lucide="menu" class="w-6 h-6"></i>
            <i x-show="mobileOpen" x-cloak data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div 
        x-show="mobileOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="pointer-events-auto fixed inset-0 top-16 bg-[#0A1E5E]/95 backdrop-blur-xl z-40 md:hidden overflow-y-auto"
    >
        <nav class="flex flex-col gap-2 p-6">
            <a 
                href="{{ route('home') }}" 
                @click="mobileOpen = false" 
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'home' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Beranda
            </a>

            <!-- Mobile Layanan Accordion -->
            <div x-data="{ openServices: false }" class="flex flex-col">
                <button 
                    type="button" 
                    @click="openServices = !openServices"
                    class="px-4 py-3 rounded-xl text-lg font-bold transition-all flex items-center justify-between {{ $currentRoute == 'services' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
                >
                    <span>Layanan</span>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="openServices ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openServices" x-cloak class="pl-4 pr-2 py-1.5 flex flex-col gap-1 bg-black/20 rounded-xl mt-1">
                    <a 
                        href="{{ route('services') }}" 
                        @click="mobileOpen = false" 
                        class="px-3 py-2 text-sm font-semibold text-white/90 hover:text-[#C7F236]"
                    >
                        Semua Layanan
                    </a>
                    <a 
                        href="{{ route('home') }}#pricing" 
                        @click="mobileOpen = false" 
                        class="px-3 py-2 text-sm font-semibold text-white/90 hover:text-[#C7F236]"
                    >
                        Paket &amp; Harga (Pricing)
                    </a>
                    <a 
                        href="{{ route('home') }}#estimator" 
                        @click="mobileOpen = false" 
                        class="px-3 py-2 text-sm font-semibold text-white/90 hover:text-[#C7F236]"
                    >
                        Estimator Biaya
                    </a>
                </div>
            </div>

            <a 
                href="{{ route('portfolio') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'portfolio' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Portofolio
            </a>
            <a 
                href="{{ route('blog') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ str_starts_with($currentRoute ?? '', 'blog') ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Blog
            </a>
            <a 
                href="{{ route('contact') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'contact' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Kontak
            </a>
            
            <a 
                href="{{ route('contact') }}" 
                @click="mobileOpen = false"
                class="mt-4 inline-flex items-center justify-center gap-2 rounded-full px-6 py-3.5 text-base font-semibold bg-[#C7F236] text-[#0A1E5E] shadow-lg"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
            </a>
        </nav>
    </div>
</header>
