@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Hello JuangDev, I would like to start a project.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div 
            class="relative rounded-[2.5rem] overflow-hidden p-8 sm:p-12 md:p-16 text-center text-white shadow-2xl"
            style="background: linear-gradient(135deg, #0A1E5E 0%, #1a3fa0 50%, #2563EB 100%);"
        >
            <!-- Decorative Glows -->
            <div class="absolute top-0 right-0 w-80 h-80 bg-[#C7F236]/20 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#2563EB]/40 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight mb-6">
                    Wujudkan Website Impian Bisnis Anda Bersama <span class="text-[#C7F236] font-serif italic">JuangDev</span>
                </h2>

                <p class="text-white/80 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto mb-10 font-medium">
                    Konsultasikan ide dan kebutuhan proyek Anda secara gratis bersama tim ahli kami. Kami siap memberikan solusi digital terbaik yang tepat sasaran.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a 
                        href="{{ $whatsappUrl }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-bold bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_25px_-5px_rgba(199,242,54,0.4)] transition-all duration-300 w-full sm:w-auto group"
                    >
                        <span>Konsultasi via WhatsApp</span>
                        <i data-lucide="arrow-up-right" class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </a>

                    <a 
                        href="{{ route('contact') }}" 
                        class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-bold bg-white/10 text-white border-2 border-white/20 hover:bg-white/20 transition-all duration-200 w-full sm:w-auto"
                    >
                        <span>Kirim Pesan Kontak</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
