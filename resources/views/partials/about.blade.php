<section id="about" class="hidden lg:block py-14 md:py-20 lg:py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative">

        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-3">
                {{ $settings['about_title_1'] ?? 'Tentang' }} <span class="text-[#2563EB] font-serif italic">{{ $settings['about_title_highlight'] ?? 'JuangDev' }}</span>
            </h2>
            <p class="text-slate-600 text-[0.925rem] md:text-base leading-relaxed max-w-2xl mx-auto font-medium">
                {{ $settings['about_desc'] ?? 'JuangDev hadir sebagai studio teknologi dan mitra strategis yang berfokus membangun website profesional, aplikasi web kustom, dan produk digital inovatif yang dirancang khusus untuk mengakselerasi pertumbuhan bisnis Anda.' }}
            </p>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-[1.25fr_1fr_1fr] gap-6 items-stretch">

            <!-- LEFT COLUMN – Unified Seamless Card (Laptop Top + Stats Bottom) -->
            <div class="rounded-[2rem] bg-[#1a3a8f] shadow-[0_20px_50px_-15px_rgba(26,58,143,0.4)] flex flex-col justify-between overflow-hidden h-full border border-blue-900/40">
                <!-- Laptop Mockup Top Section (Full Uncut Image, Hidden on Mobile) -->
                <div class="hidden lg:flex relative bg-[#05111f] p-5 sm:p-6 flex-1 items-center justify-center min-h-[220px] overflow-hidden">
                    <img 
                        src="{{ $settings['about_card1_image'] ?? '/about-laptop.png' }}" 
                        alt="JuangDev laptop mockup" 
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full max-h-[210px] object-contain object-center opacity-95 transition-transform duration-500 hover:scale-105"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#05111f]/70 via-transparent to-transparent pointer-events-none"></div>
                </div>

                <!-- Stats Section Connected Directly at Bottom (Nyambung) -->
                <div class="p-7 sm:p-8 lg:p-6 bg-[#1a3a8f] lg:border-t lg:border-white/15 flex flex-row items-center justify-center gap-6 shrink-0">
                    <div class="text-center flex-1">
                        <p class="text-3xl sm:text-4xl font-black text-[#C7F236] leading-none mb-1.5 tracking-tighter">
                            {{ $settings['about_card1_stat1_val'] ?? '100%' }}
                        </p>
                        <p class="text-[10px] sm:text-[11px] font-bold text-white/80 uppercase tracking-wider leading-tight">
                            {{ $settings['about_card1_stat1_label'] ?? 'Transparansi Penuh' }}
                        </p>
                    </div>

                    <div class="w-px h-11 bg-white/20 shrink-0"></div>

                    <div class="text-center flex-1">
                        <p class="text-3xl sm:text-4xl font-black text-white leading-none mb-1.5 tracking-tighter">
                            {{ $settings['about_card1_stat2_val'] ?? '30 Hari' }}
                        </p>
                        <p class="text-[10px] sm:text-[11px] font-bold text-white/80 uppercase tracking-wider leading-tight">
                            {{ $settings['about_card1_stat2_label'] ?? 'Dukungan Purna Jual' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- MIDDLE COLUMN – Lime Card -->
            <div class="rounded-[2rem] bg-[#c8f135] shadow-[0_20px_50px_-15px_rgba(200,241,53,0.35)] flex flex-col justify-between overflow-hidden h-full">
                <div class="p-7 sm:p-8 lg:pb-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-[#0f1f5c] mb-2.5 leading-snug tracking-tight">
                        {{ $settings['about_card2_title'] ?? 'Solusi Digital Untuk Bisnis Anda' }}
                    </h3>
                    <p class="text-[#0f1f5c]/85 text-xs sm:text-[0.85rem] leading-relaxed font-medium">
                        {{ $settings['about_card2_desc'] ?? 'Kami menghadirkan layanan pembuatan website kustom mulai dari Landing Page, Company Profile, hingga E-Commerce yang cepat, responsif, dan ramah SEO.' }}
                    </p>
                </div>

                <!-- Tablet Image Flush to Bottom (Hidden on Mobile) -->
                <div class="hidden lg:block mt-4 relative h-44 sm:h-48 overflow-hidden w-full shrink-0">
                    <img 
                        src="{{ $settings['about_card2_image'] ?? '/about-tablet.png' }}" 
                        alt="Dashboard analytics on tablet" 
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover object-top transition-transform duration-500 hover:scale-105"
                    >
                </div>
            </div>

            <!-- RIGHT COLUMN – Navy Card -->
            <div class="rounded-[2rem] bg-[#1a3a8f] shadow-[0_20px_50px_-15px_rgba(26,58,143,0.4)] flex flex-col justify-between overflow-hidden h-full">
                <!-- Team Image Flush to Top (Hidden on Mobile) -->
                <div class="hidden lg:block relative h-44 sm:h-48 overflow-hidden w-full shrink-0 bg-slate-100">
                    <img 
                        src="{{ $settings['about_card3_image'] ?? '/about-team.png' }}" 
                        alt="JuangDev team collaboration" 
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105"
                    >
                </div>

                <div class="p-7 sm:p-8 lg:pt-4 flex-1 flex flex-col justify-center lg:justify-end">
                    <h3 class="text-2xl sm:text-3xl font-black text-white mb-2.5 leading-snug tracking-tight">
                        {{ $settings['about_card3_title'] ?? 'Transparansi & Dukungan Berkelanjutan' }}
                    </h3>
                    <p class="text-white/80 text-xs sm:text-[0.85rem] leading-relaxed font-medium">
                        {{ $settings['about_card3_desc'] ?? 'Kami memberikan transparansi penuh mulai dari pembaruan progres mingguan hingga serah terima lengkap, didukung oleh layanan perawatan secara berkala.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
