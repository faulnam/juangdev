@extends('layouts.app')

@section('title', 'Blog & Artikel — JuangDev')
@section('meta_description', 'Jelajahi wawasan terbaru mengenai pembuatan website, tren teknologi, e-commerce, dan strategi otomatisasi bisnis dari JuangDev.')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya telah membaca artikel Anda dan ingin berkonsultasi mengenai proyek baru.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

@section('content')
    <!-- Blog Hero -->
    <section 
        class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden text-white text-center bg-[#071542]"
        style="background: linear-gradient(160deg, #071542 0%, #0A1E5E 50%, #122d78 100%);"
    >
        <!-- Decorative subtle grid background & right glow lighting effect -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
        <div class="absolute -top-24 right-0 w-96 h-96 rounded-full bg-[#2563EB]/25 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-blue-500/15 blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight mb-6">
                {{ $settings['hero_blog_title'] ?? 'Blog & Wawasan Digital' }}
            </h1>
            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                {{ $settings['hero_blog_desc'] ?? 'Temukan panduan praktis, tren teknologi terkini, strategi e-commerce, dan tips pengembangan aplikasi untuk mempercepat pertumbuhan bisnis Anda.' }}
            </p>
        </div>
    </section>

    <!-- Main Content: Category Filter, Search, Featured Article & Grid -->
    <section 
        class="py-16 md:py-24 bg-[#f8f9fc]"
        x-data="{
            selectedCategory: '{{ request('category', 'all') }}',
            searchQuery: '{{ request('q', '') }}',
            filterCategory(cat) {
                this.selectedCategory = cat;
                let url = new URL(window.location.href);
                if (cat === 'all') {
                    url.searchParams.delete('category');
                } else {
                    url.searchParams.set('category', cat);
                }
                window.location.href = url.toString();
            }
        }"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            <!-- Filter & Search Toolbar (Clean, Modern Dropdown like Portfolio) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 mb-10">
                <form action="{{ route('blog') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-center">
                    
                    <!-- Search Input -->
                    <div class="md:col-span-7 lg:col-span-8 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Cari artikel, panduan website, tips teknologi..."
                            class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] focus:bg-white transition-all"
                        >
                        @if(request('q'))
                            <a 
                                href="{{ route('blog', array_filter(['category' => request('category')])) }}"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Dropdown Kategori Artikel -->
                    <div class="md:col-span-5 lg:col-span-4 relative">
                        <select 
                            name="category"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#2563EB] focus:bg-white cursor-pointer transition-all"
                        >
                            <option value="">Semua Kategori ({{ $totalCount ?? $blogs->total() }})</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </form>
            </div>

            @if($blogs->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <i data-lucide="book-open" class="w-12 h-12 text-slate-400 mx-auto mb-3"></i>
                    <h3 class="text-xl font-bold text-slate-900 mb-1">Belum Ada Artikel</h3>
                    <p class="text-slate-500 text-sm font-normal">Tidak ditemukan artikel yang sesuai dengan pencarian atau kategori yang dipilih.</p>
                    <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-2.5 rounded-full bg-[#2563EB] text-white text-xs font-bold hover:bg-blue-700 transition-colors">
                        Lihat Semua Artikel
                    </a>
                </div>
            @else

                <!-- Top Featured Article Hero Card (First Article) -->
                @if(isset($featuredBlog) && !request('q') && !request('category'))
                    <div class="mb-14">
                        <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-3xl overflow-hidden shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-0">
                            <!-- Image -->
                            <div class="lg:col-span-7 aspect-[16/10] lg:aspect-auto overflow-hidden bg-slate-900 relative">
                                @if($featuredBlog->image_url)
                                    <img 
                                        src="{{ $featuredBlog->image_url }}" 
                                        alt="{{ $featuredBlog->title }}" 
                                        loading="lazy"
                                        decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    >
                                @else
                                    <div class="w-full h-full min-h-[300px] flex items-center justify-center bg-gradient-to-br from-[#0A1E5E] to-[#2563EB]">
                                        <i data-lucide="file-text" class="w-20 h-20 text-white/20"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-xs font-extrabold rounded-full px-3 py-1 shadow-sm uppercase tracking-wide">
                                        📌 Artikel Utama
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 font-medium mb-3">
                                        <span class="bg-blue-50 text-[#2563EB] px-2.5 py-1 rounded-md font-bold">
                                            {{ $featuredBlog->category ?? 'Technology' }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ $featuredBlog->read_time ?? '5 min read' }}</span>
                                    </div>

                                    <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="block group/title">
                                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-4 group-hover/title:text-[#2563EB] transition-colors">
                                            {{ $featuredBlog->title }}
                                        </h2>
                                    </a>

                                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-normal line-clamp-3">
                                        {{ $featuredBlog->excerpt }}
                                    </p>
                                </div>

                                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#0A1E5E] text-white font-bold text-xs flex items-center justify-center">
                                            {{ substr($featuredBlog->author ?? 'J', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">{{ $featuredBlog->author ?? 'Tim JuangDev' }}</p>
                                            <p class="text-[11px] text-slate-400 font-normal">
                                                {{ $featuredBlog->published_at ? $featuredBlog->published_at->format('d M Y') : $featuredBlog->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <a 
                                        href="{{ route('blog.show', $featuredBlog->slug) }}" 
                                        class="inline-flex items-center gap-1.5 text-[#2563EB] text-sm font-extrabold hover:gap-2 transition-all"
                                    >
                                        <span>Baca Artikel</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($blogs as $blog)
                        <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2 shadow-lg flex flex-col h-full">
                            
                            <!-- Thumbnail Image -->
                            <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] overflow-hidden relative border-b-2 border-slate-200">
                                @if($blog->image_url)
                                    <img 
                                        src="{{ $blog->image_url }}" 
                                        alt="{{ $blog->title }}" 
                                        loading="lazy"
                                        decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/20 text-5xl font-black">
                                        {{ substr($blog->title, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="bg-white/90 backdrop-blur-md text-[#0A1E5E] text-[10px] font-extrabold rounded-full px-2.5 py-0.5 shadow-xs">
                                        {{ $blog->category ?? 'Technology' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 font-normal mb-2">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    <span>{{ $blog->read_time ?? '5 min read' }}</span>
                                    <span>•</span>
                                    <span>{{ $blog->published_at ? $blog->published_at->format('d M Y') : $blog->created_at->format('d M Y') }}</span>
                                </div>

                                <a href="{{ route('blog.show', $blog->slug) }}" class="block">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors leading-snug">
                                        {{ $blog->title }}
                                    </h3>
                                </a>

                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-4 flex-grow line-clamp-3 font-normal">
                                    {{ $blog->excerpt }}
                                </p>

                                <div class="border-t border-slate-100 pt-4 mt-auto flex items-center justify-between">
                                    <span class="text-xs font-normal text-slate-500">
                                        Oleh <strong class="text-slate-700 font-semibold">{{ $blog->author ?? 'Tim JuangDev' }}</strong>
                                    </span>

                                    <a 
                                        href="{{ route('blog.show', $blog->slug) }}" 
                                        class="inline-flex items-center gap-1.5 text-[#2563EB] text-xs font-bold hover:gap-2 transition-all"
                                    >
                                        <span>Baca Artikel</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination Bar -->
                @if($blogs->hasPages())
                    <div class="mt-14 flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-slate-200">
                        <p class="text-xs font-semibold text-slate-500">
                            Menampilkan <span class="text-slate-900 font-bold">{{ $blogs->firstItem() }}</span> - <span class="text-slate-900 font-bold">{{ $blogs->lastItem() }}</span> dari <span class="text-slate-900 font-bold">{{ $blogs->total() }}</span> artikel
                        </p>
                        <div class="blog-pagination">
                            {{ $blogs->links() }}
                        </div>
                    </div>
                @endif

            @endif

        </div>
    </section>

    <!-- Final CTA -->
    @include('partials.final-cta')
@endsection
