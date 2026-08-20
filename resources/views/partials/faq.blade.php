@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin bertanya mengenai layanan pembuatan website.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";

    $faqItems = [
        [
            'question' => 'Berapa lama waktu pengerjaan website?',
            'answer' => 'Estimasi pengerjaan tergantung pada kompleksitas proyek. Landing page memerlukan waktu 2-3 hari, website company profile 3-7 hari, aplikasi web 2-4 minggu, dan sistem e-commerce/ERP 3-6 minggu. Kami akan memberikan lini masa yang jelas pada tahap awal.',
        ],
        [
            'question' => 'Berapa biaya pembuatan website di JuangDev?',
            'answer' => 'Biaya layanan kami sangat terjangkau mulai dari 99K untuk landing page dasar. Company profile berkisar dari 199K-499K, dan aplikasi web kustom mulai dari 999K+, tergantung pada fitur dan kompleksitas. Kami memberikan transparansi harga 100% tanpa biaya tersembunyi.',
        ],
        [
            'question' => 'Apakah bisa melakukan redesain website yang sudah ada?',
            'answer' => 'Tentu saja! Kami berpengalaman dalam melakukan redesain, perbaikan tampilan, dan modernisasi website. Kami akan menganalisis website Anda saat ini, meningkatkan kecepatan, pengalaman pengguna, serta kenyamanan di perangkat seluler dengan tetap mempertahankan identitas brand Anda.',
        ],
        [
            'question' => 'Apakah JuangDev menyediakan garansi dan dukungan purna jual?',
            'answer' => 'Ya! Seluruh layanan kami sudah mencakup dukungan purna jual (mulai dari 30 hari hingga perawatan berkala). Kami juga membantu pengaturan domain, hosting, pembaruan keamanan, dan penambahan fitur.',
        ],
        [
            'question' => 'Teknologi apa saja yang digunakan?',
            'answer' => 'Kami menggunakan teknologi modern dan andal: Laravel, Blade, Tailwind CSS, Alpine.js, MySQL untuk website render server yang super cepat, serta Next.js dan REST API untuk aplikasi web kustom yang kompleks.',
        ],
        [
            'question' => 'Bagaimana sistem pembayarannya?',
            'answer' => 'Kami menggunakan struktur pembayaran yang aman dan transparan: Down Payment (DP) sebesar 50% untuk memulai pengerjaan, dan pelunasan sisa 50% setelah proyek selesai diuji dan siap diserahterimakan.',
        ],
        [
            'question' => 'Apakah bisa meminta fitur kustom atau integrasi khusus?',
            'answer' => 'Tentu saja! Pengembangan fitur kustom adalah keahlian utama kami. Baik itu Payment Gateway (Midtrans/Xendit), notifikasi WhatsApp otomatis, dashboard analitik kustom, chatbot AI, atau logika bisnis unik — kami akan membangun sesuai kebutuhan Anda.',
        ],
    ];
@endphp

<section 
    id="faq" 
    class="py-20 md:py-28 lg:py-32 bg-white relative overflow-hidden"
    x-data="{ openIndex: 0 }"
>
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-12 lg:gap-20 items-start">
            
            <!-- Left Column: Title & CTA -->
            <div class="lg:sticky lg:top-32">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                    Ada yang Ingin<br>
                    <span class="text-[#2563EB] font-serif italic">Ditanyakan?</span>
                </h2>
                <p class="text-[#64748b] text-[0.95rem] md:text-[1.05rem] leading-relaxed mb-8 max-w-sm font-medium">
                    Pertanyaan yang paling sering kami terima dari calon klien. Tidak menemukan jawaban yang Anda cari? Hubungi kami secara langsung.
                </p>
                <a 
                    href="{{ $whatsappUrl }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-3.5 text-[0.9rem] font-bold bg-[#2563EB] text-white hover:bg-[#1d4ed8] transition-all w-fit shadow-lg shadow-[#2563EB]/25"
                >
                    <span>Hubungi Kami</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4 stroke-[2.5]"></i>
                </a>
            </div>

            <!-- Right Column: Accordion List -->
            <div class="relative">
                <div class="max-h-[550px] overflow-y-auto pr-2 sm:pr-4 pb-4 border-t border-slate-100 lg:border-t-0">
                    <div class="flex flex-col">
                        @foreach($faqItems as $i => $item)
                            @php $numStr = sprintf('%02d', $i + 1); @endphp
                            <div class="border-b border-slate-200 last:border-b-0 py-5">
                                <button 
                                    @click="openIndex = (openIndex === {{ $i }} ? null : {{ $i }})"
                                    class="w-full flex items-center justify-between text-left gap-4 group cursor-pointer focus:outline-none"
                                >
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="w-8 h-8 rounded-lg bg-[#f0f4fc] text-[#2563EB] text-[0.75rem] font-black flex items-center justify-center shrink-0">
                                            {{ $numStr }}
                                        </div>
                                        <span 
                                            :class="openIndex === {{ $i }} ? 'text-[#2563EB]' : 'text-[#1a1f3c] group-hover:text-[#2563EB]'"
                                            class="text-[0.95rem] md:text-base font-bold transition-colors"
                                        >
                                            {{ $item['question'] }}
                                        </span>
                                    </div>
                                    
                                    <div 
                                        :class="openIndex === {{ $i }} ? 'bg-[#2563EB] border-[#2563EB] text-white shadow-md shadow-[#2563EB]/30' : 'bg-white border-slate-200 text-slate-400 group-hover:border-[#2563EB] group-hover:text-[#2563EB]'"
                                        class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border transition-all duration-300 font-bold"
                                    >
                                        <span x-show="openIndex === {{ $i }}" class="text-sm font-bold">✕</span>
                                        <span x-show="openIndex !== {{ $i }}" class="text-lg leading-none">+</span>
                                    </div>
                                </button>

                                <div 
                                    x-show="openIndex === {{ $i }}"
                                    x-collapse
                                    class="overflow-hidden"
                                >
                                    <div class="pl-[3rem] sm:pl-[3.5rem] pt-4 pb-2 pr-1">
                                        <div class="bg-[#f8f9fc] rounded-[1.25rem] p-5 sm:p-6 text-[0.9rem] sm:text-[0.95rem] text-[#64748b] font-medium leading-relaxed shadow-xs">
                                            {{ $item['answer'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
