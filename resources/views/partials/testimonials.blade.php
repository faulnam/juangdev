<section class="py-20 md:py-28 lg:py-32 bg-[#0B1126] relative overflow-hidden">
    <!-- Subtle Background Ambient Glows -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#1a2d6b] rounded-full blur-[140px] opacity-30 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-[#14265e] rounded-full blur-[140px] opacity-30 translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
        <div class="mb-14 md:mb-18 text-center max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-white leading-tight tracking-tight mb-4">
                Apa Kata <span class="text-[#C7F236] font-serif italic">Klien Kami</span>
            </h2>
            <p class="text-[#94a3b8] text-base md:text-[1.05rem] leading-relaxed max-w-2xl mx-auto font-medium">
                Ulasan jujur dari para pemilik bisnis yang telah memercayakan platform digital mereka bersama tim JuangDev.
            </p>
        </div>
    </div>

    <!-- Infinite Continuous Looping Marquee Slider -->
    <div class="relative w-full overflow-hidden py-4">
        <!-- Gradient Edge Fades for Smooth Appearance -->
        <div class="absolute left-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-r from-[#0B1126] to-transparent z-20 pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-l from-[#0B1126] to-transparent z-20 pointer-events-none"></div>
        
        <style>
            @keyframes marqueeLoop {
                0% { transform: translateX(0%); }
                100% { transform: translateX(-50%); }
            }
            .marquee-track {
                display: flex;
                width: max-content;
                animation: marqueeLoop 40s linear infinite;
            }
            .marquee-track:hover {
                animation-play-state: paused;
            }
        </style>

        <div class="marquee-track flex gap-6 px-4">
            {{-- Loop twice for continuous seamless loop --}}
            @for ($loopCount = 0; $loopCount < 2; $loopCount++)
                @foreach($testimonials as $testimonial)
                    <div class="w-[300px] sm:w-[350px] shrink-0 select-none">
                        <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-xl h-full flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 border border-slate-100/90">
                            <div>
                                <!-- Rating Bintang -->
                                <div class="flex items-center gap-1 mb-4">
                                    @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                                        <i data-lucide="star" class="w-4 h-4 fill-[#eab308] text-[#eab308]"></i>
                                    @endfor
                                </div>
                                
                                <!-- Isi Review -->
                                <p class="text-slate-700 text-sm sm:text-[0.925rem] leading-relaxed font-medium mb-5">
                                    &ldquo;{{ $testimonial->content }}&rdquo;
                                </p>
                            </div>

                            <!-- Nama Klien -->
                            <div class="pt-3.5 border-t border-slate-100">
                                <h4 class="text-sm sm:text-[0.95rem] font-bold text-slate-900 leading-tight">
                                    {{ $testimonial->name }}
                                </h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endfor
        </div>
    </div>
</section>
