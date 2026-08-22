@extends('layouts.app')

@section('title', $blog->title . ' — Blog JuangDev')
@section('meta_description', Str::limit(strip_tags($blog->excerpt ?? $blog->content), 160))

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya telah membaca artikel \"{$blog->title}\" dan tertarik berkonsultasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $shareUrl = urlencode(request()->fullUrl());
    $shareTitle = urlencode($blog->title);
@endphp

@section('content')
    <!-- Main Article Page Section -->
    <section class="pt-28 pb-16 md:pt-36 md:pb-24 bg-[#f8f9fc]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-[#2563EB] transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <a href="{{ route('blog') }}" class="hover:text-[#2563EB] transition-colors">Blog</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $blog->title }}</span>
            </nav>

            <!-- Main Title & Metadata Bar (Left Aligned like reference layout) -->
            <div class="mb-8 max-w-4xl">
                <span class="inline-block bg-blue-50 text-[#2563EB] border border-blue-200 text-xs font-extrabold rounded-full px-3.5 py-1 mb-3 uppercase tracking-wider">
                    {{ $blog->category ?? 'Technology' }}
                </span>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-6">
                    {{ $blog->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-200">
                    <!-- Author & Date Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#0A1E5E] text-[#C7F236] font-extrabold text-sm flex items-center justify-center shrink-0 shadow-sm">
                            {{ substr($blog->author ?? 'J', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $blog->author ?? 'Tim JuangDev' }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <span>{{ $blog->published_at ? $blog->published_at->format('d M Y') : $blog->created_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span>{{ $blog->read_time ?? '5 min read' }}</span>
                                <span>•</span>
                                <span class="flex items-center gap-1 text-[#2563EB] font-bold">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>{{ number_format($blog->views ?? 0) }} views</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Share Action Buttons -->
                    <div class="flex items-center gap-2.5" x-data="{ copied: false }">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider hidden sm:inline-block">Bagikan:</span>
                        
                        <!-- WhatsApp -->
                        <a 
                            href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-9 h-9 rounded-full bg-[#25D366] hover:bg-[#20bd5a] text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md shadow-[#25D366]/20"
                            title="Bagikan via WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </a>

                        <!-- X / Twitter -->
                        <a 
                            href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-9 h-9 rounded-full bg-slate-900 hover:bg-black text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md shadow-slate-900/20"
                            title="Bagikan via X (Twitter)"
                        >
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>

                        <!-- LinkedIn -->
                        <a 
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-9 h-9 rounded-full bg-[#0A66C2] hover:bg-[#084e96] text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md shadow-[#0A66C2]/20"
                            title="Bagikan via LinkedIn"
                        >
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.62 1.62 0 1 0 0 3.24 1.62 1.62 0 0 0 0-3.24z"/>
                            </svg>
                        </a>

                        <!-- Copy Link Button with Tooltip -->
                        <button 
                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                            class="relative w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-xs border border-slate-200"
                            title="Salin Tautan Artikel"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span 
                                x-show="copied" 
                                x-cloak 
                                x-transition
                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-md whitespace-nowrap"
                            >
                                Tersalin!
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2-Column Grid Layout: Main Article (Left 8 cols) & Sidebar (Right 4 cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
                
                <!-- Left Column: Main Article Content (lg:col-span-8) -->
                <div class="lg:col-span-8">
                    
                    <!-- Featured Cover Image Banner -->
                    @if($blog->image_url)
                        <div class="mb-8 rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-slate-900 aspect-[16/9] relative">
                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-2 right-3 text-[10px] text-white/70 bg-black/50 backdrop-blur-xs px-2 py-0.5 rounded">
                                Foto: JuangDev Archive
                            </div>
                        </div>
                    @endif

                    <!-- Excerpt Box -->
                    @if($blog->excerpt)
                        <div class="mb-8 p-6 rounded-2xl bg-white border-2 border-slate-200 border-l-4 border-l-[#2563EB] shadow-xs text-slate-700 text-base leading-relaxed italic font-medium">
                            "{{ $blog->excerpt }}"
                        </div>
                    @endif

                    <!-- Article Body Content -->
                    <article class="prose prose-slate max-w-none text-slate-700 text-base sm:text-lg leading-relaxed font-normal space-y-6 bg-white p-8 sm:p-10 rounded-2xl border-2 border-slate-200 shadow-sm">
                        {!! nl2br(e($blog->content)) !!}
                    </article>

                    <!-- Article Hashtags Footer -->
                    <div class="mt-8 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 mr-2">Tag Terkait:</span>
                        <span class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full transition-colors">
                            #{{ str_replace(' ', '', $blog->category ?? 'Technology') }}
                        </span>
                        <span class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full transition-colors">
                            #WebsiteDevelopment
                        </span>
                        <span class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full transition-colors">
                            #JuangDev
                        </span>
                    </div>

                    <!-- Author Card Box -->
                    <div class="mt-8 p-6 rounded-2xl bg-white border-2 border-slate-200 flex items-center gap-4 shadow-sm">
                        <div class="w-14 h-14 rounded-full bg-[#0A1E5E] text-[#C7F236] font-black text-xl flex items-center justify-center shrink-0">
                            {{ substr($blog->author ?? 'J', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Ditulis oleh {{ $blog->author ?? 'Tim JuangDev' }}</h4>
                            <p class="text-slate-500 text-xs font-normal mt-0.5 leading-relaxed">
                                Tim spesialis rekayasa perangkat lunak, pengembang web, dan perancang produk digital di JuangDev.
                            </p>
                        </div>
                    </div>

                    <!-- Back to All Articles Button (White Color) -->
                    <div class="mt-8">
                        <a 
                            href="{{ route('blog') }}" 
                            class="inline-flex items-center gap-2 bg-white text-slate-900 hover:text-[#2563EB] hover:bg-slate-50 border-2 border-slate-200 hover:border-slate-300 font-bold text-sm py-2.5 px-6 rounded-full transition-all duration-200 shadow-xs"
                        >
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>Kembali ke Semua Artikel</span>
                        </a>
                    </div>

                </div>

                <!-- Right Column: Sticky Sidebar (lg:col-span-4) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <div class="sticky top-28 space-y-8">
                        
                        <!-- Widget 1: Artikel Teratas (Compact List with Image Thumbnails) -->
                        @if(isset($relatedBlogs) && count($relatedBlogs) > 0)
                            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 shadow-sm">
                                <h3 class="text-lg font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                                    <span>Artikel Teratas</span>
                                    <i data-lucide="sparkles" class="w-4 h-4 text-[#2563EB]"></i>
                                </h3>

                                <div class="space-y-4">
                                    @foreach($relatedBlogs as $rel)
                                        <a href="{{ route('blog.show', $rel->slug) }}" class="group flex gap-3.5 items-start">
                                            <!-- Thumbnail -->
                                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-900 shrink-0 border border-slate-200">
                                                @if($rel->image_url)
                                                    <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-xs">
                                                        JuangDev
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Article Details -->
                                            <div class="flex-grow min-w-0">
                                                <span class="inline-block text-[10px] font-bold text-[#2563EB] bg-blue-50 px-2 py-0.5 rounded mb-1">
                                                    {{ $rel->category ?? 'Tech' }}
                                                </span>
                                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#2563EB] transition-colors leading-snug line-clamp-2">
                                                    {{ $rel->title }}
                                                </h4>
                                                <p class="text-[11px] text-slate-400 mt-1 font-normal flex items-center justify-between">
                                                    <span>{{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }}</span>
                                                    <span class="text-[#2563EB] font-bold flex items-center gap-1">
                                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                                        <span>{{ number_format($rel->views ?? 0) }}</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Widget 2: Dark Promo CTA Banner (Matching JuangDev Theme) -->
                        <div 
                            class="relative p-6 sm:p-8 rounded-2xl text-white overflow-hidden shadow-xl bg-[#071542]"
                            style="background: linear-gradient(160deg, #071542 0%, #0A1E5E 50%, #122d78 100%);"
                        >
                            <!-- Decorative Grid & Lighting Glow -->
                            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:2rem_2rem] pointer-events-none"></div>
                            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[#2563EB]/30 blur-2xl pointer-events-none"></div>

                            <div class="relative z-10">
                                <span class="inline-block bg-[#C7F236] text-[#0A1E5E] text-[10px] font-extrabold rounded-full px-3 py-1 mb-3 uppercase tracking-wider">
                                    Solusi Bisnis Digital
                                </span>
                                
                                <h4 class="text-xl font-black text-white leading-tight mb-2">
                                    Ingin Membangun Website Seperti Ini?
                                </h4>

                                <p class="text-white/80 text-xs leading-relaxed mb-6 font-normal">
                                    Konsultasikan kebutuhan aplikasi web, e-commerce, atau portal bisnis Anda gratis bersama tim pengembang JuangDev.
                                </p>

                                <a 
                                    href="{{ route('contact') }}" 
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-full py-3 px-5 text-xs font-bold bg-[#C7F236] text-[#0A1E5E] hover:bg-[#b5dd2a] transition-all shadow-md shadow-[#C7F236]/20 group"
                                >
                                    <span>Konsultasi Gratis</span>
                                    <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Bottom Related Articles Section (If needed) -->
    @if(isset($relatedBlogs) && count($relatedBlogs) > 0)
        <section class="py-16 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Artikel Terkait <span class="text-[#2563EB]">Lainnya</span>
                        </h3>
                        <p class="text-slate-500 text-sm font-normal mt-1">
                            Baca wawasan dan informasi menarik seputar teknologi digital
                        </p>
                    </div>
                    <a 
                        href="{{ route('blog') }}" 
                        class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-[#2563EB] hover:underline"
                    >
                        <span>Lihat Semua Blog</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Single Row Horizontal Scroll Container -->
                <div class="flex gap-6 overflow-x-auto pb-6 scroll-smooth snap-x snap-mandatory text-left">
                    @foreach($relatedBlogs as $rel)
                        <div class="w-[85vw] sm:w-[340px] md:w-[calc((100%-3rem)/3)] shrink-0 snap-start group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 shadow-lg flex flex-col h-full">
                            <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] overflow-hidden relative border-b-2 border-slate-200">
                                @if($rel->image_url)
                                    <img 
                                        src="{{ $rel->image_url }}" 
                                        alt="{{ $rel->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/20 text-5xl font-black">
                                        {{ substr($rel->title, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-bold rounded px-2.5 py-0.5 self-start mb-2">
                                    {{ $rel->category ?? 'Technology' }}
                                </span>
                                <h4 class="text-base font-bold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors leading-snug line-clamp-2">
                                    {{ $rel->title }}
                                </h4>
                                <p class="text-slate-600 text-xs font-normal line-clamp-2 mb-4 flex-grow">
                                    {{ $rel->excerpt }}
                                </p>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
                                    <a 
                                        href="{{ route('blog.show', $rel->slug) }}" 
                                        class="inline-flex items-center gap-1.5 text-[#2563EB] text-xs font-bold hover:gap-2 transition-all"
                                    >
                                        <span>Baca Artikel</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3 text-[#2563EB]"></i>
                                        <span>{{ number_format($rel->views ?? 0) }}</span>
                                    </span>
                                </div>
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
