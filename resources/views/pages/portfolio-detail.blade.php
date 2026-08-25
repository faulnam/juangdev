@extends('layouts.app')

@php
    $isSoldBoilerplate = $portfolio->is_boilerplate && ($portfolio->sold_count > 0);
    $soldSuffix = $isSoldBoilerplate ? " (Boilerplate • Terjual {$portfolio->sold_count}x)" : ($portfolio->is_boilerplate ? " (Boilerplate)" : "");
    
    $pageTitle = $portfolio->title . $soldSuffix . ' — Studi Kasus Portofolio JuangDev';
    $ogTitle = $portfolio->title . $soldSuffix . ' — JuangDev';
    
    $rawDesc = strip_tags($portfolio->overview ?? $portfolio->description);
    $ogDesc = ($isSoldBoilerplate ? "[Terjual {$portfolio->sold_count}x] " : "") . \Illuminate\Support\Str::limit($rawDesc, 150);

    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya tertarik dengan proyek \"{$portfolio->title}{$soldSuffix}\" dan ingin berkonsultasi untuk pembuatan website/aplikasi serupa: " . url()->current());
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $shareWaText = urlencode("🔥 Cek portofolio studi kasus JuangDev: \"{$portfolio->title}\"" . ($isSoldBoilerplate ? " (Boilerplate • Terjual {$portfolio->sold_count}x)" : "") . "\n\n" . url()->current());
    $shareWaUrl = "https://api.whatsapp.com/send?text={$shareWaText}";

    $techStack = is_array($portfolio->technologies) ? $portfolio->technologies : [];
    $keyFeatures = is_array($portfolio->key_features) ? $portfolio->key_features : [];
    $gallery = is_array($portfolio->gallery) ? $portfolio->gallery : [];
@endphp

@section('title', $pageTitle)
@section('meta_description', $ogDesc)
@section('og_type', 'article')
@section('og_title', $ogTitle)
@section('og_description', $ogDesc)
@section('og_image', $portfolio->image_url ? (str_starts_with($portfolio->image_url, 'http') ? $portfolio->image_url : url($portfolio->image_url)) : asset('logo1.png'))

@section('content')
    <!-- Project Hero Header Section -->
    <section 
        class="relative pt-32 pb-16 md:pt-36 md:pb-20 overflow-hidden text-white bg-[#071542]"
        style="background: linear-gradient(160deg, #071542 0%, #0A1E5E 50%, #122d78 100%);"
    >
        <!-- Decorative subtle grid background -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
        <div class="absolute -top-24 right-0 w-96 h-96 rounded-full bg-[#2563EB]/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-300 mb-6 font-normal">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <a href="{{ route('portfolio') }}" class="hover:text-white transition-colors">Portofolio</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-[#C7F236] font-medium truncate max-w-[200px] sm:max-w-xs">{{ $portfolio->title }}</span>
            </nav>

            <!-- Title & Metadata Tag -->
            <div class="mb-8">
                <div class="flex flex-wrap items-center gap-2.5 mb-3">
                    <span class="inline-block bg-[#C7F236] text-[#0A1E5E] text-xs font-bold rounded-full px-3.5 py-1 shadow-xs uppercase tracking-wide">
                        {{ $portfolio->category ?? 'Web Application' }}
                    </span>
                    @if($portfolio->package_tier)
                        <span class="inline-block bg-amber-400 text-slate-950 text-xs font-extrabold rounded-full px-3.5 py-1 shadow-xs uppercase tracking-wide">
                            Paket {{ $portfolio->package_tier }}
                        </span>
                    @endif
                    @if($portfolio->is_boilerplate)
                        <span class="inline-block bg-[#2563EB] text-white text-xs font-bold rounded-full px-3.5 py-1 shadow-xs uppercase tracking-wide">
                            Boilerplate
                        </span>
                        @if($portfolio->sold_count > 0)
                            <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 text-xs font-bold rounded-full px-3.5 py-1 shadow-xs uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5 text-emerald-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                <span>Terjual {{ $portfolio->sold_count }}x</span>
                            </span>
                        @endif
                    @endif
                    @if($portfolio->client)
                        <span class="inline-block text-xs font-normal text-slate-300 bg-white/10 backdrop-blur-md border border-white/15 px-3.5 py-1 rounded-full">
                            {{ $portfolio->client }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-amber-400 sm:text-[#C7F236] leading-tight mb-4 font-sans">
                    {{ $portfolio->title }}
                </h1>
                
                <p class="text-slate-300 text-base sm:text-lg max-w-3xl leading-relaxed font-normal">
                    {{ $portfolio->description }}
                </p>
            </div>

            <!-- Featured Main Image Showcase Mockup -->
            <div class="mt-8 relative group">
                <div class="rounded-2xl overflow-hidden shadow-2xl bg-slate-900/80 border border-white/10 flex items-center justify-center">
                    @if($portfolio->image_url)
                        <img 
                            src="{{ $portfolio->image_url }}" 
                            alt="{{ $portfolio->title }}" 
                            class="w-full h-auto object-contain mx-auto transition-transform duration-500 group-hover:scale-[1.005]"
                        >
                    @else
                        <div class="w-full aspect-[16/9] flex items-center justify-center bg-gradient-to-br from-[#0A1E5E] to-[#2563EB]">
                            <span class="text-white/20 text-8xl font-black">{{ substr($portfolio->title, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Main Project Detail Content Section (2-Column Layout) -->
    <section 
        class="py-16 md:py-24 bg-[#f8f9fc]"
        x-data="{
            activeImage: null,
            openModal(url) {
                this.activeImage = url;
            },
            closeModal() {
                this.activeImage = null;
            }
        }"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
                
                <!-- Left Sidebar: Classification -->
                <aside class="lg:col-span-4">
                    <div class="sticky top-28 space-y-6">
                        <h3 class="text-xl font-bold text-slate-900 pb-3 border-b border-slate-200">
                            Classification
                        </h3>

                        <div class="space-y-5 text-sm">
                            <!-- Client Industry -->
                            <div>
                                <span class="block text-xs uppercase font-bold tracking-wider text-slate-500 mb-1">
                                    Client Industry
                                </span>
                                <p class="text-slate-800 font-normal leading-relaxed">
                                    {{ $portfolio->client_industry ?? ($portfolio->client ?? 'Property, Landlord, and Rental') }}
                                </p>
                            </div>

                            <!-- Service Stream -->
                            <div>
                                <span class="block text-xs uppercase font-bold tracking-wider text-slate-500 mb-1">
                                    Service Stream
                                </span>
                                <p class="text-slate-800 font-normal leading-relaxed">
                                    {{ $portfolio->category ?? 'Web Application' }}
                                    @if($portfolio->package_tier)
                                        <span class="text-xs font-bold text-[#2563EB] ml-1">({{ $portfolio->package_tier }})</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Duration -->
                            <div>
                                <span class="block text-xs uppercase font-bold tracking-wider text-slate-500 mb-1">
                                    Duration
                                </span>
                                <p class="text-slate-800 font-normal leading-relaxed">
                                    {{ $portfolio->duration ?? 'Januari 2024 - Juni 2024 (6 Bulan)' }}
                                </p>
                            </div>

                            @if($portfolio->is_boilerplate && $portfolio->sold_count > 0)
                                <!-- Status Penjualan -->
                                <div>
                                    <span class="block text-xs uppercase font-bold tracking-wider text-slate-500 mb-1">
                                        Status Penjualan
                                    </span>
                                    <div class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-300 text-emerald-700 text-xs font-bold rounded-lg px-2.5 py-1 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        <span>Terjual {{ $portfolio->sold_count }}x</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Tech Stack -->
                            <div>
                                <span class="block text-xs uppercase font-bold tracking-wider text-slate-500 mb-2">
                                    Tech Stack
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($techStack as $tech)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-normal bg-slate-900 text-slate-200">
                                            {{ $tech }}
                                        </span>
                                    @empty
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-normal bg-slate-900 text-slate-200">Laravel</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-normal bg-slate-900 text-slate-200">MongoDB</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-normal bg-slate-900 text-slate-200">Bootstrap</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- CTA Actions -->
                        <div class="pt-4 space-y-3">
                            @if($portfolio->live_url)
                                <a 
                                    href="{{ $portfolio->live_url }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer" 
                                    class="w-full inline-flex items-center justify-center gap-2 bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-bold text-sm py-3 px-5 rounded-xl transition-all duration-200 shadow-sm"
                                >
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                    <span>Kunjungi Website Live</span>
                                </a>
                            @endif

                            <a 
                                href="{{ route('home') }}?service={{ \Illuminate\Support\Str::slug($portfolio->category) }}#estimator" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-[#C7F236] hover:bg-[#b5dd2a] text-[#0A1E5E] font-bold text-sm py-3 px-5 rounded-xl transition-all duration-200 shadow-sm"
                            >
                                <i data-lucide="calculator" class="w-4 h-4"></i>
                                <span>Buat Proyek Seperti Ini</span>
                            </a>

                            <!-- Share Portfolio Box (Includes Sold Status) -->
                            <div 
                                x-data="{
                                    copied: false,
                                    shareTitle: '{{ addslashes($portfolio->title . $soldSuffix) }}',
                                    shareText: '{{ addslashes($ogDesc) }}',
                                    shareUrl: window.location.href,
                                    doNativeShare() {
                                        if (navigator.share) {
                                            navigator.share({
                                                title: this.shareTitle,
                                                text: this.shareText,
                                                url: window.location.href
                                            }).catch(() => {});
                                        } else {
                                            this.copyLink();
                                        }
                                    },
                                    copyLink() {
                                        navigator.clipboard.writeText(window.location.href);
                                        this.copied = true;
                                        setTimeout(() => { this.copied = false; }, 2500);
                                    }
                                }"
                                class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2.5 mt-2"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <i data-lucide="share-2" class="w-3.5 h-3.5 text-[#2563EB]"></i>
                                        <span>Bagikan Portofolio</span>
                                    </span>
                                    <span x-show="copied" x-cloak class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-300 px-2 py-0.5 rounded-md">
                                        Tersalin! ✓
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a 
                                        href="{{ $shareWaUrl }}" 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-2xs"
                                        title="Bagikan ke WhatsApp dengan status penjualan"
                                    >
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        <span>WhatsApp</span>
                                    </a>
                                    <button 
                                        type="button" 
                                        @click="doNativeShare()" 
                                        class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors cursor-pointer"
                                        title="Salin tautan atau bagikan"
                                    >
                                        <i data-lucide="copy" class="w-3.5 h-3.5 text-slate-600"></i>
                                        <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- Right Main Column: Overview & Key Features & Screenshots Gallery -->
                <main class="lg:col-span-8">
                    
                    <!-- Overview Section -->
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">
                            Overview
                        </h2>
                        @php
                            $overviewText = trim($portfolio->overview ?? $portfolio->description ?? '');
                            $paragraphs = preg_split('/(?:\r\n|\r|\n){2,}/', $overviewText);
                        @endphp
                        <div class="text-slate-700 text-base leading-relaxed space-y-4 font-normal">
                            @foreach($paragraphs as $paragraph)
                                @if(trim($paragraph))
                                    <p class="leading-relaxed">
                                        {!! nl2br(e(trim($paragraph))) !!}
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Key Features Section -->
                    @if(count($keyFeatures) > 0)
                        <div class="mb-12">
                            <h2 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">
                                Key Features
                            </h2>
                            <ul class="space-y-2.5">
                                @foreach($keyFeatures as $feature)
                                    <li class="flex items-start gap-2.5 text-slate-700 font-normal text-base">
                                        <span class="text-slate-900 font-bold text-lg leading-none mt-0.5">•</span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Screenshots Gallery Grid (Frameless / No Card) -->
                    @if(count($gallery) > 0)
                        <div class="mb-12">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 tracking-tight">
                                Screenshots &amp; Gallery
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                                @foreach($gallery as $item)
                                    @php
                                        $gTitle = is_array($item) ? ($item['title'] ?? '') : '';
                                        $gImg = is_array($item) ? ($item['image_url'] ?? '') : $item;
                                    @endphp
                                    <div 
                                        @click="openModal('{{ $gImg }}')"
                                        class="group cursor-pointer flex flex-col items-center"
                                    >
                                        <!-- Frameless Screenshot Image -->
                                        <div class="w-full aspect-[16/10] overflow-hidden rounded-xl relative shadow-sm hover:shadow-lg transition-all duration-300 bg-slate-100">
                                            <img 
                                                src="{{ $gImg }}" 
                                                alt="{{ $gTitle }}" 
                                                loading="lazy"
                                                decoding="async"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            >
                                            <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                <span class="bg-white/95 text-slate-900 font-medium text-xs px-3 py-1.5 rounded-full shadow-md flex items-center gap-1.5">
                                                    <i data-lucide="zoom-in" class="w-3.5 h-3.5 text-[#2563EB]"></i>
                                                    Perbesar Gambar
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Caption under image (Frameless) -->
                                        @if($gTitle)
                                            <h4 class="text-sm font-medium text-slate-800 text-center mt-3 group-hover:text-[#2563EB] transition-colors">
                                                {{ $gTitle }}
                                            </h4>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Back Link Button ("Back to All Projects" - White Color) -->
                    <div class="pt-8 text-center border-t border-slate-200 mt-12">
                        <a 
                            href="{{ route('portfolio') }}" 
                            class="inline-flex items-center gap-2 bg-white text-slate-900 hover:text-[#2563EB] hover:bg-slate-50 border-2 border-slate-200 hover:border-slate-300 font-bold text-sm sm:text-base py-3 px-8 rounded-full transition-all duration-200 shadow-xs hover:shadow-md"
                        >
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>Back to All Projects</span>
                        </a>
                    </div>

                </main>
            </div>
        </div>

        <!-- Lightbox Image Modal -->
        <div 
            x-show="activeImage" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeModal()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-8 bg-slate-950/80 backdrop-blur-md"
        >
            <div 
                @click.away="closeModal()"
                class="relative max-w-5xl w-full bg-slate-900 rounded-2xl border border-white/20 overflow-hidden shadow-2xl"
            >
                <button 
                    @click="closeModal()"
                    class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-slate-800/80 text-white hover:bg-slate-700 flex items-center justify-center transition-colors border border-white/10"
                    aria-label="Tutup"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <div class="p-2 sm:p-4">
                    <img :src="activeImage" alt="Preview Screenshot" class="w-full h-auto max-h-[80vh] object-contain rounded-xl mx-auto">
                </div>
            </div>
        </div>

    </section>

    <!-- Related Projects Section -->
    @if(isset($relatedPortfolios) && count($relatedPortfolios) > 0)
        <section class="py-16 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Proyek Terkait <span class="text-[#2563EB]">Lainnya</span>
                        </h3>
                        <p class="text-slate-500 text-sm font-normal mt-1">
                            Jelajah karya dan studi kasus lain yang pernah kami bangun
                        </p>
                    </div>
                    <a 
                        href="{{ route('portfolio') }}" 
                        class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-[#2563EB] hover:underline"
                    >
                        <span>Lihat Semua</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPortfolios as $rel)
                        <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2 shadow-lg flex flex-col h-full">
                            <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] flex items-center justify-center relative overflow-hidden border-b-2 border-slate-200">
                                @if($rel->image_url)
                                    <img 
                                        src="{{ $rel->image_url }}" 
                                        alt="{{ $rel->title }}" 
                                        loading="lazy"
                                        decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="text-white/20 text-5xl font-black">{{ substr($rel->title, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <div class="flex items-center gap-1.5 self-start mb-2">
                                    <span class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-bold rounded px-2.5 py-0.5">
                                        {{ $rel->category ?? 'Web Application' }}
                                    </span>
                                    @if($rel->is_boilerplate)
                                        <span class="inline-block bg-[#2563EB] text-white text-[10px] font-bold rounded px-2 py-0.5 shadow-xs">
                                            Boilerplate
                                        </span>
                                        @if($rel->sold_count > 0)
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-300 text-[10px] font-bold rounded px-1.5 py-0.5 shadow-2xs">
                                                <svg class="w-3 h-3 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                                <span>{{ $rel->sold_count }}x Terjual</span>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors">
                                    {{ $rel->title }}
                                </h4>
                                <p class="text-slate-600 text-xs font-normal line-clamp-2 mb-4 flex-grow">
                                    {{ $rel->description }}
                                </p>
                                <a 
                                    href="{{ route('portfolio.show', $rel->slug) }}" 
                                    class="inline-flex items-center gap-1.5 text-[#2563EB] text-sm font-bold hover:gap-2 transition-all"
                                >
                                    <span>Lihat Detail</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Final CTA Partial -->
    @include('partials.final-cta')
@endsection
