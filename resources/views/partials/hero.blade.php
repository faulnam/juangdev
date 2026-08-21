@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai pembuatan website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

<section 
    id="hero" 
    class="relative min-h-[100dvh] flex items-center justify-center overflow-hidden"
    style="background: linear-gradient(160deg, #1a3fa0 0%, #122d78 45%, #0A1E5E 100%);"
    x-data="{
        pricingList: [
            { price: '99K', label: 'Landing Page' },
            { price: '199K', label: 'Company Profile' },
            { price: '399K', label: 'Toko Online (E-Commerce)' },
            { price: '499K', label: 'Sistem Informasi' },
            { price: '999K', label: 'Aplikasi Web Kustom' }
        ],
        currentIndex: 0,
        init() {
            setInterval(() => {
                this.currentIndex = (this.currentIndex + 1) % this.pricingList.length;
            }, 3000);
        }
    }"
>
    <!-- Background Radial Glows & Grid Pattern -->
    <div class="absolute inset-0 pointer-events-none select-none overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
        <div class="absolute top-[-5%] left-[-15%] w-[900px] h-[900px] rounded-full blur-[180px]" style="background: radial-gradient(circle, rgba(37,99,235,0.40) 0%, transparent 70%);"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[700px] h-[700px] rounded-full blur-[140px]" style="background: radial-gradient(circle, rgba(37,99,235,0.30) 0%, transparent 70%);"></div>
        <div class="absolute top-[20%] right-0 w-[500px] h-[500px] rounded-full bg-[#2563EB]/25 blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 pt-28 sm:pt-32 lg:pt-36 pb-16 sm:pb-20">
        <div class="flex flex-col-reverse lg:flex-row gap-12 lg:gap-8 items-center justify-between">
            
            <!-- Left Headline Content -->
            <div class="w-full lg:w-[58%] flex flex-col items-start text-left">
                <h1 class="max-w-2xl text-left">
                    <span class="block text-[2rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.12] tracking-tight">
                        Bangun <span class="font-serif italic text-[#C7F236] text-[1.05em] font-medium tracking-normal">Website</span> &amp;
                    </span>
                    <span class="block text-[2rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.12] tracking-tight mt-1 sm:mt-2">
                        Aplikasi <span class="font-serif italic text-[#C7F236] text-[1.05em] font-medium tracking-normal">Web Kustom</span>
                    </span>
                    <span class="block text-[2rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.12] tracking-tight mt-1 sm:mt-2">
                        Yang Memajukan Bisnis Anda.
                    </span>
                </h1>

                <p class="text-[0.95rem] sm:text-[1.125rem] lg:text-[1.2rem] text-white/75 leading-relaxed max-w-xl mt-5 font-medium">
                    JuangDev membantu bisnis, UMKM, dan perusahaan membangun website profesional, aplikasi web kustom, toko online, sistem informasi, dan solusi digital modern yang mempercepat pertumbuhan bisnis.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-start mt-8 w-full sm:w-auto">
                    <a 
                        href="{{ $whatsappUrl }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-bold bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_30px_-5px_rgba(199,242,54,0.4)] hover:shadow-[0_0_40px_-5px_rgba(199,242,54,0.6)] transition-all duration-300 group"
                    >
                        <span>Mulai Proyek Anda</span>
                        <i data-lucide="arrow-up-right" class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </a>
                    
                    <a 
                        href="{{ route('portfolio') }}" 
                        class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-bold bg-transparent border-2 border-white/20 text-white hover:bg-white/10 hover:border-white/40 backdrop-blur-md transition-all duration-200"
                    >
                        <span>Lihat Portofolio</span>
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap items-center gap-6 sm:gap-8 mt-10">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="star" class="w-4 h-4 fill-[#C7F236] text-[#C7F236]"></i>
                        <div>
                            <span class="text-white font-bold text-sm">5.0</span>
                            <span class="text-white/60 text-xs ml-1 font-medium">Penilaian</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <i data-lucide="folder-open" class="w-4 h-4 text-[#C7F236]"></i>
                        <div>
                            <span class="text-white font-bold text-sm">100+</span>
                            <span class="text-white/60 text-xs ml-1 font-medium">Proyek Selesai</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <i data-lucide="users" class="w-4 h-4 text-[#C7F236]"></i>
                        <div>
                            <span class="text-white font-bold text-sm">50+</span>
                            <span class="text-white/60 text-xs ml-1 font-medium">Klien Puas</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-[#C7F236]"></i>
                        <div>
                            <span class="text-white font-bold text-sm">3+</span>
                            <span class="text-white/60 text-xs ml-1 font-medium">Tahun Pengalaman</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Mockup & Interactive Floating Badges -->
            <div class="w-full lg:w-[40%] relative h-[340px] sm:h-[420px] lg:h-[480px] flex items-center justify-center lg:-translate-y-6">
                <!-- Center Glow -->
                <div class="absolute inset-0 bg-gradient-to-tr from-[#2563EB]/25 to-transparent rounded-full blur-3xl pointer-events-none"></div>

                <!-- Center Person with Green Arch Shape -->
                <div class="relative w-[280px] sm:w-[360px] h-[340px] sm:h-[460px] flex items-end justify-center">
                    <div class="absolute bottom-0 w-[85%] h-[75%] bg-[#c6f036] rounded-[2.5rem] sm:rounded-[3rem]"></div>
                    <img 
                        src="/orang.png" 
                        alt="JuangDev Client" 
                        class="relative z-10 w-[95%] h-auto object-contain drop-shadow-2xl"
                    >
                </div>

                <!-- Floating Card: 99% Client Satisfaction -->
                <div 
                    class="absolute left-[-5%] sm:left-[-12%] top-[20%] z-20 bg-white border-2 border-slate-200 border-b-[5px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-5 rounded-xl sm:rounded-2xl flex flex-col items-center text-center cursor-default animate-[float_4s_ease-in-out_infinite]"
                >
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#c6f036] text-[#0A1E5E] flex items-center justify-center shrink-0 mb-1 sm:mb-2 shadow-[0_4px_12px_rgba(199,242,54,0.35)]">
                        <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                    </div>
                    <p class="text-base sm:text-lg font-black text-slate-900 leading-none mb-0.5">99%</p>
                    <p class="text-[8px] sm:text-[10px] text-slate-600 font-bold uppercase tracking-wider">Kepuasan Klien</p>
                </div>

                <!-- Floating Card: 5.0 Rating -->
                <div 
                    class="absolute right-[-2%] sm:right-[-8%] top-[10%] sm:top-[15%] z-20 bg-white border-2 border-slate-200 border-b-[5px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default animate-[float_3.5s_ease-in-out_infinite_0.5s]"
                >
                    <p class="text-sm sm:text-xl font-black text-slate-900 leading-none mb-1">⭐ 5.0 Penilaian</p>
                    <p class="text-[8px] sm:text-[10px] text-slate-600 font-bold uppercase tracking-wider">100+ Proyek Selesai</p>
                </div>

                <!-- Floating Card: 24/7 Support -->
                <div 
                    class="absolute right-[-6%] sm:right-[-12%] top-[48%] z-20 bg-white border-2 border-slate-200 border-b-[5px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default animate-[float_4.2s_ease-in-out_infinite_1s]"
                >
                    <p class="text-sm sm:text-2xl font-black text-slate-900 leading-none mb-1">24/7</p>
                    <p class="text-[8px] sm:text-[10px] text-slate-600 font-bold uppercase tracking-wider">Dukungan Siap Bantu</p>
                </div>

                <!-- Floating Card: Fast Delivery -->
                <div 
                    class="absolute left-[-2%] sm:left-[-8%] bottom-[12%] z-20 bg-white border-2 border-slate-200 border-b-[5px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default animate-[float_3.8s_ease-in-out_infinite_0.8s]"
                >
                    <p class="text-sm sm:text-lg font-black text-slate-900 leading-none mb-1">Pengerjaan Cepat</p>
                    <p class="text-[8px] sm:text-[10px] text-slate-600 font-bold uppercase tracking-wider">Kualitas Premium</p>
                </div>

                <!-- Floating Dynamic Pricing Carousel Card -->
                <div 
                    class="absolute right-[2%] sm:right-[0%] bottom-[0%] sm:bottom-[-4%] z-20 bg-white border-2 border-slate-200 border-b-[5px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default overflow-hidden min-w-[140px] sm:min-w-[180px] animate-[float_4.5s_ease-in-out_infinite_1.5s]"
                >
                    <div class="relative w-full flex flex-col">
                        <template x-for="(item, idx) in pricingList" :key="idx">
                            <div 
                                x-show="currentIndex === idx"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-3"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200 absolute"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-3"
                                class="flex flex-col w-full"
                            >
                                <p class="text-sm sm:text-xl font-black text-slate-900 leading-none mb-1" x-text="'Mulai ' + item.price"></p>
                                <p class="text-[8px] sm:text-[10px] text-slate-600 font-bold uppercase tracking-wider min-h-[15px]" x-text="item.label"></p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
