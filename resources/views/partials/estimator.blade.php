@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
@endphp

<section 
    id="estimator" 
    class="py-20 md:py-28 bg-[#f8f9fc]"
    x-data="{
        services: {{ json_encode($services) }},
        serviceFeatures: {{ json_encode($serviceFeatures) }},
        selectedServiceId: {{ $services->first()->id ?? 'null' }},
        selectedFeatureIds: [],
        formData: {
            name: '',
            phone: '',
            email: '',
            details: ''
        },
        toggleFeature(id) {
            if (this.selectedFeatureIds.includes(id)) {
                this.selectedFeatureIds = this.selectedFeatureIds.filter(fid => fid !== id);
            } else {
                this.selectedFeatureIds.push(id);
            }
        },
        get selectedService() {
            return this.services.find(s => s.id === this.selectedServiceId);
        },
        get totalPrice() {
            let total = 0;
            if (this.selectedService) {
                total += parseInt(this.selectedService.base_price || 0);
            }
            this.selectedFeatureIds.forEach(fid => {
                const feat = this.serviceFeatures.find(f => f.id === fid);
                if (feat) {
                    total += parseInt(feat.price || 0);
                }
            });
            return total;
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
        submitEstimate() {
            if (!this.formData.name || !this.formData.phone) {
                alert('Silakan lengkapi Nama dan Nomor WhatsApp Anda.');
                return;
            }
            const sName = this.selectedService ? this.selectedService.name : '-';
            const fNames = this.selectedFeatureIds.map(fid => {
                const f = this.serviceFeatures.find(feat => feat.id === fid);
                return f ? f.title : '';
            }).filter(Boolean).join(', ') || 'Tidak ada';

            const msg = `Halo Tim JuangDev, saya ingin konsultasi estimasi proyek baru.\n\n*Rincian Pilihan:*\n- Layanan: ${sName}\n- Fitur Add-on: ${fNames}\n*Estimasi Biaya:* Rp ${this.formatRupiah(this.totalPrice)}\n\n*Nama:* ${this.formData.name}\n*WhatsApp:* ${this.formData.phone}\n*Email:* ${this.formData.email || '-'}\n\n*Kebutuhan Proyek:*\n${this.formData.details || '-'}`;
            
            const waUrl = `https://wa.me/{{ $whatsappNumber }}?text=${encodeURIComponent(msg)}`;
            window.open(waUrl, '_blank');
        }
    }"
>
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.3fr] gap-12 lg:gap-16 items-start">
            
            <!-- Left Column: Estimator Summary & Free Consultation Badge -->
            <div class="flex flex-col lg:sticky lg:top-28">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                    Estimator Biaya<br>
                    <span class="font-serif italic text-[#2563EB]">Proyek Interaktif</span>
                </h2>
                
                <p class="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed mb-6 font-medium">
                    Pilih jenis layanan utama dan fitur tambahan yang Anda butuhkan. Dapatkan perkiraan biaya pembuatan proyek digital Anda secara langsung.
                </p>

                <!-- Total Box -->
                <div class="bg-white border-2 border-slate-100 rounded-[2rem] p-7 md:p-8 shadow-xl shadow-slate-200/50">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Estimasi Biaya</p>
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1f3c]">
                        Rp <span x-text="formatRupiah(totalPrice)"></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-3 font-medium leading-relaxed">
                        * Harga ini merupakan estimasi awal dan dapat disesuaikan kembali tergantung rincian spesifik kebutuhan Anda.
                    </p>
                </div>
                
                <!-- Free Consultation Card -->
                <div class="bg-[#0A1E5E] rounded-2xl p-6 md:p-7 mt-6 shadow-xl shadow-[#0A1E5E]/20 text-white">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5 font-bold">
                            <i data-lucide="check" class="w-5 h-5 stroke-[3]"></i>
                        </div>
                        <div>
                            <p class="text-[#C7F236] font-bold text-[0.9rem] mb-1 uppercase tracking-wide">Sesi Konsultasi Gratis</p>
                            <p class="text-white/85 text-[0.875rem] leading-relaxed font-medium">
                                Diskusi santai mengenai ide Anda. Kami membantu merancang strategi dasar tanpa biaya atau komitmen di awal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Step-by-Step Form -->
            <div class="bg-white rounded-[2rem] border-2 border-slate-100 shadow-xl shadow-slate-200/50 p-6 sm:p-8 md:p-10">
                <form @submit.prevent="submitEstimate()" class="space-y-8">
                    
                    <!-- Step 1: Select Main Service -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">1</span> 
                            Pilih Layanan Utama <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="service in services" :key="service.id">
                                <button 
                                    type="button"
                                    @click="selectedServiceId = service.id"
                                    :class="selectedServiceId === service.id 
                                        ? 'bg-[#f0f4fc] border-[#2563EB] ring-2 ring-[#2563EB]/20' 
                                        : 'bg-white border-slate-100 hover:border-slate-300 hover:bg-slate-50'"
                                    class="flex flex-col text-left p-4 rounded-xl border-2 transition-all duration-200"
                                >
                                    <span 
                                        :class="selectedServiceId === service.id ? 'text-[#2563EB]' : 'text-slate-800'"
                                        class="font-bold text-sm"
                                        x-text="service.name"
                                    ></span>
                                    <span class="text-xs text-slate-500 font-semibold mt-1" x-text="'+ Rp ' + formatRupiah(service.base_price)">
                                    </span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Step 2: Add-on Features -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">2</span> 
                            Fitur Tambahan / Add-on (Opsional)
                        </label>
                        <div class="space-y-2.5">
                            <template x-for="feature in serviceFeatures" :key="feature.id">
                                <button 
                                    type="button"
                                    @click="toggleFeature(feature.id)"
                                    :class="selectedFeatureIds.includes(feature.id) 
                                        ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' 
                                        : 'bg-white border-slate-200 hover:border-slate-300'"
                                    class="w-full flex items-center justify-between p-3.5 rounded-xl border-2 transition-all duration-200"
                                >
                                    <span 
                                        :class="selectedFeatureIds.includes(feature.id) ? 'text-emerald-800 font-bold' : 'text-slate-700 font-medium'"
                                        class="text-sm"
                                        x-text="feature.title"
                                    ></span>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-semibold text-slate-500" x-text="'+ Rp ' + formatRupiah(feature.price)"></span>
                                        <div 
                                            :class="selectedFeatureIds.includes(feature.id) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400'"
                                            class="w-6 h-6 rounded-md flex items-center justify-center text-xs font-bold transition-colors"
                                        >
                                            <span x-show="selectedFeatureIds.includes(feature.id)">✓</span>
                                            <span x-show="!selectedFeatureIds.includes(feature.id)">+</span>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Step 3: Contact Details -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">3</span> 
                            Informasi Kontak &amp; Kebutuhan
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <input 
                                type="text" 
                                x-model="formData.name"
                                required
                                placeholder="Nama Lengkap *"
                                class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                            >
                            <input 
                                type="tel" 
                                x-model="formData.phone"
                                required
                                placeholder="Nomor WhatsApp *"
                                class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <textarea 
                            x-model="formData.details"
                            rows="3"
                            placeholder="Jelaskan secara ringkas kebutuhan proyek Anda..."
                            class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] resize-none mb-5"
                        ></textarea>
                        
                        <button 
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-xl py-4 text-[0.95rem] font-bold transition-all duration-300 bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25"
                        >
                            <span>Kirim Estimasi via WhatsApp</span>
                            <i data-lucide="send" class="w-4 h-4 stroke-[2.5]"></i>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>
