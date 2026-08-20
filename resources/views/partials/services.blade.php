@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo Tim JuangDev, saya ingin order dan konsultasi pembuatan website/sistem digital.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $colorClasses = [
        'lime' => [
            'card' => 'bg-gradient-to-br from-[#C7F236] to-[#b0d62a] border-2 border-[#b0d62a] border-b-[8px] border-b-[#8da61b]',
            'title' => 'text-[#0A1E5E]',
            'desc' => 'text-[#0A1E5E]/80',
            'price' => 'text-[#0A1E5E]',
            'priceValue' => 'text-[#0A1E5E] font-extrabold',
            'btn' => 'border-[#0A1E5E] text-[#0A1E5E] hover:bg-[#0A1E5E] hover:text-[#C7F236]',
            'icon' => 'bg-[#0A1E5E]/10 text-[#0A1E5E]',
            'tag' => 'bg-[#0A1E5E]/10 border-[#0A1E5E]/20 text-[#0A1E5E]',
        ],
        'blue' => [
            'card' => 'bg-gradient-to-br from-[#2563EB] to-[#0A1E5E] border-2 border-[#2563EB]/40 border-b-[8px] border-b-[#071542]',
            'title' => 'text-white',
            'desc' => 'text-white/85',
            'price' => 'text-white/75',
            'priceValue' => 'text-[#C7F236] font-extrabold',
            'btn' => 'border-white text-white hover:bg-white hover:text-[#0A1E5E]',
            'icon' => 'bg-white/15 text-white',
            'tag' => 'bg-white/10 border-white/20 text-white/90',
        ],
        'white' => [
            'card' => 'bg-gradient-to-br from-white to-[#f8f9fc] border-2 border-slate-200 border-b-[8px] border-b-slate-300',
            'title' => 'text-[#1e2547]',
            'desc' => 'text-[#4f5b7d]',
            'price' => 'text-[#4f5b7d]',
            'priceValue' => 'text-[#2563EB] font-extrabold',
            'btn' => 'border-[#1e2547] text-[#1e2547] hover:bg-[#1e2547] hover:text-white',
            'icon' => 'bg-[#2563EB]/10 text-[#2563EB]',
            'tag' => 'bg-slate-100 border-slate-200 text-slate-700',
        ],
    ];

    $iconLucide = [
        'globe' => 'globe',
        'Globe' => 'globe',
        'monitor' => 'monitor',
        'Monitor' => 'monitor',
        'shopping-bag' => 'shopping-bag',
        'ShoppingBag' => 'shopping-bag',
        'bot' => 'bot',
        'Bot' => 'bot',
        'palette' => 'palette',
        'Palette' => 'palette',
    ];
@endphp

<section id="services" class="py-16 md:py-24 lg:py-28 bg-[#f8f9fc]">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        
        <!-- Section Header -->
        <div class="max-w-3xl mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                JuangDev adalah <span class="text-[#2563EB] font-serif italic">studio kreatif modern</span> yang membantu bisnis membangun solusi teknologi kustom.
            </h2>
        </div>

        <!-- Service Cards Grid (3x2 Balanced Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $index => $service)
                @php
                    $color = $index == 0 ? 'lime' : ($index == 1 ? 'blue' : ($index == 2 ? 'white' : ($index == 3 ? 'blue' : 'lime')));
                    $style = $colorClasses[$color];
                    $icon = $iconLucide[$service->icon] ?? 'globe';
                    $featureList = is_array($service->features) ? $service->features : explode(',', $service->features ?? '');
                @endphp
                
                <div class="flex flex-col justify-between h-full rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-xl hover:scale-[1.02] transition-all duration-300 {{ $style['card'] }}">
                    <div class="flex flex-col gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $style['icon'] }}">
                            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight {{ $style['title'] }}">
                            {{ $service->name }}
                        </h3>
                        
                        <p class="text-sm leading-relaxed {{ $style['desc'] }}">
                            {{ $service->description }}
                        </p>
                        
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach($featureList as $feature)
                                @if(trim($feature))
                                    <span class="text-[10px] border px-2 py-0.5 rounded font-semibold {{ $style['tag'] }}">
                                        {{ trim($feature) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3">
                        <div class="text-xs {{ $style['price'] }}">
                            Mulai dari <span class="text-base {{ $style['priceValue'] }}">{{ $service->starting_price ?? '99K' }}</span>
                        </div>
                        
                        <a 
                            href="{{ route('contact', ['service' => $service->slug]) }}" 
                            class="inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 text-sm bg-transparent border-2 w-fit py-1.5 px-4 rounded-xl group {{ $style['btn'] }}"
                        >
                            <span>Lihat Detail</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- 6th Slot: Clean White Card with Person & Comic Speech Bubble pointing to mouth -->
            <div class="flex items-end justify-center h-full rounded-2xl relative overflow-hidden shadow-xl hover:scale-[1.02] transition-all duration-300 bg-white border-2 border-slate-200 border-b-[8px] border-b-slate-300 min-h-[380px] sm:min-h-[420px] group pt-6 px-4">
                
                <!-- Comic Speech Bubble pointing near his mouth -->
                <a 
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="absolute left-3 sm:left-5 top-[10%] sm:top-[12%] z-20 hover:scale-105 transition-transform duration-200 group/bubble cursor-pointer"
                >
                    <div class="relative bg-white border-2 border-slate-800 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] py-2 sm:py-2.5 px-3.5 sm:px-4 rounded-2xl flex items-center gap-1.5">
                        <span class="text-xs sm:text-sm font-black text-slate-900 leading-tight group-hover/bubble:text-[#2563EB]">
                            Yuk Order Sekarang! 👋
                        </span>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-[#2563EB] stroke-[3]"></i>

                        <!-- Speech Bubble Tail pointing down-right towards mouth -->
                        <div class="absolute -bottom-2 right-4 w-3.5 h-3.5 bg-white border-r-2 border-b-2 border-slate-800 rotate-45"></div>
                    </div>
                </a>

                <!-- Large Person Image -->
                <img 
                    src="/orang.png" 
                    alt="JuangDev Person" 
                    class="w-full h-full max-h-[400px] object-contain object-bottom transition-transform duration-500 group-hover:scale-105"
                >
            </div>

        </div>

    </div>
</section>
