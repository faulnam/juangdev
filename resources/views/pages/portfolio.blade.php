@extends('layouts.app')

@section('title', 'Portofolio — JuangDev')
@section('meta_description', 'Jelajahi portofolio studi kasus produk digital, aplikasi web, dan website yang telah dibangun oleh JuangDev.')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya melihat portofolio Anda dan ingin membangun website serupa.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
    
    $allCategories = $portfolios->pluck('category')->unique()->filter()->values();
@endphp

@section('content')
    <!-- Portfolio Hero -->
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
                {{ $settings['hero_portfolio_title'] ?? 'Portofolio Proyek & Studi Kasus' }}
            </h1>
            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                {{ $settings['hero_portfolio_desc'] ?? 'Jelajahi berbagai proyek nyata yang telah kami bangun untuk beragam industri — mulai dari bisnis berkembang hingga perusahaan besar.' }}
            </p>
        </div>
    </section>

    <!-- Portfolio Grid with Alpine.js Category, Tier, Boilerplate & Search Filters + Pagination -->
    <section 
        class="py-16 md:py-24 bg-[#f8f9fc]"
        x-data="{
            searchQuery: '',
            selectedCategory: 'all',
            selectedTier: 'all',
            selectedBoilerplate: 'all',
            currentPage: 1,
            perPage: 12,
            projects: {{ json_encode($portfolios) }},
            init() {
                this.$watch('searchQuery', () => { this.currentPage = 1; });
                this.$watch('selectedCategory', () => { this.currentPage = 1; });
                this.$watch('selectedTier', () => { this.currentPage = 1; });
                this.$watch('selectedBoilerplate', () => { this.currentPage = 1; });
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
                });
            },
            get isFiltered() {
                return this.selectedCategory !== 'all' || 
                       this.selectedTier !== 'all' || 
                       this.selectedBoilerplate !== 'all' || 
                       this.searchQuery.trim() !== '';
            },
            resetFilters() {
                this.searchQuery = '';
                this.selectedCategory = 'all';
                this.selectedTier = 'all';
                this.selectedBoilerplate = 'all';
                this.currentPage = 1;
            },
            get filteredProjects() {
                return this.projects.filter(p => {
                    // Category filter
                    if (this.selectedCategory !== 'all') {
                        if ((p.category || '').toLowerCase() !== this.selectedCategory.toLowerCase()) {
                            return false;
                        }
                    }
                    // Tier filter
                    if (this.selectedTier !== 'all') {
                        if ((p.package_tier || '').toLowerCase() !== this.selectedTier.toLowerCase()) {
                            return false;
                        }
                    }
                    // Boilerplate filter
                    if (this.selectedBoilerplate === 'boilerplate' && !p.is_boilerplate) {
                        return false;
                    }
                    if (this.selectedBoilerplate === 'custom' && p.is_boilerplate) {
                        return false;
                    }
                    // Search query
                    if (this.searchQuery.trim() !== '') {
                        const q = this.searchQuery.toLowerCase().trim();
                        const title = (p.title || '').toLowerCase();
                        const desc = (p.description || '').toLowerCase();
                        const client = (p.client || '').toLowerCase();
                        const cat = (p.category || '').toLowerCase();
                        const tier = (p.package_tier || '').toLowerCase();
                        const techs = Array.isArray(p.technologies) ? p.technologies.join(' ').toLowerCase() : (p.technologies || '').toLowerCase();

                        const match = title.includes(q) || desc.includes(q) || client.includes(q) || cat.includes(q) || tier.includes(q) || techs.includes(q);
                        if (!match) return false;
                    }
                    return true;
                });
            },
            get totalPages() {
                return Math.ceil(this.filteredProjects.length / this.perPage) || 1;
            },
            get paginatedProjects() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredProjects.slice(start, start + this.perPage);
            },
            setPage(p) {
                if (p < 1 || p > this.totalPages) return;
                this.currentPage = p;
                const gridElem = document.getElementById('portfolio-grid-anchor');
                if (gridElem) {
                    gridElem.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
                });
            }
        }"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            <!-- Filter & Search Toolbar (Clean, Formal, Modern) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 mb-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                    
                    <!-- Search Input -->
                    <div class="lg:col-span-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            placeholder="Cari judul, tech stack, klien..."
                            class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] focus:bg-white transition-all"
                        >
                        <button 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; currentPage = 1;" 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                        >
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Dropdown Kategori Layanan -->
                    <div class="lg:col-span-3 relative">
                        <select 
                            x-model="selectedCategory"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#2563EB] focus:bg-white cursor-pointer transition-all"
                        >
                            <option value="all">Semua Layanan</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Kategori Paket -->
                    <div class="lg:col-span-2 relative">
                        <select 
                            x-model="selectedTier"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#2563EB] focus:bg-white cursor-pointer transition-all"
                        >
                            <option value="all">Semua Paket</option>
                            <option value="Basic">Paket Basic</option>
                            <option value="Rekomendasi">Paket Rekomendasi</option>
                            <option value="Premium">Paket Premium</option>
                        </select>
                    </div>

                    <!-- Dropdown Boilerplate -->
                    <div class="lg:col-span-2 relative">
                        <select 
                            x-model="selectedBoilerplate"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#2563EB] focus:bg-white cursor-pointer transition-all"
                        >
                            <option value="all">Semua Tipe</option>
                            <option value="boilerplate">Hanya Boilerplate</option>
                            <option value="custom">Proyek Klien</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="lg:col-span-1 flex justify-end">
                        <button 
                            type="button" 
                            @click="resetFilters()"
                            :disabled="!isFiltered"
                            :class="isFiltered ? 'bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 border-slate-300 shadow-2xs cursor-pointer' : 'bg-slate-50 text-slate-300 border-slate-200 cursor-not-allowed opacity-50'"
                            class="w-full py-2.5 px-3 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                            title="Reset Filter"
                        >
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span class="lg:hidden">Reset</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Empty State -->
            <div x-show="filteredProjects.length === 0" x-cloak class="text-center py-16 px-4 bg-white rounded-2xl border-2 border-dashed border-slate-200 mb-8">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                    <i data-lucide="folder-search" class="w-6 h-6"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Portofolio Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-normal">
                    Tidak ada proyek yang sesuai dengan kombinasi filter atau kata kunci pencarian Anda.
                </p>
                <button 
                    type="button" 
                    @click="resetFilters()"
                    class="px-4 py-2 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] transition-all shadow-xs cursor-pointer"
                >
                    Reset Semua Filter
                </button>
            </div>

            <!-- Projects Grid -->
            <div id="portfolio-grid-anchor" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="filteredProjects.length > 0">
                <template x-for="project in paginatedProjects" :key="project.id">
                    <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2 shadow-lg flex flex-col h-full">
                        
                        <!-- Thumbnail Image -->
                        <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] flex items-center justify-center relative overflow-hidden border-b-2 border-slate-200">
                            <template x-if="project.image_url">
                                <img 
                                    :src="project.image_url" 
                                    :alt="project.title" 
                                    loading="lazy"
                                    decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                            </template>
                            <template x-if="!project.image_url">
                                <div class="text-white/20 text-6xl font-black" x-text="project.title.charAt(0)"></div>
                            </template>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span 
                                    class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-extrabold rounded px-2.5 py-0.5 shadow-xs"
                                    x-text="project.category || 'Aplikasi Web'"
                                >
                                </span>
                                <template x-if="project.package_tier">
                                    <span 
                                        class="inline-block bg-amber-400 border border-amber-500/30 text-slate-950 text-[10px] font-extrabold rounded px-2.5 py-0.5 shadow-xs"
                                        x-text="'Paket ' + project.package_tier"
                                    >
                                    </span>
                                </template>
                                <template x-if="project.is_boilerplate">
                                    <span class="inline-block bg-[#2563EB] text-white text-[10px] font-extrabold rounded px-2.5 py-0.5 shadow-xs">
                                        Boilerplate
                                    </span>
                                </template>
                            </div>

                            <a :href="'/portfolio/' + project.slug" class="block">
                                <h3 class="text-xl font-extrabold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors duration-200" x-text="project.title">
                                </h3>
                            </a>

                            <p class="text-slate-600 text-sm leading-relaxed mb-4 flex-grow line-clamp-3" x-text="project.description">
                            </p>

                            <!-- Tech Stack Pills -->
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <template x-for="tech in (Array.isArray(project.technologies) ? project.technologies : [])" :key="tech">
                                    <span class="text-[11px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium" x-text="tech">
                                    </span>
                                </template>
                            </div>

                            <div class="border-t border-slate-100 pt-4 mt-auto">
                                <a 
                                    :href="'/portfolio/' + project.slug" 
                                    class="inline-flex items-center gap-1.5 text-[#2563EB] text-sm font-extrabold hover:gap-2 transition-all duration-200 group/link"
                                >
                                    <span>Lihat Detail Proyek</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-1"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Pagination Controls (Only shown if more than 12 items) -->
            <div x-show="totalPages > 1" x-cloak class="mt-12 sm:mt-16 flex items-center justify-center gap-2">
                <!-- Prev Button -->
                <button 
                    type="button"
                    @click="setPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400 border-slate-200' : 'bg-white text-slate-700 hover:bg-slate-50 hover:text-slate-900 border-slate-200 shadow-xs cursor-pointer'"
                    class="px-3.5 py-2 rounded-xl border text-xs sm:text-sm font-bold transition-all flex items-center gap-1.5"
                >
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    <span>Sebelumnya</span>
                </button>

                <!-- Page Numbers -->
                <div class="flex items-center gap-1.5">
                    <template x-for="p in totalPages" :key="p">
                        <button 
                            type="button"
                            @click="setPage(p)"
                            :class="currentPage === p 
                                ? 'bg-[#0A1E5E] text-[#C7F236] border-[#0A1E5E] font-black shadow-md' 
                                : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 font-bold'"
                            class="w-10 h-10 rounded-xl border text-xs sm:text-sm transition-all flex items-center justify-center cursor-pointer"
                            x-text="p"
                        >
                        </button>
                    </template>
                </div>

                <!-- Next Button -->
                <button 
                    type="button"
                    @click="setPage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400 border-slate-200' : 'bg-white text-slate-700 hover:bg-slate-50 hover:text-slate-900 border-slate-200 shadow-xs cursor-pointer'"
                    class="px-3.5 py-2 rounded-xl border text-xs sm:text-sm font-bold transition-all flex items-center gap-1.5"
                >
                    <span>Selanjutnya</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>

        </div>
    </section>

    <!-- Final CTA -->
    @include('partials.final-cta')
@endsection
