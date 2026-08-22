@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai pembuatan website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $dbSteps = \App\Models\ProcessStep::where('is_active', true)->orderBy('display_order')->get();

    if ($dbSteps->count() > 0) {
        $steps = $dbSteps->map(function($s, $idx) {
            return [
                'id' => $s->step_number ?? sprintf('%02d', $idx + 1),
                'icon' => $s->icon ?? 'monitor',
                'title' => $s->title,
                'description' => $s->description,
            ];
        })->toArray();
    } else {
        $steps = [
            [
                'id' => '01',
                'icon' => 'monitor',
                'title' => 'Konsultasi & Penentuan Kebutuhan',
                'description' => 'Sampaikan kebutuhan bisnis Anda, mulai dari jenis platform yang ingin dibangun, fitur utama yang dibutuhkan, hingga target pengunjung yang ingin dicapai.',
            ],
            [
                'id' => '02',
                'icon' => 'lightbulb',
                'title' => 'Rekomendasi Solusi & Paket',
                'description' => 'Tim kami akan menganalisis kebutuhan Anda dan menyarankan jenis website/aplikasi serta paket investasi yang paling efektif dan efisien untuk bisnis Anda.',
            ],
        ];
    }

    $bullets = [
        'Alur kerja adaptif & fleksibel',
        'Diskusi & konsultasi berkala',
        'Revisi berorientasi solusi sesuai kesepakatan',
        'Dukungan penuh setelah proyek dirilis',
    ];
@endphp

<section id="process" class="py-16 md:py-24 lg:py-28 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.1fr] gap-12 lg:gap-24">
            
            <!-- Left Column: Content -->
            <div class="flex flex-col gap-6 lg:sticky lg:top-32 self-start">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight">
                    4 Langkah Mudah<br>
                    Untuk Memulai <span class="font-serif italic text-[#2563EB]">Proyek Anda</span>
                </h2>

                <p class="text-[#4f5b7d] text-base md:text-[1.05rem] leading-relaxed font-medium">
                    Siap memulai proyek Anda? Hubungi kami langsung melalui tombol WhatsApp di bawah. Kami akan memandu Anda secara transparan dari tahap briefing hingga serah terima melalui 4 langkah praktis ini.
                </p>

                <ul class="flex flex-col gap-3.5 my-2">
                    @foreach($bullets as $bullet)
                        <li class="flex items-center gap-3 text-[0.95rem] text-[#4f5b7d] font-semibold">
                            <div class="w-5 h-5 rounded-full bg-[#C7F236] flex items-center justify-center shrink-0">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-[#0A1E5E] stroke-[3]"></i>
                            </div>
                            <span>{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>

                <a 
                    href="{{ $whatsappUrl }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-[0.95rem] font-bold bg-[#2563EB] text-white hover:bg-[#1d4ed8] transition-all w-fit shadow-lg shadow-[#2563EB]/25 mt-2"
                >
                    <span>Mulai Konsultasi Proyek</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4 stroke-[2.5]"></i>
                </a>
            </div>

            <!-- Right Column: Timeline -->
            <div class="relative mt-8 lg:mt-3 lg:max-h-[480px] max-h-[420px] overflow-y-auto overflow-x-hidden pr-3 sm:pr-6 pb-6">
                <!-- Vertical Line -->
                <div class="absolute left-[22px] top-6 bottom-12 w-px bg-slate-200"></div>
                
                <div class="flex flex-col gap-10 md:gap-12 relative">
                    @foreach($steps as $step)
                        <div class="flex gap-6 relative">
                            <!-- Icon Circle -->
                            <div class="w-11 h-11 rounded-xl bg-[#2563EB] shadow-lg shadow-[#2563EB]/20 flex items-center justify-center shrink-0 relative z-10 text-white">
                                <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex flex-col gap-2.5 pb-2">
                                <div class="bg-[#f0f4fc] text-[#2563EB] text-[0.65rem] font-black px-2.5 py-0.5 rounded-full w-fit tracking-wider">
                                    {{ $step['id'] }}
                                </div>
                                <h3 class="text-[1.15rem] md:text-xl font-bold text-[#1a1f3c] tracking-tight">
                                    {{ $step['title'] }}
                                </h3>
                                <p class="text-[#4f5b7d] text-[0.9rem] md:text-[0.95rem] leading-relaxed font-medium">
                                    {{ $step['description'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
