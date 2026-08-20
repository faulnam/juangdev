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
                    @php
                        $initials = collect(explode(' ', $testimonial->name))->map(fn($n) => $n[0] ?? '')->take(2)->join('');
                    @endphp
                    <div class="w-[320px] sm:w-[380px] shrink-0 select-none">
                        <div class="bg-white rounded-[1.75rem] p-7 md:p-8 shadow-2xl h-full flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1.5 border border-slate-100">
                            <div>
                                <!-- Large Serif Quote Mark -->
                                <div class="text-[#a5b4fc] font-serif text-[3.75rem] leading-none h-8 mb-5 opacity-70">
                                    &ldquo;
                                </div>
                                
                                <!-- Review Content -->
                                <p class="text-[#1e293b] text-[0.925rem] leading-relaxed font-semibold mb-6">
                                    {{ $testimonial->content }}
                                </p>
                            </div>

                            <div>
                                <!-- Star Rating -->
                                <div class="flex items-center gap-1 mb-4">
                                    @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                                        <i data-lucide="star" class="w-4 h-4 fill-[#eab308] text-[#eab308]"></i>
                                    @endfor
                                </div>

                                <!-- Client Avatar & Details -->
                                <div class="flex items-center gap-3.5 pt-3 border-t border-slate-100">
                                    @if($testimonial->avatar_url)
                                        <img 
                                            src="{{ $testimonial->avatar_url }}" 
                                            alt="{{ $testimonial->name }}" 
                                            class="w-11 h-11 rounded-full object-cover shrink-0 ring-2 ring-blue-500/20"
                                        >
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-[#1e40af] flex items-center justify-center text-white text-[0.85rem] font-bold shrink-0">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    
                                    <div class="flex flex-col min-w-0">
                                        <p class="text-[0.95rem] font-bold text-[#0f172a] leading-tight truncate">
                                            {{ $testimonial->name }}
                                        </p>
                                        <p class="text-[0.8rem] font-semibold text-[#64748b] mt-0.5 truncate">
                                            {{ $testimonial->role ?? 'Klien' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endfor
        </div>
    </div>
</section>
