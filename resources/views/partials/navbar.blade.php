@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai pembuatan website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
    $currentRoute = Route::currentRouteName();
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
            : 'max-w-7xl mt-0 rounded-none bg-transparent border-transparent h-16 md:h-20'"
        class="pointer-events-auto transition-all duration-500 ease-in-out w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between mx-auto"
    >
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center group" aria-label="JuangDev — Beranda">
            <span 
                :class="scrolled ? 'text-slate-900' : 'text-white'"
                class="text-xl md:text-2xl font-serif font-bold tracking-tight transition-colors duration-300 flex items-center gap-1.5"
            >
                <span>Juang</span><span class="text-[#C7F236]">Dev</span>
            </span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-1" aria-label="Main navigation">
            <a 
                href="{{ route('home') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'home' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'home' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium' }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Beranda
            </a>
            
            <a 
                href="{{ route('services') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'services' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'services' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium' }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Layanan
            </a>

            <a 
                href="{{ route('portfolio') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'portfolio' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'portfolio' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium' }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Portofolio
            </a>

            <a 
                href="{{ route('home') }}#pricing"
                :class="scrolled 
                    ? 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' 
                    : 'text-white/85 hover:text-white hover:bg-white/10 font-medium'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Paket Harga
            </a>

            <a 
                href="{{ route('contact') }}"
                :class="scrolled 
                    ? '{{ $currentRoute == 'contact' ? 'text-white bg-[#0A1E5E]' : 'text-slate-600 hover:text-[#2563EB] hover:bg-slate-100 font-medium' }}' 
                    : '{{ $currentRoute == 'contact' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium' }}'"
                class="px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300"
            >
                Kontak
            </a>
        </nav>

        <!-- Desktop CTA -->
        <div class="hidden md:block">
            <a 
                href="{{ $whatsappUrl }}" 
                target="_blank" 
                rel="noopener noreferrer"
                :class="scrolled
                    ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] hover:bg-[#1a4fd4] hover:border-[#1a4fd4] shadow-md shadow-[#2563EB]/20'
                    : 'bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_20px_-5px_rgba(199,242,54,0.35)]'"
                class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 group"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
            </a>
        </div>

        <!-- Mobile Toggle Button -->
        <button 
            @click="mobileOpen = !mobileOpen"
            :class="scrolled ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/10'"
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
            <a 
                href="{{ route('services') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'services' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Layanan
            </a>
            <a 
                href="{{ route('portfolio') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'portfolio' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Portofolio
            </a>
            <a 
                href="{{ route('home') }}#pricing" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold text-white/80 hover:text-white hover:bg-white/10"
            >
                Paket Harga
            </a>
            <a 
                href="{{ route('contact') }}" 
                @click="mobileOpen = false"
                class="px-4 py-3 rounded-xl text-lg font-bold transition-all {{ $currentRoute == 'contact' ? 'text-[#0A1E5E] bg-[#C7F236]' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
            >
                Kontak
            </a>
            
            <a 
                href="{{ $whatsappUrl }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="mt-4 inline-flex items-center justify-center gap-2 rounded-full px-6 py-3.5 text-base font-semibold bg-[#C7F236] text-[#0A1E5E] shadow-lg"
            >
                <span>Konsultasi Gratis</span>
                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
            </a>
        </nav>
    </div>
</header>
