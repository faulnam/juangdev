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
        class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden text-white text-center"
        style="background: linear-gradient(160deg, #1a3fa0 0%, #122d78 45%, #0A1E5E 100%);"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight mb-6">
                Portofolio Proyek &amp; <span class="font-serif italic text-[#C7F236]">Studi Kasus</span>
            </h1>
            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                Jelajahi berbagai proyek nyata yang telah kami bangun untuk beragam industri — mulai dari bisnis berkembang hingga perusahaan besar.
            </p>
        </div>
    </section>

    <!-- Portfolio Grid with Alpine.js Category Filter -->
    <section 
        class="py-16 md:py-24 bg-[#f8f9fc]"
        x-data="{
            selectedCategory: 'all',
            projects: {{ json_encode($portfolios) }},
            get filteredProjects() {
                if (this.selectedCategory === 'all') return this.projects;
                return this.projects.filter(p => (p.category || '').toLowerCase() === this.selectedCategory.toLowerCase());
            }
        }"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
                <button 
                    @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' 
                        ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-md shadow-[#2563EB]/25' 
                        : 'bg-white text-slate-700 border-2 border-slate-200 hover:border-slate-300'"
                    class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-200"
                >
                    Semua Proyek ({{ $portfolios->count() }})
                </button>

                @foreach($allCategories as $cat)
                    <button 
                        @click="selectedCategory = '{{ $cat }}'"
                        :class="selectedCategory.toLowerCase() === '{{ strtolower($cat) }}' 
                            ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-md shadow-[#2563EB]/25' 
                            : 'bg-white text-slate-700 border-2 border-slate-200 hover:border-slate-300'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-200"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="project in filteredProjects" :key="project.id">
                    <div class="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2 shadow-lg flex flex-col h-full">
                        
                        <!-- Thumbnail Image -->
                        <div class="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] flex items-center justify-center relative overflow-hidden border-b-2 border-slate-200">
                            <template x-if="project.image_url">
                                <img 
                                    :src="project.image_url" 
                                    :alt="project.title" 
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
                            <div class="mb-3">
                                <span 
                                    class="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-extrabold rounded px-2.5 py-0.5 shadow-xs"
                                    x-text="project.category || 'Aplikasi Web'"
                                >
                                </span>
                            </div>

                            <h3 class="text-xl font-extrabold text-slate-900 mb-2 group-hover:text-[#2563EB] transition-colors duration-200" x-text="project.title">
                            </h3>

                            <p class="text-slate-600 text-sm leading-relaxed mb-4 flex-grow" x-text="project.description">
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
                                    :href="project.live_url || '{{ $whatsappUrl }}'" 
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
                </template>
            </div>

        </div>
    </section>

    <!-- Final CTA -->
    @include('partials.final-cta')
@endsection
