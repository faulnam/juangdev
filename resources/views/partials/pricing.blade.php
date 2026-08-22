@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    
    // Group pricing plans by category
    $categories = [
        'landing-page' => 'Landing Page',
        'company-profile' => 'Company Profile',
        'ecommerce' => 'E-Commerce',
        'sistem-informasi' => 'Sistem Informasi',
        'custom-app' => 'Custom Web App',
    ];

    $plansByCategory = [];
    foreach ($categories as $catKey => $catLabel) {
        $plansByCategory[$catKey] = $pricingPlans->where('category', $catKey)->values();
    }
@endphp

<section 
    id="pricing" 
    class="py-16 md:py-24 lg:py-28 bg-[#f8f9fc]"
    x-data="{
        activeCategory: (new URLSearchParams(window.location.search)).get('category') || 'landing-page',
        categories: {{ json_encode($categories) }},
        plans: {{ json_encode($plansByCategory) }},
        whatsappBase: 'https://wa.me/{{ $whatsappNumber }}',
        selectPlan(plan) {
            window.dispatchEvent(new CustomEvent('select-estimate-plan', { 
                detail: { 
                    category: plan.category, 
                    planId: plan.id 
                } 
            }));
            const estimatorEl = document.getElementById('estimator');
            if (estimatorEl) {
                estimatorEl.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }"
>
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-10 md:mb-14">
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                Paket &amp; Investasi Terbaik Untuk <span class="text-[#2563EB] font-serif italic">Bisnis Anda</span>
            </h2>
            <p class="text-slate-600 text-[0.95rem] md:text-base leading-relaxed max-w-2xl mx-auto font-medium">
                Harga transparan, tanpa biaya tersembunyi. Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda.
            </p>
        </div>

        <!-- Categories Filter / Tabs -->
        <div class="flex overflow-x-auto gap-3 sm:gap-4 pb-4 mb-2 justify-start lg:justify-center snap-x snap-mandatory scroll-smooth hide-scrollbar px-1 pt-2">
            <template x-for="(label, key) in categories" :key="key">
                <button 
                    @click="activeCategory = key"
                    :class="activeCategory === key 
                        ? 'bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-[0_4px_0_#1e3a8a] -translate-y-1' 
                        : 'bg-white text-[#4f5b7d] border-2 border-slate-200 shadow-[0_4px_0_#e2e8f0] hover:border-[#2563EB] hover:text-[#2563EB] hover:shadow-[0_4px_0_#2563EB] hover:-translate-y-1'"
                    class="px-5 sm:px-6 py-2.5 rounded-xl font-bold text-[0.925rem] transition-all duration-200 whitespace-nowrap shrink-0 snap-start active:shadow-none active:translate-y-[3px]"
                    x-text="label"
                >
                </button>
            </template>
        </div>

        <!-- Custom App Information Banner (Clean & Formal) -->
        <div 
            x-show="activeCategory === 'custom-app'" 
            x-cloak
            class="max-w-3xl mx-auto mb-8 px-4.5 py-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3 text-left"
        >
            <i data-lucide="info" class="w-4 h-4 text-slate-500 shrink-0"></i>
            <p class="text-xs sm:text-[0.825rem] text-slate-600 leading-relaxed">
                <span class="font-bold text-slate-800">Fleksibilitas Layanan:</span> 
                Paket Custom Web App dapat menggabungkan fitur dari seluruh layanan (Landing Page, Company Profile, Toko Online, Sistem Informasi) sesuai kebutuhan proyek Anda.
            </p>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory scroll-smooth hide-scrollbar -mx-6 md:grid md:grid-cols-3 md:gap-8 md:items-stretch md:mx-0 px-6 md:px-0">
            <template x-for="(plan, idx) in (plans[activeCategory] || [])" :key="plan.id">
                <div class="w-[82vw] sm:w-[350px] md:w-auto shrink-0 snap-start snap-always">
                    <div 
                        :class="plan.popular 
                            ? 'bg-gradient-to-br from-[#c8f135] to-[#a3c922] border-2 border-[#a3c922] border-b-[8px] border-b-[#82a313] shadow-2xl shadow-[#C7F236]/20 scale-[1.02] md:scale-105' 
                            : 'bg-white border-2 border-slate-200 border-b-[8px] border-b-slate-300 shadow-xl shadow-slate-200/50'"
                        class="flex flex-col rounded-[2rem] overflow-hidden relative transition-all duration-300 h-full hover:-translate-y-2"
                    >
                        <!-- Popular Badge -->
                        <div x-show="plan.popular || plan.badge" class="absolute top-5 right-5">
                            <span class="bg-[#0A1E5E] text-white text-[0.65rem] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-lg" x-text="plan.badge || 'Paling Populer'">
                            </span>
                        </div>

                        <!-- Card Header -->
                        <div 
                            :class="plan.popular ? 'border-[#0A1E5E]/10' : 'border-slate-100'"
                            class="px-8 pt-9 pb-7 border-b-2"
                        >
                            <h4 
                                :class="plan.popular ? 'text-[#0A1E5E]' : 'text-[#1a1f3c]'"
                                class="text-2xl font-black mb-2"
                                x-text="plan.name"
                            ></h4>
                            
                            <p 
                                :class="plan.popular ? 'text-[#0A1E5E]/75' : 'text-[#4f5b7d]'"
                                class="text-[0.85rem] leading-relaxed pr-8 font-medium min-h-[48px]"
                                x-text="plan.description"
                            ></p>

                            <div class="mt-6 flex items-baseline gap-1.5 flex-wrap">
                                <span 
                                    :class="[
                                        plan.popular ? 'text-[#0A1E5E]' : 'text-[#1a1f3c]',
                                        plan.price && plan.price.length > 7 ? 'text-[1.35rem] sm:text-[1.65rem] lg:text-[1.75rem]' : 'text-[1.85rem] sm:text-[2.25rem]'
                                    ]"
                                    class="font-black tracking-tight leading-none"
                                >
                                    <span class="text-base sm:text-lg font-bold mr-1">Rp</span><span x-text="plan.price"></span>
                                </span>
                                <span 
                                    x-show="plan.period" 
                                    :class="plan.popular ? 'text-[#0A1E5E]/60' : 'text-slate-500'"
                                    class="text-xs sm:text-sm font-semibold"
                                    x-text="'/' + plan.period"
                                ></span>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="px-8 py-7 flex-1 flex flex-col justify-between bg-gradient-to-b from-transparent to-black/[0.02]">
                            <ul class="flex flex-col gap-3.5 mb-8 flex-1">
                                <template x-for="feature in (Array.isArray(plan.features) ? plan.features : (plan.features ? plan.features.split(',') : []))" :key="feature">
                                    <li class="flex items-start gap-3.5">
                                        <div 
                                            :class="plan.popular ? 'bg-[#0A1E5E] text-[#C7F236]' : 'bg-[#2563EB]/10 text-[#2563EB]'"
                                            class="w-[1.125rem] h-[1.125rem] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                        >
                                            <i data-lucide="check" class="w-3 h-3 stroke-[3]"></i>
                                        </div>
                                        <span 
                                            :class="plan.popular ? 'text-[#0A1E5E]/90' : 'text-slate-700'"
                                            class="text-[0.9rem] font-semibold leading-snug"
                                            x-text="feature.trim()"
                                        ></span>
                                    </li>
                                </template>
                            </ul>

                            <!-- Choose Button -->
                            <button 
                                type="button"
                                @click="selectPlan(plan)"
                                :class="plan.popular 
                                    ? 'bg-[#0A1E5E] text-white hover:bg-[#071542] shadow-xl shadow-[#0A1E5E]/20' 
                                    : 'bg-white border-2 border-slate-200 text-[#1a1f3c] hover:border-[#2563EB] hover:text-[#2563EB] shadow-md'"
                                class="inline-flex items-center justify-center gap-2 rounded-full py-4 text-[0.9rem] font-bold transition-all duration-300 group w-full cursor-pointer"
                            >
                                <span>Pilih Paket Ini</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 stroke-[2.5]"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </template>
        </div>

    </div>
</section>
