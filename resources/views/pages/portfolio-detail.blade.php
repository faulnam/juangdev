@extends('layouts.app')

@section('title', $portfolio->title . ' — Studi Kasus Portofolio JuangDev')
@section('meta_description', Str::limit(strip_tags($portfolio->overview ?? $portfolio->description), 155))
@section('og_type', 'article')
@section('og_title', $portfolio->title . ' — Studi Kasus JuangDev')
@section('og_description', Str::limit(strip_tags($portfolio->overview ?? $portfolio->description), 155))
@section('og_image', $portfolio->image_url ? (str_starts_with($portfolio->image_url, 'http') ? $portfolio->image_url : url($portfolio->image_url)) : asset('logo4.png'))

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya tertarik dengan proyek \"{$portfolio->title}\" dan ingin berkonsultasi untuk pembuatan website/aplikasi serupa.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $techStack = is_array($portfolio->technologies) ? $portfolio->technologies : [];
    $keyFeatures = is_array($portfolio->key_features) ? $portfolio->key_features : [];
    $gallery = is_array($portfolio->gallery) ? $portfolio->gallery : [];
@endphp

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
                                <span class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-bold rounded px-2.5 py-0.5 self-start mb-2">
                                    {{ $rel->category ?? 'Web Application' }}
                                </span>
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
