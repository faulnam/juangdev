<section 
    id="portfolio" 
    class="py-16 md:py-24 lg:py-28 bg-[#f8f9fc] relative overflow-hidden"
    x-data="{
        scrollLeft() {
            this.$refs.portfolioContainer.scrollBy({ left: -380, behavior: 'smooth' });
        },
        scrollRight() {
            this.$refs.portfolioContainer.scrollBy({ left: 380, behavior: 'smooth' });
        }
    }"
>
    <div class="absolute top-1/2 -right-40 w-96 h-96 rounded-full bg-[#2563EB]/5 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        
        <!-- Header with Carousel Controls -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 md:mb-16 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                    Portofolio Proyek <span class="text-[#2563EB] font-serif italic">Terbaik Kami</span>
                </h2>
                <p class="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed font-medium">
                    Setiap proyek adalah kisah sukses. Berikut adalah beberapa solusi digital yang telah kami bangun bersama klien.
                </p>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-3 self-start md:self-end">
                <button 
                    @click="scrollLeft()"
                    class="w-12 h-12 rounded-full border-2 border-slate-200 border-b-[4px] border-b-slate-300 flex items-center justify-center bg-white text-[#1e2547] hover:border-[#2563EB] hover:text-[#2563EB] hover:bg-[#2563EB]/5 transition-all duration-200 shadow-xs active:translate-y-0.5"
                    aria-label="Proyek sebelumnya"
                >
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button 
                    @click="scrollRight()"
                    class="w-12 h-12 rounded-full border-2 border-[#2563EB] border-b-[4px] border-b-[#0A1E5E] flex items-center justify-center bg-white text-[#2563EB] hover:bg-[#2563EB] hover:text-white transition-all duration-200 shadow-xs active:translate-y-0.5"
                    aria-label="Proyek selanjutnya"
                >
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Carousel Container -->
        <div 
            x-ref="portfolioContainer"
            class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scroll-smooth hide-scrollbar -mx-6 sm:mx-0 px-6 sm:px-0"
        >
            @foreach($portfolios as $project)
                <div class="w-[82vw] sm:w-[385px] shrink-0 snap-start snap-always">
                    <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.02] shadow-lg flex flex-col h-full">
                        
                        <!-- Image Container -->
                        <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] flex items-center justify-center relative overflow-hidden border-b-2 border-slate-200">
                            @if($project->image_url)
                                <img 
                                    src="{{ $project->image_url }}" 
                                    alt="{{ $project->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                            @else
                                <div class="text-white/20 text-6xl font-black">{{ substr($project->title, 0, 1) }}</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="mb-2.5">
                                <span class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-extrabold rounded px-2.5 py-0.5 shadow-[1.5px_1.5px_0_rgba(0,0,0,0.08)]">
                                    {{ $project->category ?? 'Aplikasi Web' }}
                                </span>
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-900 mb-1.5 group-hover:text-[#2563EB] transition-colors duration-200">
                                {{ $project->title }}
                            </h3>

                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-3 flex-grow line-clamp-3">
                                {{ $project->description }}
                            </p>

                            @if($project->technologies && count($project->technologies) > 0)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(array_slice($project->technologies, 0, 3) as $tech)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="border-t border-slate-100 pt-3 mt-auto flex items-center justify-between">
                                <a 
                                    href="{{ $project->live_url ?? '#' }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer" 
                                    class="inline-flex items-center gap-1.5 text-[#2563EB] text-sm font-extrabold hover:gap-2 transition-all duration-200 group/link"
                                >
                                    <span>Lihat Detail Proyek</span>
                                    <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
