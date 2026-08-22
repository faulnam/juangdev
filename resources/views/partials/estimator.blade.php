@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '62859171681988';
@endphp

<section 
    id="estimator" 
    class="py-20 md:py-28 bg-[#f8f9fc]"
    @select-estimate-plan.window="selectPlanFromCategory($event.detail.category, $event.detail.planId)"
    x-data="{
        services: {{ json_encode($services) }},
        pricingPlans: {{ json_encode($pricingPlans ?? []) }},
        serviceFeatures: {{ json_encode($serviceFeatures) }},
        selectedServiceId: {{ $services->first()->id ?? 'null' }},
        selectedPlanId: null,
        selectedFeatureIds: [],
        formData: {
            name: '',
            phone: '',
            email: '',
            projectName: '',
            paymentScheme: 'dp_50',
            details: ''
        },
        selectedPaymentChannel: 'qris',
        createdOrder: {{ json_encode($order ?? null) }},
        estimatorStep: {{ isset($order) ? "'order_history'" : "'form'" }}, // 'form', 'payment_methods', 'payment_instruction', 'order_history'
        isSubmitting: false,
        isJustPaid: false,
        customPriceInput: null,

        init() {
            if (this.createdOrder) {
                this.formData.name = this.createdOrder.customer_name;
                this.formData.phone = this.createdOrder.customer_phone;
                this.formData.email = this.createdOrder.customer_email;
                this.formData.projectName = this.createdOrder.project_name;
                this.formData.paymentScheme = this.createdOrder.payment_scheme;

                this.$nextTick(() => {
                    const el = document.getElementById('estimator');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }

            this.$watch('selectedPlanId', (val) => {
                if (this.selectedService && this.selectedService.slug === 'custom-app') {
                    this.customPriceInput = this.currentPlanRange.min;
                }
            });

            this.$watch('selectedServiceId', (val) => {
                const plans = this.activePlans;
                if (plans.length > 0) {
                    this.selectedPlanId = plans[0].id;
                    if (this.selectedService && this.selectedService.slug === 'custom-app') {
                        this.customPriceInput = this.currentPlanRange.min;
                    }
                } else {
                    this.selectedPlanId = null;
                }
            });

            const plans = this.activePlans;
            if (plans.length > 0) {
                this.selectedPlanId = plans[0].id;
                if (this.selectedService && this.selectedService.slug === 'custom-app') {
                    this.customPriceInput = this.currentPlanRange.min;
                }
            }

            const urlParams = new URLSearchParams(window.location.search);
            const paramCategory = urlParams.get('category') || urlParams.get('service');
            const paramPlan = urlParams.get('plan');
            if (paramCategory) {
                this.selectPlanFromCategory(paramCategory, paramPlan ? parseInt(paramPlan) : null);
            }
        },

        selectPlanFromCategory(category, planId) {
            if (!category) return;
            const service = this.services.find(s => s.slug === category);
            if (service) {
                this.selectedServiceId = service.id;
                this.$nextTick(() => {
                    if (planId) {
                        const targetPlan = this.pricingPlans.find(p => p.id === planId);
                        if (targetPlan) {
                            this.selectedPlanId = targetPlan.id;
                        }
                    }
                });
            }
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
        get activePlans() {
            if (!this.selectedService) return [];
            return this.pricingPlans.filter(p => p.category === this.selectedService.slug);
        },
        get selectedPlan() {
            if (this.selectedPlanId) {
                const found = this.pricingPlans.find(p => p.id === this.selectedPlanId);
                if (found) return found;
            }
            const plans = this.activePlans;
            return plans.length > 0 ? plans[0] : null;
        },
        parsePriceString(str) {
            if (!str) return 0;
            if (typeof str === 'number') return str;
            let s = str.toString().toLowerCase().trim();
            if (s.includes('-')) {
                s = s.split('-')[0].trim();
            }
            if (s.includes('k')) {
                let clean = s.replace(/[^0-9]/g, '').trim();
                let num = parseInt(clean);
                return isNaN(num) ? 0 : num * 1000;
            }
            let num = parseInt(s.replace(/[^0-9]/g, ''));
            return isNaN(num) ? 0 : num;
        },
        parsePriceRange(str) {
            if (!str) return { min: 0, max: 0 };
            let s = str.toString().toLowerCase().trim();
            if (s.includes('-')) {
                let parts = s.split('-');
                let min = this.parsePriceString(parts[0]);
                let max = this.parsePriceString(parts[1]);
                return { min: min, max: max };
            }
            let val = this.parsePriceString(s);
            return { min: val, max: val };
        },
        get currentPlanRange() {
            if (!this.selectedPlan) return { min: 0, max: 0 };
            return this.parsePriceRange(this.selectedPlan.price);
        },
        get isCustomApp() {
            return this.selectedService && this.selectedService.slug === 'custom-app';
        },
        get planPriceNumber() {
            if (this.isCustomApp && this.selectedPlan) {
                const range = this.currentPlanRange;
                let val = parseInt(this.customPriceInput);
                if (isNaN(val) || val <= 0) return range.min;
                return val;
            }
            return this.selectedPlan ? this.parsePriceString(this.selectedPlan.price) : 0;
        },
        get totalPrice() {
            let total = 0;
            if (this.selectedPlan) {
                total += this.planPriceNumber;
            } else if (this.selectedService) {
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
        get dpPrice() {
            return Math.round(this.totalPrice * 0.5);
        },
        get payableAmount() {
            return this.formData.paymentScheme === 'full_100' ? this.totalPrice : this.dpPrice;
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        },
        goToPaymentMethods() {
            if (!this.formData.name || !this.formData.phone || !this.formData.email) {
                alert('Silakan lengkapi Nama, Email, dan Nomor WhatsApp Anda.');
                return;
            }
            this.estimatorStep = 'payment_methods';
        },
        processFinalPayment() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const sName = this.selectedService ? this.selectedService.name : '-';
            let pName = '-';
            if (this.selectedPlan) {
                if (this.isCustomApp) {
                    pName = this.selectedPlan.name + ' (Rp ' + this.formatRupiah(this.planPriceNumber) + ' - ' + this.selectedPlan.price + ')';
                } else {
                    pName = this.selectedPlan.name + ' (' + this.selectedPlan.price + ')';
                }
            }
            const selectedAddons = this.selectedFeatureIds.map(fid => {
                const f = this.serviceFeatures.find(feat => feat.id === fid);
                return f ? { id: f.id, title: f.title, price: f.price } : null;
            }).filter(Boolean);

            fetch('/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    customer_name: this.formData.name,
                    customer_email: this.formData.email,
                    customer_phone: this.formData.phone,
                    project_name: this.formData.projectName || this.formData.name + ' Project',
                    service_name: sName,
                    package_name: pName,
                    addons: selectedAddons,
                    total_amount: this.totalPrice,
                    payment_scheme: this.formData.paymentScheme,
                    notes: this.formData.details
                })
            })
            .then(res => res.json())
            .then(data => {
                this.isSubmitting = false;
                if (data.invoice_number) {
                    this.createdOrder = data;
                    this.isJustPaid = true;
                    this.estimatorStep = 'order_history';
                    // Update URL browser history without full page reload
                    if (window.history && window.history.pushState) {
                        window.history.pushState({ path: data.invoice_url }, '', data.invoice_url);
                    }
                }
            })
            .catch(e => {
                console.log(e);
                this.isSubmitting = false;
                alert('Terjadi kesalahan saat memproses pesanan. Silakan coba kembali.');
            });
        },
        printReceipt() {
            if (typeof printThermalReceipt === 'function') {
                printThermalReceipt();
            } else {
                window.print();
            }
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
                    Pilih jenis layanan, opsi paket, dan fitur tambahan yang Anda butuhkan. Dapatkan perkiraan biaya pembuatan proyek digital Anda secara langsung.
                </p>

                <!-- Total Box -->
                <div class="bg-white border-2 border-slate-100 rounded-[2rem] p-7 md:p-8 shadow-xl shadow-slate-200/50 space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Estimasi Biaya Proyek</p>
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1f3c]">
                        <span class="text-xl font-bold text-slate-400">Rp</span> 
                        <span x-text="formatRupiah(totalPrice)">0</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#2563EB]">
                        <span>Uang Muka (DP 50%):</span>
                        <span>Rp <span x-text="formatRupiah(dpPrice)">0</span></span>
                    </div>
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

            <!-- Right Column: Interactive Step-by-Step In-Place Form -->
            <div class="bg-white rounded-[2rem] border-2 border-slate-100 shadow-xl shadow-slate-200/50 p-6 sm:p-8 md:p-10">
                
                <!-- VIEW 1: Form Inputs -->
                <div x-show="estimatorStep === 'form'" class="space-y-8">
                    
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
                                    <span class="text-xs text-slate-500 font-semibold mt-1" x-text="'Mulai Rp ' + formatRupiah(service.base_price)">
                                    </span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Step 2: Select Package -->
                    <div x-show="activePlans.length > 0">
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">2</span> 
                            Pilih Paket Layanan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <template x-for="plan in activePlans" :key="plan.id">
                                <button 
                                    type="button"
                                    @click="selectedPlanId = plan.id"
                                    :class="selectedPlanId === plan.id 
                                        ? 'bg-blue-50 border-[#2563EB] ring-2 ring-[#2563EB]/20' 
                                        : 'bg-white border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                                    class="flex flex-col text-left p-3.5 rounded-xl border-2 transition-all duration-200"
                                >
                                    <div class="flex items-center justify-between w-full mb-1">
                                        <span 
                                            :class="selectedPlanId === plan.id ? 'text-[#2563EB]' : 'text-slate-900'"
                                            class="font-bold text-sm"
                                            x-text="plan.name"
                                        ></span>
                                        <span x-show="plan.badge" class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-amber-100 text-amber-800" x-text="plan.badge"></span>
                                    </div>
                                    <span 
                                        class="text-xs font-black text-slate-700" 
                                        x-text="plan.price.includes('-') ? ('Rp ' + plan.price) : ('Rp ' + formatRupiah(parsePriceString(plan.price)))"
                                    ></span>
                                    <p class="text-[11px] text-slate-400 font-medium mt-1 line-clamp-2" x-text="plan.description"></p>
                                </button>
                            </template>
                        </div>

                        <!-- Custom Web App: Custom Price Adjuster & Manual Input -->
                        <div x-show="isCustomApp && selectedPlan" class="mt-4 p-4.5 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3.5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#2563EB]"></i>
                                        <span class="text-xs font-bold text-slate-900">Atur Nominal Investasi Proyek Sesuai Kesepakatan</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Rentang paket <strong class="text-slate-800" x-text="selectedPlan ? selectedPlan.name : ''"></strong>: 
                                        <span class="font-bold text-[#2563EB]" x-text="'Rp ' + formatRupiah(currentPlanRange.min) + ' s/d Rp ' + formatRupiah(currentPlanRange.max)"></span>
                                    </p>
                                </div>
                                <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-1.5 text-right shadow-2xs self-start sm:self-auto">
                                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Biaya Terpilih</span>
                                    <span class="text-xs sm:text-sm font-extrabold text-[#2563EB]" x-text="'Rp ' + formatRupiah(planPriceNumber)"></span>
                                </div>
                            </div>

                            <!-- Quick Presets Buttons -->
                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                <button 
                                    type="button" 
                                    @click="customPriceInput = currentPlanRange.min"
                                    class="px-3 py-1 rounded-lg text-[11px] font-bold border transition-all"
                                    :class="planPriceNumber === currentPlanRange.min ? 'bg-[#2563EB] text-white border-[#2563EB] shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    Min (Rp <span x-text="formatRupiah(currentPlanRange.min)"></span>)
                                </button>
                                <button 
                                    type="button" 
                                    @click="customPriceInput = Math.round((currentPlanRange.min + currentPlanRange.max) / 2000) * 1000"
                                    class="px-3 py-1 rounded-lg text-[11px] font-bold border transition-all"
                                    :class="planPriceNumber === Math.round((currentPlanRange.min + currentPlanRange.max) / 2000) * 1000 ? 'bg-[#2563EB] text-white border-[#2563EB] shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    Tengah (Rp <span x-text="formatRupiah(Math.round((currentPlanRange.min + currentPlanRange.max) / 2000) * 1000)"></span>)
                                </button>
                                <button 
                                    type="button" 
                                    @click="customPriceInput = currentPlanRange.max"
                                    class="px-3 py-1 rounded-lg text-[11px] font-bold border transition-all"
                                    :class="planPriceNumber === currentPlanRange.max ? 'bg-[#2563EB] text-white border-[#2563EB] shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    Maksimal (Rp <span x-text="formatRupiah(currentPlanRange.max)"></span>)
                                </button>
                            </div>

                            <!-- Slider & Manual Number Input -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center pt-1">
                                <div class="sm:col-span-2">
                                    <input 
                                        type="range" 
                                        :min="currentPlanRange.min" 
                                        :max="currentPlanRange.max" 
                                        step="25000"
                                        x-model.number="customPriceInput"
                                        class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#2563EB]"
                                    >
                                </div>
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                        <input 
                                            type="number" 
                                            :min="currentPlanRange.min" 
                                            :max="currentPlanRange.max" 
                                            step="10000"
                                            x-model.number="customPriceInput"
                                            class="w-full pl-8 pr-3 py-2 text-xs font-bold text-slate-800 rounded-xl border border-slate-200 focus:outline-none focus:border-[#2563EB] bg-white text-right"
                                            placeholder="Ketik harga..."
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Add-on Features -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">3</span> 
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

                    <!-- Step 4: Contact & Payment Scheme -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">4</span> 
                            Informasi Pemesan &amp; Skema Pembayaran
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Lengkap *</label>
                                <input 
                                    type="text" 
                                    x-model="formData.name"
                                    required
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Nomor WhatsApp *</label>
                                <input 
                                    type="tel" 
                                    x-model="formData.phone"
                                    required
                                    placeholder="+62 812 3456 7890"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Alamat Email *</label>
                                <input 
                                    type="email" 
                                    x-model="formData.email"
                                    required
                                    placeholder="budi@example.com"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Proyek / Bisnis</label>
                                <input 
                                    type="text" 
                                    x-model="formData.projectName"
                                    placeholder="Contoh: Toko Kopi Sejahtera"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                                >
                            </div>
                        </div>

                        <!-- Skema Bayar Radio Cards -->
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-slate-600 mb-2">Pilih Skema Pembayaran</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label 
                                    :class="formData.paymentScheme === 'dp_50' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                                    class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="scheme" value="dp_50" x-model="formData.paymentScheme" class="mt-1 text-[#2563EB]">
                                    <div>
                                        <p class="text-xs font-black text-slate-900 uppercase">Uang Muka (DP 50%)</p>
                                        <p class="text-sm font-bold text-[#2563EB] mt-0.5" x-text="'Rp ' + formatRupiah(dpPrice)"></p>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1">Sisa 50% dilunasi saat proyek selesai 100%.</p>
                                    </div>
                                </label>

                                <label 
                                    :class="formData.paymentScheme === 'full_100' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                                    class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="scheme" value="full_100" x-model="formData.paymentScheme" class="mt-1 text-[#2563EB]">
                                    <div>
                                        <p class="text-xs font-black text-slate-900 uppercase">Pelunasan Langsung (100%)</p>
                                        <p class="text-sm font-bold text-slate-900 mt-0.5" x-text="'Rp ' + formatRupiah(totalPrice)"></p>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1">Pembayaran penuh di awal tanpa repot.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <textarea 
                            x-model="formData.details"
                            rows="3"
                            placeholder="Jelaskan secara ringkas kebutuhan atau spesifikasi khusus proyek Anda..."
                            class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] resize-none mb-6"
                        ></textarea>

                        <!-- Single Submit Button: "Pilih Metode Pembayaran" -->
                        <button 
                            type="button"
                            @click="goToPaymentMethods()"
                            class="w-full flex items-center justify-center gap-2 rounded-xl py-4 text-sm font-black transition-all duration-300 bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25"
                        >
                            <span>Pilih Metode Pembayaran</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- VIEW 2: Select Payment Method (In-Place) -->
                <div x-show="estimatorStep === 'payment_methods'" x-cloak class="space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-900">Pilih Metode Pembayaran Pakasir</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Tagihan: <span class="font-bold text-[#2563EB]" x-text="'Rp ' + formatRupiah(payableAmount)"></span>
                                (<span x-text="formData.paymentScheme === 'dp_50' ? 'DP 50%' : 'Lunas 100%'"></span>)
                            </p>
                        </div>
                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'"
                            class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1"
                        >
                            <span>← Kembali</span>
                        </button>
                    </div>

                    <!-- Pakasir Channels List -->
                    <div class="space-y-3">
                        <label 
                            :class="selectedPaymentChannel === 'qris' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                            class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_channel" value="qris" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">QRIS Instant (All E-Wallet &amp; M-Banking)</p>
                                    <p class="text-xs text-slate-500 font-medium">BCA, GoPay, OVO, Dana, ShopeePay, LinkAja</p>
                                </div>
                            </div>
                            <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full uppercase">Instant</span>
                        </label>

                        <label 
                            :class="selectedPaymentChannel === 'va_bca' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                            class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_channel" value="va_bca" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">Virtual Account BCA</p>
                                    <p class="text-xs text-slate-500 font-medium">Verifikasi Otomatis Pakasir</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">VA Bank</span>
                        </label>

                        <label 
                            :class="selectedPaymentChannel === 'va_mandiri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                            class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_channel" value="va_mandiri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">Virtual Account Mandiri</p>
                                    <p class="text-xs text-slate-500 font-medium">Verifikasi Otomatis Pakasir</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">VA Bank</span>
                        </label>

                        <label 
                            :class="selectedPaymentChannel === 'va_bri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                            class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_channel" value="va_bri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">Virtual Account BRI</p>
                                    <p class="text-xs text-slate-500 font-medium">Verifikasi Otomatis Pakasir</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">VA Bank</span>
                        </label>
                    </div>

                    <!-- Action Buttons: Bayar & Kembali -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'"
                            class="w-1/3 py-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all"
                        >
                            ← Kembali
                        </button>

                        <button 
                            type="button"
                            @click="processFinalPayment()"
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : ''"
                            class="w-2/3 flex items-center justify-center gap-2 rounded-xl py-4 text-xs sm:text-sm font-black transition-all duration-300 bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25"
                        >
                            <span x-show="!isSubmitting">Bayar Rp <span x-text="formatRupiah(payableAmount)"></span> Sekarang</span>
                            <span x-show="isSubmitting">Memproses QRIS/VA...</span>
                            <i x-show="!isSubmitting" data-lucide="credit-card" class="w-4 h-4"></i>
                            <i x-show="isSubmitting" data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </button>
                    </div>
                </div>

                <!-- VIEW 3: Payment Instructions & Cetak Resi Button (In-Place) -->
                <div x-show="estimatorStep === 'payment_instruction'" x-cloak class="space-y-6 text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i data-lucide="check-circle" class="w-8 h-8 stroke-[2.5]"></i>
                    </div>

                    <div>
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-3 py-1 rounded-full">
                            Nomor Invoice: <span x-text="createdOrder?.invoice_number"></span>
                        </span>
                        <h3 class="text-2xl font-black text-slate-900 mt-2">Instruksi Pembayaran Pakasir</h3>
                        <p class="text-slate-500 text-xs font-medium mt-1">
                            Silakan selesaikan pembayaran <span class="font-bold text-[#2563EB]" x-text="'Rp ' + formatRupiah(payableAmount)"></span> melalui instruksi di bawah ini.
                        </p>
                    </div>

                    <!-- QRIS / VA Code Display Box -->
                    <div class="bg-slate-50 border-2 border-slate-200 rounded-2xl p-6 text-left space-y-4">
                        <template x-if="selectedPaymentChannel === 'qris'">
                            <div class="text-center space-y-3">
                                <p class="text-xs font-bold text-slate-700 uppercase">Pindai Kode QRIS Di Bawah Ini</p>
                                <div class="bg-white p-4 inline-block rounded-xl border border-slate-200 shadow-xs">
                                    <div class="w-44 h-44 bg-slate-900 rounded-lg mx-auto flex items-center justify-center text-white text-xs font-mono font-bold p-4 text-center">
                                        [ QRIS PAKASIR JUANGDEV ]
                                    </div>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium">Buka aplikasi GoPay, ShopeePay, BCA, OVO, Dana atau M-Banking Anda dan scan QRIS di atas.</p>
                            </div>
                        </template>

                        <template x-if="selectedPaymentChannel !== 'qris'">
                            <div class="space-y-3">
                                <p class="text-xs font-bold text-slate-700 uppercase">Nomor Virtual Account</p>
                                <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 font-mono font-bold text-lg text-slate-900">
                                    <span>8801 8273 9912 0019</span>
                                    <span class="text-xs font-sans font-bold bg-blue-50 text-[#2563EB] px-2.5 py-1 rounded uppercase" x-text="selectedPaymentChannel.replace('va_', '')"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium">Transfer sesuai nominal persis: <span class="font-bold text-slate-900" x-text="'Rp ' + formatRupiah(payableAmount)"></span></p>
                            </div>
                        </template>
                    </div>

                    <!-- Cetak Resi Button & WhatsApp Notification Notice -->
                    <div class="space-y-3">
                        <button 
                            type="button" 
                            @click="printReceipt()"
                            class="w-full py-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md"
                        >
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            <span>Cetak Resi / Download PDF</span>
                        </button>

                        <p class="text-[11px] text-slate-400 font-medium">
                            Bukti tagihan &amp; link pelunasan sisa 50% telah dikirimkan secara resmi ke nomor WhatsApp <span class="font-bold text-slate-700" x-text="formData.phone"></span>.
                        </p>
                    </div>
                </div>

                <!-- VIEW 4: Order History & Instant Pelunasan (In-Place) -->
                <div x-show="estimatorStep === 'order_history'" x-cloak class="space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                        <div>
                            <template x-if="createdOrder?.payment_status === 'fully_paid'">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-3 py-1 rounded-full inline-block mb-1">
                                    STATUS: LUNAS 100%
                                </span>
                            </template>
                            <template x-if="createdOrder?.payment_status === 'dp_paid'">
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-black uppercase px-3 py-1 rounded-full inline-block mb-1">
                                    STATUS: DP 50% LUNAS
                                </span>
                            </template>
                            <template x-if="createdOrder?.payment_status === 'unpaid'">
                                <span class="bg-rose-100 text-rose-800 text-[10px] font-black uppercase px-3 py-1 rounded-full inline-block mb-1">
                                    STATUS: MENUNGGU PEMBAYARAN
                                </span>
                            </template>

                            <h3 class="text-xl font-black text-slate-900">
                                Invoice #<span x-text="createdOrder?.invoice_number"></span>
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Klien: <span class="font-bold text-slate-800" x-text="createdOrder?.customer_name"></span> (<span x-text="createdOrder?.customer_phone"></span>)
                            </p>
                        </div>

                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'; createdOrder = null;"
                            class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1"
                        >
                            <span>+ Hitung Estimasi Baru</span>
                        </button>
                    </div>

                    <template x-if="isJustPaid || {{ session('success') ? 'true' : 'false' }}">
                    <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4 flex items-start gap-3 text-left">
                        <div class="bg-emerald-100 text-emerald-600 rounded-full p-1 mt-0.5">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-emerald-800">
                                <span x-show="createdOrder?.payment_status === 'fully_paid'">Pembayaran Pelunasan 100% Berhasil!</span>
                                <span x-show="createdOrder?.payment_status !== 'fully_paid'">Pembayaran DP 50% Berhasil Diterima!</span>
                            </p>
                            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">
                                Bukti transaksi dan konfirmasi resmi telah dikirimkan ke nomor WhatsApp Anda.
                            </p>
                        </div>
                    </div>
                    </template>

                    <!-- Order Summary Table -->
                    <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Nama Proyek / Layanan:</span>
                            <span class="font-bold text-slate-900" x-text="createdOrder?.project_name || createdOrder?.service_name"></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Total Investasi Proyek:</span>
                            <span class="font-black text-slate-900" x-text="'Rp ' + formatRupiah(createdOrder?.total_amount || 0)"></span>
                        </div>

                        <div class="pt-2 border-t border-slate-200 flex justify-between text-xs">
                            <span class="text-slate-600 font-bold">DP 50% (Uang Muka):</span>
                            <span :class="createdOrder?.payment_status !== 'unpaid' ? 'text-emerald-600 font-black' : 'text-rose-600 font-bold'">
                                <span x-text="'Rp ' + formatRupiah(createdOrder?.dp_amount || 0)"></span>
                                <span x-show="createdOrder?.payment_status !== 'unpaid'"> (LUNAS ✓)</span>
                                <span x-show="createdOrder?.payment_status === 'unpaid'"> (BELUM DIBAYAR)</span>
                            </span>
                        </div>

                        <div class="flex justify-between text-xs">
                            <span class="text-slate-600 font-bold">Sisa Pelunasan (50%):</span>
                            <span :class="createdOrder?.payment_status === 'fully_paid' ? 'text-emerald-600 font-black' : 'text-slate-900 font-black'">
                                <span x-text="createdOrder?.payment_status === 'fully_paid' ? 'Rp 0 (LUNAS ✓)' : 'Rp ' + formatRupiah(createdOrder?.remaining_amount || 0)"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Payment Gateway Section for Unpaid or DP Paid Orders -->
                    <template x-if="createdOrder?.payment_status !== 'fully_paid' && !isJustPaid && !{{ session('success') ? 'true' : 'false' }}">
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-left">
                                <p class="text-xs font-bold text-blue-900">
                                    <span x-show="createdOrder?.payment_status === 'unpaid'">Bayar DP 50% sebesar Rp <span x-text="formatRupiah(createdOrder?.dp_amount || 0)"></span></span>
                                    <span x-show="createdOrder?.payment_status === 'dp_paid'">Bayar Pelunasan 50% sebesar Rp <span x-text="formatRupiah(createdOrder?.remaining_amount || 0)"></span></span>
                                </p>
                                <p class="text-[11px] text-blue-700 font-medium mt-0.5">Pilih channel pembayaran Pakasir di bawah ini untuk memproses pembayaran secara instan.</p>
                            </div>

                            <!-- Pakasir Channels List -->
                            <div class="space-y-2.5">
                                <label 
                                    :class="selectedPaymentChannel === 'qris' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                                    class="flex items-center justify-between p-3.5 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="pay_hist_channel" value="qris" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div>
                                            <p class="font-bold text-slate-900">QRIS Instant (All E-Wallet &amp; M-Banking)</p>
                                            <p class="text-[11px] text-slate-500 font-medium">GoPay, OVO, ShopeePay, BCA, Dana, LinkAja</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded uppercase">Instant</span>
                                </label>

                                <label 
                                    :class="selectedPaymentChannel === 'va_bca' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                                    class="flex items-center justify-between p-3.5 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="pay_hist_channel" value="va_bca" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <p class="font-bold text-slate-900">Virtual Account BCA</p>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">VA Bank</span>
                                </label>

                                <label 
                                    :class="selectedPaymentChannel === 'va_mandiri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 bg-white'"
                                    class="flex items-center justify-between p-3.5 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="pay_hist_channel" value="va_mandiri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <p class="font-bold text-slate-900">Virtual Account Mandiri</p>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded">VA Bank</span>
                                </label>
                            </div>

                            <!-- Action Form Submit for Direct Payment Simulation / Redirect to Pakasir -->
                            <form :action="'/invoice/' + createdOrder?.invoice_number + '/pay'" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="type" :value="createdOrder?.payment_status === 'dp_paid' ? 'remaining' : 'dp'">
                                <button 
                                    type="submit"
                                    class="w-full py-4 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-black text-xs flex items-center justify-center gap-2 shadow-lg shadow-[#2563EB]/20"
                                >
                                    <span>Bayar </span>
                                    <span x-text="createdOrder?.payment_status === 'dp_paid' ? 'Pelunasan 50% (Rp ' + formatRupiah(createdOrder?.remaining_amount || 0) + ')' : 'DP 50% (Rp ' + formatRupiah(createdOrder?.dp_amount || 0) + ')'"></span>
                                    <span> via Pakasir</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </template>

                    <template x-if="createdOrder?.payment_status === 'dp_paid' && (isJustPaid || {{ session('success') ? 'true' : 'false' }})">
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-center space-y-1.5 mt-2">
                            <p class="font-bold text-blue-900 text-xs">Informasi Pelunasan Sisa 50%</p>
                            <p class="text-[11px] text-blue-800 font-medium leading-relaxed">Tautan invoice resmi telah dikirim ke WhatsApp Anda. Anda dapat kembali mengakses tautan tersebut kapan saja untuk melakukan pembayaran <b>Pelunasan Sisa 50%</b> di kemudian hari.</p>
                        </div>
                    </template>

                    <!-- Fully Paid Notice -->
                    <template x-if="createdOrder?.payment_status === 'fully_paid'">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center space-y-2">
                            <p class="font-black text-emerald-800 text-sm">Terima Kasih! Tagihan Proyek Ini Telah LUNAS 100%</p>
                            <p class="text-xs text-emerald-700 font-medium">Seluruh proses pembayaran telah terverifikasi. Tim teknis JuangDev sedang/telah menyelesaikan proyek Anda.</p>
                        </div>
                    </template>

                    <!-- Print Receipt Button -->
                    <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                        <button 
                            type="button" 
                            @click="printReceipt()"
                            class="w-full sm:w-1/2 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center justify-center gap-2"
                        >
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            <span>Cetak Resi / Download PDF</span>
                        </button>

                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'; createdOrder = null;"
                            class="w-full sm:w-1/2 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all"
                        >
                            + Hitung Estimasi Baru
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Thermal Paper Receipt Print Area -->

<div id="receipt-print-area" class="hidden print:block font-mono text-black text-xs p-6 bg-white max-w-sm mx-auto border border-dashed border-slate-300">
    <div class="text-center space-y-1 mb-4">
        <h2 class="text-base font-black tracking-widest uppercase">JUANGDEV</h2>
        <p class="text-10">Digital Solutions &amp; Software House</p>
        <p class="text-9">WhatsApp: +62 859-1716-81988 | halo@juangdev.com</p>
    </div>

    <div class="border-dashed-line"></div>
    
    <p class="rec-title text-center font-bold uppercase tracking-wider text-xs">
        @if(isset($order) && $order->payment_status === 'fully_paid')
            OFFICIAL BUKTI PELUNASAN (LUNAS 100%)
        @elseif(isset($order) && $order->payment_status === 'dp_paid')
            OFFICIAL BUKTI PEMBAYARAN DP (50%)
        @else
            OFFICIAL TAGIHAN PESANAN PROYEK
        @endif
    </p>

    <div class="border-dashed-line"></div>

    <div class="space-y-1 text-11 mb-3">
        <div class="flex justify-between">
            <span>No. Invoice:</span>
            <span class="rec-inv font-bold">{{ isset($order) ? $order->invoice_number : '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tanggal:</span>
            <span class="rec-date">{{ isset($order) ? $order->created_at->format('d M Y H:i') . ' WIB' : date('d M Y H:i') . ' WIB' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Klien:</span>
            <span class="rec-name font-bold">{{ isset($order) ? $order->customer_name : '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span>No. WA:</span>
            <span class="rec-phone">{{ isset($order) ? $order->customer_phone : '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Proyek:</span>
            <span class="rec-proj">{{ isset($order) ? ($order->project_name ?? '-') : '-' }}</span>
        </div>
    </div>

    <div class="border-solid-line"></div>

    <div class="space-y-15 text-11 mb-3">
        <div class="flex justify-between font-bold">
            <span>Deskripsi Layanan</span>
            <span>Biaya</span>
        </div>
        <div class="flex justify-between">
            <span class="rec-service">{{ isset($order) ? $order->service_name : '-' }}</span>
            <span class="rec-total-cost">{{ isset($order) ? $order->formatted_total : 'Rp 0' }}</span>
        </div>
        @if(isset($order) && $order->package_name)
            <div class="flex justify-between text-10 pl-2" style="color:#64748b">
                <span>Paket: {{ $order->package_name }}</span>
            </div>
        @endif
    </div>

    <div class="border-double-line"></div>

    <div class="space-y-1 text-11 mb-4">
        <div class="flex justify-between font-bold text-xs">
            <span>Total Nilai Proyek</span>
            <span class="rec-total">{{ isset($order) ? $order->formatted_total : 'Rp 0' }}</span>
        </div>
        
        <div class="flex justify-between font-bold" style="color:#2563eb">
            <span>Tagihan DP (50%)</span>
            <span class="rec-dp">
                {{ isset($order) ? $order->formatted_dp . ($order->payment_status !== 'unpaid' ? ' (LUNAS ✓)' : '') : 'Rp 0' }}
            </span>
        </div>

        <div class="flex justify-between" style="color:#475569">
            <span>Sisa Pelunasan (50%)</span>
            <span class="rec-rem">
                {{ isset($order) ? ($order->payment_status === 'fully_paid' ? 'Rp 0 (LUNAS ✓)' : $order->formatted_remaining) : 'Rp 0' }}
            </span>
        </div>

        <div class="flex justify-between pt-1 border-t">
            <span>Status Pembayaran:</span>
            <span class="rec-status font-bold uppercase">
                @if(isset($order) && $order->payment_status === 'fully_paid')
                    <span style="color:#047857">LUNAS SEPENUHNYA (100%)</span>
                @elseif(isset($order) && $order->payment_status === 'dp_paid')
                    <span style="color:#b45309">DP 50% LUNAS</span>
                @else
                    <span style="color:#e11d48">MENUNGGU PEMBAYARAN</span>
                @endif
            </span>
        </div>
    </div>

    <div class="border-dashed-line"></div>
    
    <div class="text-center space-y-1 mt-4">
        <p class="font-bold tracking-widest text-xs">THANK YOU FOR YOUR BUSINESS!</p>
        <p class="text-9" style="color:#64748b">JUANGDEV - YOUR DIGITAL GROWTH PARTNER</p>
    </div>
</div>

<script>
function formatRupiah(num) {
    if (!num && num !== 0) return 'Rp 0';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function printThermalReceipt() {
    var receiptEl = document.getElementById('receipt-print-area');
    if (!receiptEl) {
        window.print();
        return;
    }

    // Default values (empty when no Blade $order)
    var inv = '', name = '', phone = '', proj = '-', service = '-', dateStr = '';
    var total = 'Rp 0', dp = 'Rp 0', rem = 'Rp 0';
    var status = 'MENUNGGU PEMBAYARAN';
    var title = 'OFFICIAL TAGIHAN PESANAN PROYEK';
    var payStatus = 'unpaid';
    var totalNum = 0, dpNum = 0, remNum = 0;

    // 1. Try Blade server-side values first (only available when $order exists via invoice URL)
    @if(isset($order))
    inv = @json($order->invoice_number);
    name = @json($order->customer_name);
    phone = @json($order->customer_phone);
    proj = @json($order->project_name ?? '-');
    service = @json($order->service_name ?? '-');
    dateStr = @json($order->created_at->format('d M Y H:i') . ' WIB');
    totalNum = {{ $order->total_amount }};
    dpNum = {{ $order->dp_amount }};
    remNum = {{ $order->remaining_amount }};
    payStatus = @json($order->payment_status);
    @endif

    // 2. Override with Alpine.js data (always has latest, especially for new AJAX orders)
    if (window.Alpine && document.getElementById('estimator')) {
        try {
            var alpineData = Alpine.$data(document.getElementById('estimator'));
            if (alpineData && alpineData.createdOrder) {
                var o = alpineData.createdOrder;
                if (o.invoice_number) inv = o.invoice_number;
                if (o.customer_name) name = o.customer_name;
                if (o.customer_phone) phone = o.customer_phone;
                if (o.project_name) proj = o.project_name;
                if (o.service_name) service = o.service_name;
                if (o.total_amount !== undefined) totalNum = Number(o.total_amount);
                if (o.dp_amount !== undefined) dpNum = Number(o.dp_amount);
                if (o.remaining_amount !== undefined) remNum = Number(o.remaining_amount);
                if (o.payment_status) payStatus = o.payment_status;
                if (o.created_at) {
                    var d = new Date(o.created_at);
                    dateStr = d.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}) + ' ' + d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) + ' WIB';
                }
            }
            // Also try formData for name/phone if createdOrder is missing them
            if (alpineData && alpineData.formData && !name) {
                if (alpineData.formData.name) name = alpineData.formData.name;
                if (alpineData.formData.phone) phone = alpineData.formData.phone;
                if (alpineData.formData.projectName) proj = alpineData.formData.projectName;
            }
            // Try to get service name from selected service
            if (alpineData && alpineData.selectedService && (!service || service === '-')) {
                service = alpineData.selectedService.name || service;
            }
            // Try totalPrice from Alpine for total
            if (alpineData && alpineData.totalPrice && totalNum === 0) {
                totalNum = Number(alpineData.totalPrice);
                dpNum = Math.round(totalNum * 0.5);
                remNum = totalNum - dpNum;
            }
        } catch(e) {
            console.error('Alpine data read error:', e);
        }
    }

    // 3. Compute formatted values from numbers
    total = formatRupiah(totalNum);
    if (payStatus === 'fully_paid') {
        dp = formatRupiah(dpNum) + ' (LUNAS ✓)';
        rem = 'Rp 0 (LUNAS ✓)';
        status = 'LUNAS SEPENUHNYA (100%)';
        title = 'OFFICIAL BUKTI PELUNASAN (LUNAS 100%)';
    } else if (payStatus === 'dp_paid') {
        dp = formatRupiah(dpNum) + ' (LUNAS ✓)';
        rem = formatRupiah(remNum);
        status = 'DP 50% LUNAS';
        title = 'OFFICIAL BUKTI PEMBAYARAN DP (50%)';
    } else {
        dp = formatRupiah(dpNum);
        rem = formatRupiah(remNum);
        status = 'MENUNGGU PEMBAYARAN';
        title = 'OFFICIAL TAGIHAN PESANAN PROYEK';
    }

    if (!dateStr) {
        var now = new Date();
        dateStr = now.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}) + ' ' + now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) + ' WIB';
    }

    // 4. Clone receipt and inject all values
    var clone = receiptEl.cloneNode(true);
    clone.classList.remove('hidden', 'print:block');
    clone.style.display = 'block';

    var setEl = function(sel, val) { var el = clone.querySelector(sel); if (el) el.textContent = val; };
    setEl('.rec-title', title);
    setEl('.rec-inv', inv || '-');
    setEl('.rec-date', dateStr);
    setEl('.rec-name', name || '-');
    setEl('.rec-phone', phone || '-');
    setEl('.rec-proj', proj || '-');
    setEl('.rec-service', service || '-');
    setEl('.rec-total-cost', total);
    setEl('.rec-total', total);
    setEl('.rec-dp', dp);
    setEl('.rec-rem', rem);
    // For status, set innerHTML since it may have color spans
    var recStatusEl = clone.querySelector('.rec-status');
    if (recStatusEl) {
        var statusColor = '#e11d48'; // rose for unpaid
        if (payStatus === 'fully_paid') statusColor = '#047857';
        else if (payStatus === 'dp_paid') statusColor = '#b45309';
        recStatusEl.innerHTML = '<span style="color:' + statusColor + '">' + status + '</span>';
    }

    // 5. Open print popup - FULL CANVAS
    var printWin = window.open('', '_blank', 'width=800,height=900');
    if (!printWin) {
        window.print();
        return;
    }
    var css = [
        '@page { size: A4; margin: 12mm 10mm; }',
        '* { box-sizing: border-box; }',
        'body {',
        '  font-family: "Courier New", Courier, monospace;',
        '  background: #fff; color: #000;',
        '  margin: 0; padding: 0;',
        '  display: flex; justify-content: center; align-items: flex-start;',
        '  min-height: 100vh;',
        '}',
        '.receipt-box {',
        '  width: 100%; max-width: 100%;',
        '  padding: 30px 40px;',
        '  box-sizing: border-box;',
        '}',
        '.text-center { text-align: center; }',
        '.flex { display: flex; justify-content: space-between; align-items: center; }',
        '.font-bold { font-weight: bold; }',
        '.font-black { font-weight: 900; }',
        '.uppercase { text-transform: uppercase; }',
        '.text-xs { font-size: 13px; }',
        '.text-base { font-size: 19px; }',
        '.text-10 { font-size: 12px; }',
        '.text-11 { font-size: 14px; }',
        '.text-9 { font-size: 11px; }',
        '.mb-3 { margin-bottom: 16px; }',
        '.mb-4 { margin-bottom: 20px; }',
        '.mt-4 { margin-top: 20px; }',
        '.pl-2 { padding-left: 10px; }',
        '.border-dashed-line { border-bottom: 2px dashed #000; margin: 12px 0; }',
        '.border-solid-line { border-bottom: 2px solid #000; margin: 12px 0; }',
        '.border-double-line { border-bottom: 4px double #000; margin: 12px 0; }',
        '.space-y-1 > * + * { margin-top: 6px; }',
        '.space-y-15 > * + * { margin-top: 8px; }',
        '.border-t { border-top: 1px dotted #999; }',
        '.pt-1 { padding-top: 6px; }',
        '.tracking-widest { letter-spacing: 0.15em; }',
        '.tracking-wider { letter-spacing: 0.08em; }',
        'h2 { font-size: 24px; margin: 0; }',
        'p { margin: 2px 0; }',
    ].join('\n');
    printWin.document.write('<!DOCTYPE html><html><head><title>Resi Transaksi Resmi - JuangDev</title><meta charset="utf-8"><style>' + css + '</style></head><body><div class="receipt-box">' + clone.innerHTML + '</div><scr' + 'ipt>setTimeout(function(){window.print();},500);</scr' + 'ipt></body></html>');
    printWin.document.close();
}
</script>

