@if(isset($blogs) && count($blogs) > 0)
<section id="blog" class="py-16 md:py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 md:mb-16 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                    Wawasan &amp; <span class="text-[#2563EB] font-serif italic">Artikel Terbaru</span>
                </h2>
                <p class="text-slate-600 text-[0.95rem] md:text-base leading-relaxed font-medium">
                    Pelajari strategi teknologi digital, panduan pembuatan website, dan tips bisnis terkini dari tim ahli JuangDev.
                </p>
            </div>

            <a 
                href="{{ route('blog') }}" 
                class="inline-flex items-center gap-2 font-extrabold text-[#2563EB] hover:text-[#1d4ed8] text-sm group"
            >
                <span>Lihat Semua Artikel</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>

        <!-- Grid of 3 Blogs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($blogs as $article)
                <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2 shadow-lg flex flex-col h-full">
                    
                    <!-- Cover Image -->
                    <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] overflow-hidden relative border-b-2 border-slate-200">
                        @if($article->image_url)
                            <img 
                                src="{{ $article->image_url }}" 
                                alt="{{ $article->title }}" 
                                loading="lazy"
                                decoding="async"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/20 text-5xl font-black">
                                {{ substr($article->title, 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-extrabold rounded-full px-2.5 py-0.5 shadow-xs">
                                {{ $article->category ?? 'Technology' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium mb-2">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>{{ $article->read_time ?? '5 min read' }}</span>
                            <span>•</span>
                            <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                        </div>

                        <a href="{{ route('blog.show', $article->slug) }}" class="block">
                            <h3 class="text-base font-bold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors leading-snug">
                                {{ $article->title }}
                            </h3>
                        </a>

                        <p class="text-slate-600 text-xs leading-relaxed mb-4 flex-grow line-clamp-3 font-normal">
                            {{ $article->excerpt ? strip_tags($article->excerpt) : Str::limit(strip_tags($article->content), 120) }}
                        </p>

                        <div class="border-t border-slate-100 pt-3 mt-auto flex items-center justify-between">
                            <span class="text-[11px] font-normal text-slate-500">
                                Oleh <strong class="text-slate-700 font-semibold">{{ $article->author ?? 'Tim JuangDev' }}</strong>
                            </span>

                            <a 
                                href="{{ route('blog.show', $article->slug) }}" 
                                class="inline-flex items-center gap-1.5 text-[#2563EB] text-xs font-extrabold hover:gap-2 transition-all"
                            >
                                <span>Baca Artikel</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
@endif
