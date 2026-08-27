@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '62859171681988';
@endphp

<section 
    id="estimator" 
    class="py-20 md:py-28 bg-[#f8f9fc]"
    @select-estimate-plan.window="selectPlanFromCategory($event.detail.category, $event.detail.planId)"
    x-data="{
        currentUser: {{ auth()->check() ? json_encode([
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone ?? '',
        ]) : 'null' }},
        services: {{ json_encode($services) }},
        pricingPlans: {{ json_encode($pricingPlans ?? []) }},
        serviceFeatures: {{ json_encode($serviceFeatures) }},
        portfolios: {{ json_encode($portfolios ?? []) }},
        selectedServiceId: {{ $services->first()->id ?? 'null' }},
        selectedPlanId: null,
        selectedBoilerplateId: null,
        boilerplateDropdownOpen: false,
        selectedFeatureIds: [],
        formData: {
            name: {{ json_encode(auth()->check() ? auth()->user()->name : '') }},
            phone: {{ json_encode(auth()->check() ? (auth()->user()->phone ?? '') : '') }},
            email: {{ json_encode(auth()->check() ? auth()->user()->email : '') }},
            projectName: '',
            paymentScheme: 'dp_50',
            details: ''
        },
        selectedPaymentChannel: 'qris',
        createdOrder: {{ json_encode($order ?? null) }},
        pakasirData: null,
        isCheckingPayment: false,
        paymentPollingTimer: null,
        estimatorStep: {{ isset($order) ? "'order_history'" : "'form'" }}, // 'form', 'payment_methods', 'payment_instruction', 'order_history'
        isSubmitting: false,
        isJustPaid: false,
        customPriceInput: null,
        attachmentFile: null,
        attachmentName: '',
        attachmentSize: '',
        attachmentType: '',
        attachmentPreview: null,
        isDragging: false,
        attachmentError: null,

        normalizeCategory(cat) {
            if (!cat) return '';
            let s = cat.toString().toLowerCase().trim();
            if (s === 'aplikasi-web' || s === 'aplikasi web' || s === 'custom web app' || s === 'custom-app') return 'custom-app';
            if (s === 'toko-online' || s === 'toko online' || s === 'e-commerce' || s === 'ecommerce') return 'ecommerce';
            if (s === 'landing-page' || s === 'landing page') return 'landing-page';
            if (s === 'company-profile' || s === 'company profile') return 'company-profile';
            if (s === 'sistem-informasi' || s === 'sistem informasi') return 'sistem-informasi';
            return s.replace(/\s+/g, '-');
        },

        init() {
            if (this.currentUser) {
                if (!this.formData.name) this.formData.name = this.currentUser.name;
                if (!this.formData.email) this.formData.email = this.currentUser.email;
                if (!this.formData.phone && this.currentUser.phone) this.formData.phone = this.currentUser.phone;
            }

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
                this.$nextTick(() => {
                    const bps = this.availableBoilerplates;
                    if (bps.length > 0) {
                        if (!this.selectedBoilerplateId || !bps.some(b => b.id === this.selectedBoilerplateId)) {
                            this.selectBoilerplate(bps[0]);
                        }
                    } else {
                        this.selectedBoilerplateId = null;
                    }
                });
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
                this.$nextTick(() => {
                    const bps = this.availableBoilerplates;
                    if (bps.length > 0) {
                        if (!this.selectedBoilerplateId || !bps.some(b => b.id === this.selectedBoilerplateId)) {
                            this.selectBoilerplate(bps[0]);
                        }
                    } else {
                        this.selectedBoilerplateId = null;
                    }
                });
            });

            const plans = this.activePlans;
            if (plans.length > 0) {
                this.selectedPlanId = plans[0].id;
                if (this.selectedService && this.selectedService.slug === 'custom-app') {
                    this.customPriceInput = this.currentPlanRange.min;
                }
            }

            this.$nextTick(() => {
                const bps = this.availableBoilerplates;
                if (bps.length > 0 && !this.selectedBoilerplateId) {
                    this.selectBoilerplate(bps[0]);
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const paramCategory = urlParams.get('category') || urlParams.get('service');
            const paramPlan = urlParams.get('plan');
            const paramTier = urlParams.get('tier');
            const paramBoilerplate = urlParams.get('boilerplate') || urlParams.get('portfolio');

            if (paramCategory) {
                this.selectPlanFromCategory(paramCategory, paramPlan ? parseInt(paramPlan) : null, paramTier);
            }

            if (paramBoilerplate) {
                this.$nextTick(() => {
                    const targetBp = this.portfolios.find(p => (p.id == paramBoilerplate || p.slug === paramBoilerplate) && p.is_boilerplate);
                    if (targetBp) {
                        this.selectBoilerplate(targetBp);
                    }
                });
            }
        },

        selectPlanFromCategory(category, planId, tierName) {
            if (!category) return;
            const normalized = this.normalizeCategory(category);
            const service = this.services.find(s => this.normalizeCategory(s.slug) === normalized);
            if (service) {
                this.selectedServiceId = service.id;
                this.$nextTick(() => {
                    if (planId) {
                        const targetPlan = this.pricingPlans.find(p => p.id === planId);
                        if (targetPlan) {
                            this.selectedPlanId = targetPlan.id;
                        }
                    } else if (tierName) {
                        const matchingPlan = this.activePlans.find(p => p.name.toLowerCase() === tierName.toLowerCase());
                        if (matchingPlan) {
                            this.selectedPlanId = matchingPlan.id;
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
        get availableBoilerplates() {
            if (!this.selectedService) return [];
            const currentServiceSlug = this.normalizeCategory(this.selectedService.slug);
            const currentPlanName = this.selectedPlan ? (this.selectedPlan.name || '').toLowerCase() : '';
            
            return this.portfolios.filter(p => {
                if (!p.is_boilerplate) return false;
                const pCat = this.normalizeCategory(p.category);
                if (pCat !== currentServiceSlug) return false;
                
                if (currentPlanName && p.package_tier) {
                    const pTier = (p.package_tier || '').toLowerCase();
                    if (currentPlanName.includes('basic') && pTier !== 'basic') return false;
                    if (currentPlanName.includes('rekomendasi') && pTier !== 'rekomendasi') return false;
                    if (currentPlanName.includes('premium') && pTier !== 'premium') return false;
                }
                return true;
            });
        },
        get allCategoryBoilerplates() {
            if (!this.selectedService) return [];
            const currentServiceSlug = this.normalizeCategory(this.selectedService.slug);
            return this.portfolios.filter(p => p.is_boilerplate && this.normalizeCategory(p.category) === currentServiceSlug);
        },
        get selectedBoilerplate() {
            if (!this.selectedBoilerplateId) return null;
            return this.portfolios.find(p => p.id === this.selectedBoilerplateId && p.is_boilerplate) || null;
        },
        selectBoilerplate(bp) {
            if (!bp || !bp.is_boilerplate) {
                this.selectedBoilerplateId = null;
                this.boilerplateDropdownOpen = false;
                return;
            }
            this.selectedBoilerplateId = bp.id;
            this.boilerplateDropdownOpen = false;
            
            if (!this.formData.projectName || this.portfolios.some(p => p.title === this.formData.projectName)) {
                this.formData.projectName = bp.title;
            }
            
            const bpCat = this.normalizeCategory(bp.category);
            const srv = this.services.find(s => this.normalizeCategory(s.slug) === bpCat);
            if (srv && srv.id !== this.selectedServiceId) {
                this.selectedServiceId = srv.id;
            }
            
            if (bp.package_tier) {
                const matchingPlan = this.activePlans.find(pl => pl.name.toLowerCase() === bp.package_tier.toLowerCase());
                if (matchingPlan && matchingPlan.id !== this.selectedPlanId) {
                    this.selectedPlanId = matchingPlan.id;
                }
            }

            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
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
        get originalPlanPriceNumber() {
            if (this.selectedPlan && this.selectedPlan.original_price) {
                return this.parsePriceString(this.selectedPlan.original_price);
            }
            return this.planPriceNumber;
        },
        get originalTotalPrice() {
            let total = 0;
            if (this.selectedPlan) {
                total += this.originalPlanPriceNumber;
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
        get discountSavings() {
            if (this.originalTotalPrice > this.totalPrice) {
                return this.originalTotalPrice - this.totalPrice;
            }
            return 0;
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
            if (!this.currentUser) {
                window.dispatchEvent(new CustomEvent('open-auth-modal', {
                    detail: {
                        mode: 'login',
                        notice: 'Silakan masuk atau daftar akun terlebih dahulu untuk melanjutkan pemesanan dan melacak pengerjaan proyek Anda.',
                        onSuccess: (user) => {
                            this.currentUser = user;
                            this.formData.name = user.name;
                            this.formData.email = user.email;
                            if (user.phone) this.formData.phone = user.phone;
                            this.estimatorStep = 'payment_methods';
                        }
                    }
                }));
                return;
            }

            if (!this.formData.name || !this.formData.phone || !this.formData.email) {
                alert('Silakan lengkapi Nama, Email, dan Nomor WhatsApp Anda.');
                return;
            }
            this.estimatorStep = 'payment_methods';
        },
        handleFileSelect(event) {
            const file = event.target.files && event.target.files[0];
            this.processSelectedFile(file);
        },
        handleFileDrop(event) {
            this.isDragging = false;
            if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                this.processSelectedFile(event.dataTransfer.files[0]);
            }
        },
        processSelectedFile(file) {
            this.attachmentError = null;
            if (!file) return;

            // Maximum size: 50MB
            const maxSize = 50 * 1024 * 1024;
            if (file.size > maxSize) {
                this.attachmentError = 'Ukuran berkas melebihi batas maksimum 50MB.';
                alert(this.attachmentError);
                return;
            }

            // Allowed extensions
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', '7z', 'png', 'jpg', 'jpeg', 'webp'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(ext)) {
                this.attachmentError = 'Format berkas tidak didukung. Harap unggah format PDF, Word, Excel, ZIP/RAR, atau Gambar.';
                alert(this.attachmentError);
                return;
            }

            this.attachmentFile = file;
            this.attachmentName = file.name;
            this.attachmentSize = this.formatBytes(file.size);
            this.attachmentType = ext;

            // Image preview
            if (['png', 'jpg', 'jpeg', 'webp'].includes(ext)) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.attachmentPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.attachmentPreview = null;
            }

            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },
        removeAttachment() {
            this.attachmentFile = null;
            this.attachmentName = '';
            this.attachmentSize = '';
            this.attachmentType = '';
            this.attachmentPreview = null;
            this.attachmentError = null;
            const input = document.getElementById('estimator-file-input');
            if (input) input.value = '';
            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },
        formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

            let projName = this.formData.projectName;
            if (!projName) {
                if (this.selectedBoilerplate) {
                    projName = this.selectedBoilerplate.title;
                } else {
                    projName = (this.formData.name || 'Pelanggan') + ' Project';
                }
            }

            const fd = new FormData();
            fd.append('customer_name', this.formData.name);
            fd.append('customer_email', this.formData.email);
            fd.append('customer_phone', this.formData.phone);
            fd.append('project_name', projName);
            fd.append('service_name', sName);
            fd.append('package_name', pName);
            fd.append('addons', JSON.stringify(selectedAddons));
            fd.append('original_amount', this.originalTotalPrice);
            fd.append('discount_amount', this.discountSavings);
            fd.append('total_amount', this.totalPrice);
            fd.append('payment_scheme', this.formData.paymentScheme);
            fd.append('payment_channel', this.selectedPaymentChannel);
            fd.append('notes', this.formData.details || '');
            if (this.attachmentFile) {
                fd.append('attachment', this.attachmentFile);
            }

            fetch('/orders', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                this.isSubmitting = false;
                if (data.invoice_number) {
                    this.createdOrder = data;
                    this.pakasirData = data.pakasir;
                    this.isJustPaid = false;
                    this.estimatorStep = 'payment_instruction';
                    
                    // Start automatic polling to verify payment
                    this.startPaymentPolling(data.invoice_number);

                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                        const el = document.getElementById('estimator');
                        if (el) el.scrollIntoView({ behavior: 'smooth' });
                    });
                }
            })
            .catch(e => {
                console.log(e);
                this.isSubmitting = false;
                alert('Terjadi kesalahan saat memproses pesanan. Silakan coba kembali.');
            });
        },
        checkOrderPaymentStatus(manual = false) {
            if (!this.createdOrder) return;
            if (manual) this.isCheckingPayment = true;

            fetch('/orders/' + this.createdOrder.invoice_number + '/check-status')
                .then(res => res.json())
                .then(data => {
                    if (manual) this.isCheckingPayment = false;
                    if (data.paid) {
                        if (this.paymentPollingTimer) clearInterval(this.paymentPollingTimer);
                        this.createdOrder.payment_status = data.payment_status;
                        this.createdOrder.project_status = data.project_status;
                        this.createdOrder.remaining_amount = data.remaining_amount;
                        this.isJustPaid = true;
                        this.estimatorStep = 'order_history';
                        if (manual) alert('Pembayaran berhasil dikonfirmasi oleh sistem!');
                    } else if (manual) {
                        alert('Pembayaran belum terdeteksi. Silakan selesaikan pembayaran melalui QRIS / Pakasir.');
                    }
                })
                .catch(e => {
                    if (manual) {
                        this.isCheckingPayment = false;
                        alert('Gagal memeriksa status pembayaran.');
                    }
                });
        },
        startPaymentPolling(invoiceNumber) {
            if (this.paymentPollingTimer) clearInterval(this.paymentPollingTimer);
            this.paymentPollingTimer = setInterval(() => {
                if (this.estimatorStep === 'payment_instruction') {
                    this.checkOrderPaymentStatus(false);
                } else {
                    clearInterval(this.paymentPollingTimer);
                }
            }, 4000);
        },
        cancelCurrentOrder() {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini dan kembali ke estimator?')) {
                if (this.paymentPollingTimer) {
                    clearInterval(this.paymentPollingTimer);
                    this.paymentPollingTimer = null;
                }
                this.createdOrder = null;
                this.pakasirData = null;
                this.estimatorStep = 'form';
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                    const el = document.getElementById('estimator');
                    if (el) el.scrollIntoView({ behavior: 'smooth' });
                });
            }
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
                    
                    <template x-if="discountSavings > 0">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                            <span>Harga Normal:</span>
                            <span class="line-through">Rp <span x-text="formatRupiah(originalTotalPrice)"></span></span>
                        </div>
                    </template>
                    <template x-if="discountSavings > 0">
                        <div class="flex items-center justify-between text-xs font-bold text-rose-600 pb-1 border-b border-slate-100">
                            <span>Hemat Diskon:</span>
                            <span>- Rp <span x-text="formatRupiah(discountSavings)"></span></span>
                        </div>
                    </template>

                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1f3c]">
                        <span class="text-xl font-bold text-slate-400">Rp</span> 
                        <span x-text="formatRupiah(totalPrice)">0</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#2563EB]">
                        <span>Uang Muka (DP 50%):</span>
                        <span>Rp <span x-text="formatRupiah(dpPrice)">0</span></span>
                    </div>

                    <!-- Selected Boilerplate Badge & Thumbnail Preview in Total Box -->
                    <template x-if="selectedBoilerplate">
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 bg-blue-50/50 p-2.5 rounded-xl border border-blue-100">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <img :src="selectedBoilerplate.image_url || '/placeholder.png'" class="w-10 h-10 rounded-lg object-cover border border-blue-200 shrink-0 bg-white shadow-2xs" onerror="this.src='/placeholder.png'">
                                <div class="min-w-0 text-left">
                                    <span class="block text-[9px] font-black uppercase tracking-wider text-[#2563EB]">Template Terpilih</span>
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="selectedBoilerplate.title"></p>
                                    <span class="text-[10px] text-slate-500 font-semibold" x-text="'Paket ' + (selectedBoilerplate.package_tier || 'Standar')"></span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-[#2563EB] bg-white border border-blue-200 px-2 py-1 rounded-lg shrink-0" x-text="'Paket ' + (selectedBoilerplate.package_tier || 'Standar')"></span>
                        </div>
                    </template>
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
                                        <div class="flex items-center gap-1">
                                            <span x-show="plan.discount_percent" class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-rose-100 text-rose-700" x-text="'-' + plan.discount_percent + '%'"></span>
                                            <span x-show="plan.badge" class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-amber-100 text-amber-800" x-text="plan.badge"></span>
                                        </div>
                                    </div>

                                    <template x-if="plan.original_price">
                                        <span class="text-[10px] text-slate-400 font-bold line-through">
                                            Rp <span x-text="plan.original_price"></span>
                                        </span>
                                    </template>

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

                    <!-- Step 3: Select Boilerplate / Template (Dropdown with Images & Titles) -->
                    <div class="relative" x-data="{ searchBp: '' }">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <label class="flex items-center gap-2 text-xs sm:text-sm font-black text-[#1e2547] uppercase tracking-wider">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-black shadow-xs shrink-0">3</span> 
                                <span>Pilih Template Boilerplate Desain</span>
                            </label>
                            <template x-if="availableBoilerplates.length > 0">
                                <span class="text-[10px] sm:text-[11px] font-bold text-[#2563EB] bg-blue-50 border border-blue-200/80 px-2.5 py-0.5 rounded-full" x-text="availableBoilerplates.length + ' Template Sesuai Paket'"></span>
                            </template>
                        </div>

                        <p class="text-xs text-slate-500 font-medium mb-3">
                            Pilih model template boilerplate yang ingin Anda gunakan untuk proyek website ini.
                        </p>

                        <!-- Trigger Dropdown Button -->
                        <div class="relative">
                            <!-- If Boilerplate IS selected -->
                            <template x-if="selectedBoilerplate">
                                <div 
                                    @click="boilerplateDropdownOpen = !boilerplateDropdownOpen"
                                    class="w-full flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-[#2563EB] bg-blue-50/50 hover:bg-blue-50/80 ring-2 ring-[#2563EB]/15 cursor-pointer transition-all shadow-xs gap-3"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img 
                                            :src="selectedBoilerplate.image_url || '/placeholder.png'" 
                                            :alt="selectedBoilerplate.title" 
                                            class="w-12 h-12 sm:w-14 sm:h-14 object-cover rounded-xl border border-blue-200 shadow-2xs shrink-0 bg-white"
                                            onerror="this.src='/placeholder.png'"
                                        >
                                        <div class="min-w-0 text-left">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="text-xs sm:text-sm font-black text-slate-900 truncate" x-text="selectedBoilerplate.title"></span>
                                                <span 
                                                    x-show="selectedBoilerplate.package_tier" 
                                                    class="text-[9px] sm:text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-[#2563EB] text-white" 
                                                    x-text="'Paket ' + selectedBoilerplate.package_tier"
                                                ></span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <template x-if="selectedBoilerplate.live_url">
                                                    <a 
                                                        :href="selectedBoilerplate.live_url" 
                                                        target="_blank" 
                                                        @click.stop 
                                                        class="text-[11px] font-bold text-[#2563EB] hover:underline inline-flex items-center gap-1"
                                                    >
                                                        <span>Lihat Demo Live</span>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </template>
                                                <span class="text-[10px] text-slate-400 font-medium hidden sm:inline">• Klik untuk ganti pilihan</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-xs font-bold text-[#2563EB] bg-white border border-blue-200 px-2.5 py-1 rounded-xl hidden sm:inline-block shadow-2xs">Pilih Template Lain</span>
                                        <div class="w-8 h-8 rounded-xl bg-white border border-blue-200 flex items-center justify-center text-[#2563EB] shadow-2xs">
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="boilerplateDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- If NO Boilerplate is selected (Fallback) -->
                            <template x-if="!selectedBoilerplate">
                                <button 
                                    type="button" 
                                    @click="boilerplateDropdownOpen = !boilerplateDropdownOpen"
                                    class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border-2 border-slate-200 bg-white hover:border-blue-400 hover:bg-slate-50 transition-all text-left shadow-2xs cursor-pointer gap-3"
                                >
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-slate-700">
                                            Pilih Model Template Boilerplate...
                                        </p>
                                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                            Klik untuk membuka katalog template yang tersedia
                                        </p>
                                    </div>

                                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="boilerplateDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>
                            </template>

                            <!-- Dropdown Menu Options Panel -->
                            <div 
                                x-show="boilerplateDropdownOpen" 
                                @click.outside="boilerplateDropdownOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute left-0 right-0 top-full mt-2 z-50 bg-white rounded-2xl border-2 border-slate-200 shadow-2xl p-2.5 sm:p-3.5 space-y-2.5 max-h-[460px] overflow-y-auto w-full"
                                style="display: none;"
                            >
                                <!-- Search bar inside dropdown -->
                                <div class="sticky top-0 bg-white pt-1 pb-2 z-10 border-b border-slate-100">
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            x-model="searchBp" 
                                            placeholder="Cari nama template..."
                                            class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:outline-none focus:border-[#2563EB] focus:bg-white transition-colors"
                                        >
                                        <button 
                                            type="button" 
                                            x-show="searchBp" 
                                            @click="searchBp = ''" 
                                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold p-1"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </div>

                                <!-- Header for Matching Tier Boilerplates -->
                                <template x-if="availableBoilerplates.length > 0">
                                    <div class="px-2 pt-1 text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 flex items-center justify-between">
                                        <span>Template Paket <span class="text-[#2563EB]" x-text="selectedPlan ? selectedPlan.name : ''"></span></span>
                                        <span x-text="availableBoilerplates.length + ' item'"></span>
                                    </div>
                                </template>

                                <!-- Loop Matching Boilerplates -->
                                <template x-for="bp in availableBoilerplates.filter(p => !searchBp || p.title.toLowerCase().includes(searchBp.toLowerCase()) || (p.description || '').toLowerCase().includes(searchBp.toLowerCase()))" :key="bp.id">
                                    <div 
                                        @click="selectBoilerplate(bp)"
                                        :class="selectedBoilerplateId === bp.id ? 'bg-blue-50/90 border-[#2563EB] ring-1 ring-[#2563EB]' : 'border-slate-100 hover:bg-slate-50 hover:border-slate-200'"
                                        class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all group text-left gap-2.5"
                                    >
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img 
                                                :src="bp.image_url || '/placeholder.png'" 
                                                :alt="bp.title"
                                                class="w-14 h-14 sm:w-16 sm:h-16 object-cover rounded-xl border border-slate-200 bg-slate-100 shrink-0 shadow-2xs group-hover:scale-105 transition-transform"
                                                onerror="this.src='/placeholder.png'"
                                            >
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <p class="text-xs sm:text-sm font-black text-slate-900 truncate" x-text="bp.title"></p>
                                                    <span 
                                                        x-show="bp.package_tier" 
                                                        class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-blue-100 text-blue-800" 
                                                        x-text="'Paket ' + bp.package_tier"
                                                    ></span>
                                                </div>
                                                <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-1" x-text="bp.description || bp.overview"></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <template x-if="bp.live_url">
                                                        <a 
                                                            :href="bp.live_url" 
                                                            target="_blank" 
                                                            @click.stop 
                                                            class="text-[10px] sm:text-[11px] font-bold text-[#2563EB] hover:underline inline-flex items-center gap-0.5"
                                                        >
                                                            <span>Lihat Demo</span>
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div 
                                            :class="selectedBoilerplateId === bp.id ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'border-2 border-slate-300 bg-white'"
                                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 ml-2 shadow-2xs"
                                        >
                                            <svg x-show="selectedBoilerplateId === bp.id" class="w-3 h-3 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </template>

                                <!-- Other Tier Boilerplates in Same Category (If user wants to explore/switch tier) -->
                                <template x-if="allCategoryBoilerplates.filter(p => !availableBoilerplates.some(ab => ab.id === p.id)).length > 0">
                                    <div class="pt-3 border-t border-slate-100">
                                        <p class="px-2 pb-1.5 text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400">
                                            Template di Paket Layanan Lain (Klik untuk beralih paket):
                                        </p>
                                        <template x-for="bp in allCategoryBoilerplates.filter(p => !availableBoilerplates.some(ab => ab.id === p.id) && (!searchBp || p.title.toLowerCase().includes(searchBp.toLowerCase())))" :key="'other-' + bp.id">
                                            <div 
                                                @click="selectBoilerplate(bp)"
                                                class="flex items-center justify-between p-2.5 rounded-xl border border-dashed border-slate-200 hover:border-blue-300 hover:bg-blue-50/50 cursor-pointer transition-all mb-1.5 text-left opacity-85 hover:opacity-100 gap-2"
                                            >
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <img 
                                                        :src="bp.image_url || '/placeholder.png'" 
                                                        :alt="bp.title"
                                                        class="w-11 h-11 object-cover rounded-lg border border-slate-200 bg-slate-100 shrink-0"
                                                        onerror="this.src='/placeholder.png'"
                                                    >
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <p class="text-xs font-bold text-slate-800 truncate" x-text="bp.title"></p>
                                                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-slate-100 text-slate-700" x-text="'Paket ' + (bp.package_tier || 'Lain')"></span>
                                                        </div>
                                                        <p class="text-[10px] text-slate-400 font-medium truncate" x-text="bp.description || bp.overview"></p>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-[#2563EB] whitespace-nowrap ml-2">Pilih &amp; Ubah Paket →</span>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Empty Search State -->
                                <template x-if="allCategoryBoilerplates.length === 0 || (searchBp && availableBoilerplates.filter(p => p.title.toLowerCase().includes(searchBp.toLowerCase())).length === 0)">
                                    <div class="p-4 text-center text-xs text-slate-400">
                                        Tidak ada template yang cocok dengan pencarian.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Add-on Features -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">4</span> 
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

                    <!-- Step 5: Contact & Payment Scheme -->
                    <div>
                        <label class="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">5</span> 
                            Informasi Pemesan &amp; Skema Pembayaran
                        </label>
                        
                        <!-- Logged-in Customer Badge -->
                        <template x-if="currentUser">
                            <div class="mb-4 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-[#0A1E5E] text-[#C7F236] flex items-center justify-center text-xs font-black">
                                        <span x-text="currentUser.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800" x-text="'Masuk sebagai: ' + currentUser.name"></p>
                                        <p class="text-[10px] text-slate-500 font-medium" x-text="currentUser.email"></p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Akun Terhubung</span>
                            </div>
                        </template>

                        <!-- Guest Prompt -->
                        <template x-if="!currentUser">
                            <div class="mb-4 bg-blue-50/80 border border-blue-200/80 rounded-2xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-blue-900 font-medium">
                                    <i data-lucide="lock" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                    <span>Sudah punya akun? Masuk untuk menyimpan pesanan ke portal Anda.</span>
                                </div>
                                <button 
                                    type="button" 
                                    @click="$dispatch('open-auth-modal', { mode: 'login' })"
                                    class="text-xs font-bold text-[#2563EB] hover:underline shrink-0 ml-2"
                                >
                                    Masuk
                                </button>
                            </div>
                        </template>

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

                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5 flex items-center justify-between">
                                <span>Catatan / Spesifikasi Proyek (Opsional)</span>
                                <span class="text-[10px] font-semibold" :class="(formData.details?.length || 0) >= 100 ? 'text-rose-600 font-bold' : 'text-slate-400'">
                                    <span x-text="formData.details ? formData.details.length : 0"></span>/100 karakter
                                </span>
                            </label>
                            <textarea 
                                x-model="formData.details"
                                rows="3"
                                maxlength="100"
                                placeholder="Tuliskan catatan singkat proyek Anda (maksimal 100 karakter)..."
                                class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] resize-none"
                            ></textarea>

                            <!-- Guideline / Long Instruction Helper Note -->
                            <div class="mt-2.5 p-3 rounded-xl bg-amber-50/90 border border-amber-200/90 flex items-start gap-2.5 text-xs text-amber-900 leading-relaxed shadow-2xs">
                                <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                                <p class="font-medium text-[11px]">
                                    <strong class="font-bold text-amber-950">Catatan:</strong> Maksimal 100 karakter. Jika Anda memiliki instruksi yang panjang, brief detail, atau file <i>guideline</i> proyek, dimohon untuk melampirkannya pada bagian berkas di bawah ini.
                                </p>
                            </div>

                            <!-- Interactive File Attachment Component (From Device) -->
                            <div class="mt-3">
                                <!-- Hidden File Input -->
                                <input 
                                    type="file" 
                                    id="estimator-file-input"
                                    @change="handleFileSelect($event)"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.png,.jpg,.jpeg,.webp"
                                    class="hidden"
                                >

                                <!-- Dropzone / Attach Button State (When NO file selected) -->
                                <div 
                                    x-show="!attachmentFile"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleFileDrop($event)"
                                    @click="document.getElementById('estimator-file-input').click()"
                                    :class="isDragging ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20' : 'border-slate-200 hover:border-[#2563EB]/60 hover:bg-slate-50/80 bg-white'"
                                    class="border-2 border-dashed rounded-2xl p-4 transition-all duration-200 cursor-pointer text-center group"
                                >
                                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] group-hover:bg-[#2563EB] group-hover:text-white flex items-center justify-center transition-colors shrink-0 shadow-2xs">
                                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                                        </div>
                                        <div class="text-center sm:text-left">
                                            <p class="text-xs font-bold text-slate-800 group-hover:text-[#2563EB] transition-colors flex items-center justify-center sm:justify-start gap-1.5 flex-wrap">
                                                <span>Sisipkan Berkas / Dokumen dari Perangkat</span>
                                                <span class="text-[10px] font-semibold text-slate-400">(PDF, DOC, ZIP, Gambar)</span>
                                            </p>
                                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                                Klik untuk memilih berkas dari HP / Komputer, atau seret ke sini (Maks. 50MB)
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected File Preview Card (When file IS selected) -->
                                <div 
                                    x-show="attachmentFile" 
                                    x-cloak
                                    class="rounded-2xl border-2 border-blue-200 bg-blue-50/60 p-4 flex items-center justify-between gap-3 shadow-xs"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <!-- Thumbnail or File Type Badge -->
                                        <template x-if="attachmentPreview">
                                            <img :src="attachmentPreview" alt="Preview" class="w-12 h-12 object-cover rounded-xl border border-blue-200 shadow-2xs shrink-0">
                                        </template>
                                        <template x-if="!attachmentPreview">
                                            <div class="w-12 h-12 rounded-xl bg-[#2563EB] text-white flex flex-col items-center justify-center shrink-0 shadow-2xs">
                                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                                <span class="text-[8px] font-black uppercase tracking-wider" x-text="attachmentType"></span>
                                            </div>
                                        </template>

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-xs font-bold text-slate-900 truncate" x-text="attachmentName"></p>
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 shrink-0">Siap Diunggah</span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center gap-1.5">
                                                <span x-text="attachmentSize"></span>
                                                <span>&bull;</span>
                                                <span class="text-[#2563EB] font-bold uppercase text-[10px]" x-text="attachmentType + ' Document'"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions: Change or Remove -->
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button 
                                            type="button" 
                                            @click.stop="document.getElementById('estimator-file-input').click()"
                                            class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:text-[#2563EB] hover:border-blue-300 text-[11px] font-bold shadow-2xs transition-all cursor-pointer"
                                            title="Ganti Berkas"
                                        >
                                            Ganti
                                        </button>
                                        <button 
                                            type="button" 
                                            @click.stop="removeAttachment()"
                                            class="p-1.5 rounded-lg bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 text-xs font-bold shadow-2xs transition-all cursor-pointer"
                                            title="Hapus Berkas"
                                        >
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                <div x-show="estimatorStep === 'payment_methods'" x-cloak class="space-y-6" x-data="{ paymentCategoryTab: 'all' }">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xl font-black text-slate-900">Pilih Metode Pembayaran Pakasir</h3>
                                <span class="text-[10px] font-black bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-200 uppercase">Resmi Pakasir</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Tagihan: <span class="font-bold text-[#2563EB]" x-text="'Rp ' + formatRupiah(payableAmount)"></span>
                                (<span x-text="formData.paymentScheme === 'dp_50' ? 'DP 50%' : 'Lunas 100%'"></span>) &bull; <span class="text-emerald-600 font-bold">Bebas Biaya Admin (Rp0)</span>
                            </p>
                        </div>
                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'"
                            class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
                        >
                            <span>← Kembali</span>
                        </button>
                    </div>

                    <!-- Category Filter Tabs -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                        <button 
                            type="button" 
                            @click="paymentCategoryTab = 'all'"
                            :class="paymentCategoryTab === 'all' ? 'bg-[#0A1E5E] text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                            class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap cursor-pointer"
                        >
                            Semua Channel (14)
                        </button>
                        <button 
                            type="button" 
                            @click="paymentCategoryTab = 'qris_ewallet'"
                            :class="paymentCategoryTab === 'qris_ewallet' ? 'bg-[#0A1E5E] text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                            class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap cursor-pointer"
                        >
                            QRIS &amp; E-Wallet (5)
                        </button>
                        <button 
                            type="button" 
                            @click="paymentCategoryTab = 'va_bank'"
                            :class="paymentCategoryTab === 'va_bank' ? 'bg-[#0A1E5E] text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                            class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap cursor-pointer"
                        >
                            Virtual Account (7)
                        </button>
                        <button 
                            type="button" 
                            @click="paymentCategoryTab = 'retail'"
                            :class="paymentCategoryTab === 'retail' ? 'bg-[#0A1E5E] text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                            class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap cursor-pointer"
                        >
                            Gerai Retail (2)
                        </button>
                    </div>

                    <!-- Pakasir Channels List -->
                    <div class="space-y-3 max-h-[460px] overflow-y-auto pr-1">

                        <!-- CATEGORY 1: QRIS & E-WALLET -->
                        <div x-show="paymentCategoryTab === 'all' || paymentCategoryTab === 'qris_ewallet'" class="space-y-2.5">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider px-1">QRIS &amp; E-Wallet Instant</p>
                            
                            <!-- QRIS Instant -->
                            <label 
                                :class="selectedPaymentChannel === 'qris' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="qris" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-bold text-slate-900 text-xs sm:text-sm">QRIS Instant</p>
                                            <span class="text-[9px] font-black bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded uppercase">Populer</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">BCA Mobile, Livin Mandiri, BRImo, GoPay, OVO, Dana, ShopeePay</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full uppercase shrink-0">Instant</span>
                            </label>

                            <!-- GoPay -->
                            <label 
                                :class="selectedPaymentChannel === 'gopay' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="gopay" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/gopay.svg') }}" alt="GoPay" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">GoPay Instant</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Pembayaran langsung &amp; scan QR GoPay</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">E-Wallet</span>
                            </label>

                            <!-- ShopeePay -->
                            <label 
                                :class="selectedPaymentChannel === 'shopeepay' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="shopeepay" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/shopeepay.svg') }}" alt="ShopeePay" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">ShopeePay</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Pembayaran instan via ShopeePay</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">E-Wallet</span>
                            </label>

                            <!-- DANA -->
                            <label 
                                :class="selectedPaymentChannel === 'dana' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="dana" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/dana.svg') }}" alt="DANA" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">DANA</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Pembayaran instan via akun DANA</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">E-Wallet</span>
                            </label>

                            <!-- OVO -->
                            <label 
                                :class="selectedPaymentChannel === 'ovo' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="ovo" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/ovo.svg') }}" alt="OVO" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">OVO</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Pembayaran instan via akun OVO</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">E-Wallet</span>
                            </label>
                        </div>

                        <!-- CATEGORY 2: VIRTUAL ACCOUNT BANK -->
                        <div x-show="paymentCategoryTab === 'all' || paymentCategoryTab === 'va_bank'" class="space-y-2.5 pt-2">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider px-1">Virtual Account Bank (Verifikasi Otomatis 24 Jam)</p>
                            
                            <!-- VA BCA -->
                            <label 
                                :class="selectedPaymentChannel === 'va_bca' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_bca" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">BCA Virtual Account</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">BCA Mobile, KlikBCA, myBCA &amp; ATM</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA Mandiri -->
                            <label 
                                :class="selectedPaymentChannel === 'va_mandiri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_mandiri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">Mandiri Virtual Account</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Livin' by Mandiri, Kopra &amp; ATM</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA BRI -->
                            <label 
                                :class="selectedPaymentChannel === 'va_bri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_bri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/bri.svg') }}" alt="BRI" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">BRI Virtual Account (BRIVA)</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">BRImo, Internet Banking &amp; ATM BRI</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA BNI -->
                            <label 
                                :class="selectedPaymentChannel === 'va_bni' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_bni" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/bni.svg') }}" alt="BNI" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">BNI Virtual Account</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">BNI Mobile Banking, Direct &amp; ATM BNI</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA Permata -->
                            <label 
                                :class="selectedPaymentChannel === 'va_permata' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_permata" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/permata.svg') }}" alt="Permata" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">Permata Virtual Account</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">PermataMobile X, PermataNet &amp; ATM</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA CIMB Niaga -->
                            <label 
                                :class="selectedPaymentChannel === 'va_cimb' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_cimb" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/cimb.svg') }}" alt="CIMB Niaga" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">CIMB Niaga Virtual Account</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">OCTO Mobile, OCTO Clicks &amp; ATM</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">VA Otomatis</span>
                            </label>

                            <!-- VA BSI -->
                            <label 
                                :class="selectedPaymentChannel === 'va_bsi' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="va_bsi" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/bsi.svg') }}" alt="BSI" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">Bank Syariah Indonesia (BSI)</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">BSI Mobile, BSI Net &amp; ATM BSI</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0">Syariah</span>
                            </label>
                        </div>

                        <!-- CATEGORY 3: GERAI RETAIL -->
                        <div x-show="paymentCategoryTab === 'all' || paymentCategoryTab === 'retail'" class="space-y-2.5 pt-2">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider px-1">Gerai Retail / Minimarket</p>
                            
                            <!-- Indomaret -->
                            <label 
                                :class="selectedPaymentChannel === 'retail_indomaret' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="retail_indomaret" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/indomaret.png') }}" alt="Indomaret" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">Indomaret / Ceriamart</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Bayar tunai di seluruh kasir Indomaret</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">Retail</span>
                            </label>

                            <!-- Alfamart -->
                            <label 
                                :class="selectedPaymentChannel === 'retail_alfamart' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_channel" value="retail_alfamart" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                    <div class="w-16 h-10 bg-white border border-slate-200/80 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/payments/alfamart.svg') }}" alt="Alfamart" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm">Alfamart / Alfamidi / Dan+Dan</p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">Bayar tunai di seluruh kasir Alfamart Group</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">Retail</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons: Bayar & Kembali -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'"
                            class="w-1/3 py-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all cursor-pointer"
                        >
                            ← Kembali
                        </button>

                        <button 
                            type="button"
                            @click="processFinalPayment()"
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : ''"
                            class="w-2/3 flex items-center justify-center gap-2 rounded-xl py-4 text-xs sm:text-sm font-black transition-all duration-300 bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25 cursor-pointer"
                        >
                            <span x-show="!isSubmitting">Bayar Rp <span x-text="formatRupiah(payableAmount)"></span> Sekarang</span>
                            <span x-show="isSubmitting">Menyiapkan Channel Pakasir...</span>
                            <i x-show="!isSubmitting" data-lucide="credit-card" class="w-4 h-4"></i>
                            <i x-show="isSubmitting" data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </button>
                    </div>
                </div>

                <!-- VIEW 3: Payment Instructions & Cetak Resi Button (In-Place) -->
                <div x-show="estimatorStep === 'payment_instruction'" x-cloak class="space-y-6 text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-[#2563EB] mx-auto flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i data-lucide="qr-code" class="w-8 h-8 stroke-[2.5]"></i>
                    </div>

                    <div>
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-black uppercase px-3 py-1 rounded-full">
                            Nomor Invoice: <span x-text="createdOrder?.invoice_number"></span>
                        </span>
                        <h3 class="text-2xl font-black text-slate-900 mt-2">Selesaikan Pembayaran</h3>
                        <p class="text-slate-500 text-xs font-medium mt-1">
                            Total Tagihan: <span class="font-bold text-[#2563EB] text-sm" x-text="'Rp ' + formatRupiah(pakasirData?.total_payment || payableAmount)"></span>
                            <span x-show="pakasirData?.fee > 0" class="text-[11px] text-slate-400">(Biaya layanan Rp <span x-text="formatRupiah(pakasirData?.fee)"></span>)</span>
                        </p>
                    </div>

                    <!-- Dynamic Payment Display Box Generated Live from Pakasir -->
                    <div class="bg-slate-50 border-2 border-slate-200 rounded-2xl p-6 text-left space-y-4">
                        
                        <!-- 1. QRIS Display (Live Barcode from Pakasir) -->
                        <template x-if="selectedPaymentChannel === 'qris' || ['gopay', 'ovo', 'shopeepay', 'dana'].includes(selectedPaymentChannel) || !selectedPaymentChannel.startsWith('va_')">
                            <div class="text-center space-y-3">
                                <div class="inline-flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-2xs mb-1">
                                    <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" class="h-4 w-auto">
                                    <span class="text-[11px] font-bold text-slate-700">QRIS Resmi Pakasir (Semua Bank &amp; E-Wallet)</span>
                                </div>
                                <p class="text-xs font-bold text-slate-700 uppercase">Pindai Kode QR Resmi Di Bawah Ini</p>
                                <div class="bg-white p-3 inline-block rounded-2xl border border-slate-200 shadow-sm">
                                    <img 
                                        :src="pakasirData?.qr_image_url || ('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(pakasirData?.payment_url || ''))" 
                                        alt="QRIS Resmi Pakasir" 
                                        class="w-60 h-60 mx-auto object-contain rounded-xl"
                                    >
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 text-left space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Merchant:</span>
                                        <span class="font-bold text-slate-900">JUANGDEV / PAKASIR</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Total yang Harus Dibayar:</span>
                                        <span class="font-black text-[#2563EB]" x-text="'Rp ' + formatRupiah(pakasirData?.total_payment || payableAmount)"></span>
                                    </div>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium max-w-sm mx-auto">
                                    Buka aplikasi <strong>BCA Mobile, Livin Mandiri, BRImo, BNI, GoPay, OVO, Dana, ShopeePay</strong> dan scan QRIS di atas untuk menyelesaikan pembayaran secara instan.
                                </p>
                            </div>
                        </template>

                        <!-- 2. Virtual Account Display (Live VA from Pakasir for BNI, BRI, Permata, etc.) -->
                        <template x-if="selectedPaymentChannel.startsWith('va_')">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                    <span class="text-xs font-bold text-slate-700 uppercase">Nomor Virtual Account Resmi</span>
                                    <div class="h-6 w-auto flex items-center">
                                        <template x-if="selectedPaymentChannel === 'va_bca'"><img src="{{ asset('images/payments/bca.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_mandiri'"><img src="{{ asset('images/payments/mandiri.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_bri'"><img src="{{ asset('images/payments/bri.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_bni'"><img src="{{ asset('images/payments/bni.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_permata'"><img src="{{ asset('images/payments/permata.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_cimb'"><img src="{{ asset('images/payments/cimb.svg') }}" class="h-5 w-auto"></template>
                                        <template x-if="selectedPaymentChannel === 'va_bsi'"><img src="{{ asset('images/payments/bsi.svg') }}" class="h-5 w-auto"></template>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nomor Rekening Virtual Account</p>
                                        <p class="font-mono font-black text-xl text-slate-900 tracking-wider" x-text="pakasirData?.payment_number || '8801827399120019'"></p>
                                    </div>
                                    <button 
                                        type="button" 
                                        @click="navigator.clipboard.writeText(pakasirData?.payment_number || '8801827399120019'); alert('Nomor Virtual Account berhasil disalin!');" 
                                        class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-[#2563EB] text-xs font-bold rounded-lg border border-blue-200 transition-colors cursor-pointer"
                                    >
                                        Salin VA
                                    </button>
                                </div>

                                <div class="flex justify-between items-center text-xs text-slate-700 bg-amber-50/80 p-3 rounded-xl border border-amber-200">
                                    <span>Nominal Transfer Tepat:</span>
                                    <span class="font-black text-slate-900 text-sm" x-text="'Rp ' + formatRupiah(pakasirData?.total_payment || payableAmount)"></span>
                                </div>

                                <template x-if="pakasirData?.qr_image_url && selectedPaymentChannel !== 'va_bni' && selectedPaymentChannel !== 'va_bri' && selectedPaymentChannel !== 'va_permata'">
                                    <div class="text-center pt-2 space-y-2">
                                        <p class="text-[11px] font-bold text-slate-600">Atau Scan QRIS di Bawah Ini:</p>
                                        <div class="bg-white p-2.5 inline-block rounded-xl border border-slate-200">
                                            <img :src="pakasirData?.qr_image_url" class="w-44 h-44 mx-auto object-contain">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Live Polling & Auto Verification Indicator -->
                        <div class="flex items-center justify-center gap-2 p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                            </span>
                            <span>Menunggu Pembayaran (Verifikasi Otomatis Real-Time)</span>
                        </div>
                    </div>

                    <!-- Payment Action Buttons (Status Check & Print Receipt) -->
                    <div class="space-y-2.5">
                        <button 
                            type="button" 
                            @click="checkOrderPaymentStatus(true)"
                            :disabled="isCheckingPayment"
                            class="w-full py-3.5 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-black text-xs flex items-center justify-center gap-2 shadow-md shadow-[#2563EB]/25 transition-all cursor-pointer"
                        >
                            <i data-lucide="refresh-cw" :class="isCheckingPayment ? 'animate-spin' : ''" class="w-4 h-4"></i>
                            <span x-text="isCheckingPayment ? 'Memeriksa Pembayaran...' : 'Saya Sudah Bayar (Cek Status Real-Time)'"></span>
                        </button>

                        <button 
                            type="button" 
                            @click="cancelCurrentOrder()"
                            class="w-full py-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-bold text-xs flex items-center justify-center gap-2 border border-rose-200 transition-all cursor-pointer"
                        >
                            <i data-lucide="x-circle" class="w-4 h-4 text-rose-500"></i>
                            <span>Batalkan Pesanan</span>
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

                        <template x-if="createdOrder?.notes">
                            <div class="pt-2 border-t border-slate-200 text-xs">
                                <span class="text-slate-500 font-medium block mb-1">Catatan / Kebutuhan Klien:</span>
                                <p class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium text-[11px]" x-text="createdOrder?.notes"></p>
                            </div>
                        </template>

                        <template x-if="createdOrder?.attachment_name">
                            <div class="pt-2 border-t border-slate-200 text-xs flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i data-lucide="paperclip" class="w-3.5 h-3.5 text-[#2563EB] shrink-0"></i>
                                    <span class="text-slate-600 font-bold truncate text-[11px]" x-text="createdOrder?.attachment_name"></span>
                                    <span class="text-[10px] text-slate-400 shrink-0" x-text="'(' + (createdOrder?.formatted_attachment_size || '') + ')'"></span>
                                </div>
                                <a 
                                    :href="createdOrder?.attachment_url" 
                                    target="_blank" 
                                    class="text-[11px] font-bold text-[#2563EB] hover:underline flex items-center gap-1 shrink-0"
                                >
                                    <span>Lihat File</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </div>
                        </template>
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

                            <!-- Pakasir Channels List with Logos -->
                            <div class="space-y-2 max-h-[260px] overflow-y-auto pr-1">
                                <!-- QRIS -->
                                <label 
                                    :class="selectedPaymentChannel === 'qris' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="radio" name="pay_hist_channel" value="qris" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div class="w-12 h-8 bg-white border border-slate-200/80 rounded-lg p-0.5 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 truncate">QRIS Instant (All E-Wallet &amp; M-Banking)</p>
                                            <p class="text-[10px] text-slate-500 font-medium truncate">BCA, GoPay, OVO, ShopeePay, Dana, LinkAja</p>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded uppercase shrink-0">Instant</span>
                                </label>

                                <!-- VA BCA -->
                                <label 
                                    :class="selectedPaymentChannel === 'va_bca' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="radio" name="pay_hist_channel" value="va_bca" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div class="w-12 h-8 bg-white border border-slate-200/80 rounded-lg p-0.5 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <p class="font-bold text-slate-900 truncate">BCA Virtual Account</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">VA Bank</span>
                                </label>

                                <!-- VA Mandiri -->
                                <label 
                                    :class="selectedPaymentChannel === 'va_mandiri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="radio" name="pay_hist_channel" value="va_mandiri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div class="w-12 h-8 bg-white border border-slate-200/80 rounded-lg p-0.5 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <p class="font-bold text-slate-900 truncate">Mandiri Virtual Account</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">VA Bank</span>
                                </label>

                                <!-- VA BRI -->
                                <label 
                                    :class="selectedPaymentChannel === 'va_bri' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="radio" name="pay_hist_channel" value="va_bri" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div class="w-12 h-8 bg-white border border-slate-200/80 rounded-lg p-0.5 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('images/payments/bri.svg') }}" alt="BRI" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <p class="font-bold text-slate-900 truncate">BRI Virtual Account (BRIVA)</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">VA Bank</span>
                                </label>

                                <!-- VA BNI -->
                                <label 
                                    :class="selectedPaymentChannel === 'va_bni' ? 'border-[#2563EB] bg-blue-50/70 ring-2 ring-[#2563EB]/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all text-xs"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="radio" name="pay_hist_channel" value="va_bni" x-model="selectedPaymentChannel" class="text-[#2563EB]">
                                        <div class="w-12 h-8 bg-white border border-slate-200/80 rounded-lg p-0.5 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('images/payments/bni.svg') }}" alt="BNI" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <p class="font-bold text-slate-900 truncate">BNI Virtual Account</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">VA Bank</span>
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
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-center space-y-2 mt-2">
                            <div class="flex items-center justify-center gap-1.5 text-blue-900 font-bold text-xs">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                <span>Pembayaran DP 50% Berhasil Dikonfirmasi</span>
                            </div>
                            <p class="text-[11px] text-blue-800 font-medium leading-relaxed">
                                Notifikasi konfirmasi resmi telah dikirim ke WhatsApp Anda. <b>Sisa kekurangan (50%)</b> dapat Anda lunasi setelah pengerjaan proyek selesai secara langsung melalui menu <b>Detail Pesanan</b> di profil akun Anda.
                            </p>
                            <div class="pt-1 flex flex-wrap items-center justify-center gap-2">
                                <a 
                                    :href="'/customer/orders/' + createdOrder?.invoice_number"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-bold text-xs transition-all shadow-xs"
                                >
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>Lihat Detail Pesanan Ini</span>
                                </a>
                                <a 
                                    href="{{ route('customer.dashboard') }}" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs transition-all shadow-2xs"
                                >
                                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                    <span>Semua Pesanan Saya</span>
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Fully Paid Notice -->
                    <template x-if="createdOrder?.payment_status === 'fully_paid'">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center space-y-2">
                            <p class="font-black text-emerald-800 text-sm">Terima Kasih! Tagihan Proyek Ini Telah LUNAS 100%</p>
                            <p class="text-xs text-emerald-700 font-medium">Seluruh proses pembayaran telah terverifikasi. Tim teknis JuangDev sedang/telah menyelesaikan proyek Anda.</p>
                            <div class="pt-1 flex flex-wrap items-center justify-center gap-2">
                                <a 
                                    :href="'/customer/orders/' + createdOrder?.invoice_number"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-xs"
                                >
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>Lihat Detail Pesanan Ini</span>
                                </a>
                                <a 
                                    href="{{ route('customer.dashboard') }}" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs transition-all shadow-2xs"
                                >
                                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                    <span>Semua Pesanan Saya</span>
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Print Receipt Button -->
                    <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                        <button 
                            type="button" 
                            @click="printReceipt()"
                            class="w-full sm:w-1/2 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            <span>Cetak Resi / Download PDF</span>
                        </button>

                        <button 
                            type="button" 
                            @click="estimatorStep = 'form'; createdOrder = null;"
                            class="w-full sm:w-1/2 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all cursor-pointer"
                        >
                            + Hitung Estimasi Baru
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Formal E-Receipt Component -->
@include('partials.receipt-modal')

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

    // Default values
    var inv = '', name = '', phone = '', maskedPhone = '', proj = '-', service = '-', pkg = '', dateStr = '';
    var total = 'Rp 0', dp = 'Rp 0', rem = 'Rp 0', currentPaid = 'Rp 0';
    var status = 'MENUNGGU PEMBAYARAN';
    var title = 'Tagihan Transaksi Resmi';
    var trxType = 'Tagihan Menunggu Pembayaran';
    var payStatus = 'unpaid';
    var totalNum = 0, dpNum = 0, remNum = 0;

    // 1. Try Blade server-side values first
    @if(isset($order))
    inv = @json($order->invoice_number);
    name = @json($order->customer_name);
    phone = @json($order->customer_phone);
    proj = @json($order->project_name ?? '-');
    service = @json($order->service_name ?? '-');
    pkg = @json($order->package_name ?? '');
    dateStr = @json($order->created_at->format('Y-m-d H:i:s') . ' WIB');
    totalNum = {{ $order->total_amount }};
    dpNum = {{ $order->dp_amount }};
    remNum = {{ $order->remaining_amount }};
    payStatus = @json($order->payment_status);
    @endif

    // 2. Override with Alpine.js data
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
                if (o.package_name) pkg = o.package_name;
                if (o.total_amount !== undefined) totalNum = Number(o.total_amount);
                if (o.dp_amount !== undefined) dpNum = Number(o.dp_amount);
                if (o.remaining_amount !== undefined) remNum = Number(o.remaining_amount);
                if (o.payment_status) payStatus = o.payment_status;
                if (o.created_at) {
                    var d = new Date(o.created_at);
                    var yyyy = d.getFullYear();
                    var mm = String(d.getMonth() + 1).padStart(2, '0');
                    var dd = String(d.getDate()).padStart(2, '0');
                    var hh = String(d.getHours()).padStart(2, '0');
                    var min = String(d.getMinutes()).padStart(2, '0');
                    var ss = String(d.getSeconds()).padStart(2, '0');
                    dateStr = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min + ':' + ss + ' WIB';
                }
            }
            if (alpineData && alpineData.formData && !name) {
                if (alpineData.formData.name) name = alpineData.formData.name;
                if (alpineData.formData.phone) phone = alpineData.formData.phone;
            }
            if (alpineData && (!proj || proj === '-')) {
                if (alpineData.formData && alpineData.formData.projectName) {
                    proj = alpineData.formData.projectName;
                } else if (alpineData.selectedBoilerplate) {
                    proj = alpineData.selectedBoilerplate.title;
                }
            }
            if (alpineData && alpineData.selectedService && (!service || service === '-')) {
                service = alpineData.selectedService.name || service;
            }
            if (alpineData && alpineData.selectedPlan && !pkg) {
                pkg = alpineData.selectedPlan.name || '';
            }
            if (alpineData && alpineData.totalPrice && totalNum === 0) {
                totalNum = Number(alpineData.totalPrice);
                dpNum = Math.round(totalNum * 0.5);
                remNum = totalNum - dpNum;
            }
        } catch(e) {
            console.error('Alpine data read error:', e);
        }
    }

    // Mask phone number
    if (phone && phone.length >= 8) {
        maskedPhone = phone.substring(0, 4) + ' **** **** ' + phone.substring(phone.length - 3);
    } else {
        maskedPhone = phone || '-';
    }

    // 3. Compute formatted values & status
    total = formatRupiah(totalNum);
    dp = formatRupiah(dpNum);
    rem = formatRupiah(remNum);

    if (payStatus === 'fully_paid') {
        title = 'Transaksi Berhasil (Lunas 100%)';
        trxType = 'Pelunasan Proyek (100% LUNAS)';
        currentPaid = total;
        rem = 'Rp 0 (LUNAS)';
    } else if (payStatus === 'dp_paid') {
        title = 'Transaksi Berhasil (DP 50%)';
        trxType = 'Pembayaran Uang Muka (DP 50%)';
        currentPaid = dp;
    } else {
        title = 'Tagihan Transaksi Resmi';
        trxType = 'Tagihan Menunggu Pembayaran';
        currentPaid = dp;
    }

    if (!dateStr) {
        var now = new Date();
        var yyyy = now.getFullYear();
        var mm = String(now.getMonth() + 1).padStart(2, '0');
        var dd = String(now.getDate()).padStart(2, '0');
        var hh = String(now.getHours()).padStart(2, '0');
        var min = String(now.getMinutes()).padStart(2, '0');
        var ss = String(now.getSeconds()).padStart(2, '0');
        dateStr = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min + ':' + ss + ' WIB';
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
    setEl('.rec-phone', maskedPhone);
    setEl('.rec-trx-type', trxType);
    setEl('.rec-proj', proj || '-');
    setEl('.rec-service', service || '-');
    if (pkg) setEl('.rec-pkg', 'Paket: ' + pkg);
    setEl('.rec-notes', proj && proj !== '-' ? proj : 'Pembayaran Resmi Proyek JuangDev');
    setEl('.rec-total-cost', total);
    setEl('.rec-dp', dp);
    setEl('.rec-rem', rem);
    setEl('.rec-total-highlight', currentPaid);

    var origNum = 0, discNum = 0;
    if (alpineData && alpineData.createdOrder) {
        if (alpineData.createdOrder.original_amount !== undefined) origNum = Number(alpineData.createdOrder.original_amount);
        if (alpineData.createdOrder.discount_amount !== undefined) discNum = Number(alpineData.createdOrder.discount_amount);
    } else if (alpineData) {
        if (alpineData.originalTotalPrice) origNum = Number(alpineData.originalTotalPrice);
        if (alpineData.discountSavings) discNum = Number(alpineData.discountSavings);
    }
    if (discNum > 0 || origNum > totalNum) {
        setEl('.rec-orig-cost', formatRupiah(origNum || totalNum));
        setEl('.rec-disc-cost', '- ' + formatRupiah(discNum));
    }

    // 5. Open print popup - BRImo Style Clean E-Receipt View
    var printWin = window.open('', '_blank', 'width=780,height=920');
    if (!printWin) {
        window.print();
        return;
    }
    var css = [
        '@page { size: A4 portrait; margin: 15mm 10mm; }',
        '* { box-sizing: border-box; margin: 0; padding: 0; }',
        'body {',
        '  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;',
        '  background: #0086E5; color: #0f172a;',
        '  padding: 30px 15px; display: flex; justify-content: center; align-items: flex-start;',
        '  min-height: 100vh;',
        '}',
        '.receipt-container { width: 100%; max-width: 440px; margin: 0 auto; }',
        '.receipt-card {',
        '  background: #ffffff; border-radius: 28px;',
        '  padding: 32px 28px;',
        '  box-shadow: 0 20px 45px rgba(0,0,0,0.18);',
        '  position: relative; overflow: hidden;',
        '}',
        '.text-center { text-align: center; }',
        '.flex { display: flex; justify-content: space-between; align-items: flex-start; }',
        '.items-center { align-items: center; }',
        '.font-bold { font-weight: 700; }',
        '.font-black { font-weight: 900; }',
        '.font-semibold { font-weight: 600; }',
        '.font-medium { font-weight: 500; }',
        '.font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }',
        '.uppercase { text-transform: uppercase; }',
        '.text-xs { font-size: 13px; }',
        '.text-sm { font-size: 14px; }',
        '.text-lg { font-size: 18px; }',
        '.text-xl { font-size: 20px; }',
        '.text-2xl { font-size: 24px; }',
        '.text-3xl { font-size: 30px; }',
        '.text-slate-900 { color: #0f172a; }',
        '.text-slate-800 { color: #1e293b; }',
        '.text-slate-700 { color: #334155; }',
        '.text-slate-500 { color: #64748b; }',
        '.text-slate-400 { color: #94a3b8; }',
        '.text-right { text-align: right; }',
        '.border-b { border-bottom: 1px solid #f1f5f9; }',
        '.border-t { border-top: 1px solid #f1f5f9; }',
        '.border-t-2 { border-top: 2px dashed #cbd5e1; }',
        '.py-2 { padding-top: 8px; padding-bottom: 8px; }',
        '.py-3 { padding-top: 12px; padding-bottom: 12px; }',
        '.py-4 { padding-top: 16px; padding-bottom: 16px; }',
        '.pb-5 { padding-bottom: 20px; }',
        '.pt-2 { padding-top: 8px; }',
        '.pt-3 { padding-top: 12px; }',
        '.mt-4 { margin-top: 16px; }',
        '.mb-1\\.5 { margin-bottom: 6px; }',
        '.space-y-2 > * + * { margin-top: 8px; }',
        '.space-y-2\\.5 > * + * { margin-top: 10px; }',
        '.relative { position: relative; }',
        '.my-3 { margin-top: 12px; margin-bottom: 12px; }',
        '.scallop-top, .scallop-bottom { display: flex; justify-content: space-between; overflow: hidden; width: 100%; }',
        '.scallop-top span, .scallop-bottom span { width: 12px; height: 12px; background: #0086E5; border-radius: 50%; flex-shrink: 0; }',
        '.scallop-top span { margin-top: -8px; }',
        '.scallop-bottom span { margin-bottom: -8px; }',
        '.rec-total-highlight { color: #0086E5; font-weight: 900; font-size: 30px; letter-spacing: -0.5px; }',
        '@media print {',
        '  body { background: #ffffff !important; padding: 0 !important; }',
        '  .receipt-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }',
        '  .scallop-top span, .scallop-bottom span { background: #ffffff !important; }',
        '}'
    ].join('\n');

    printWin.document.write('<!DOCTYPE html><html><head><title>Bukti Transaksi Resmi - JuangDev</title><meta charset="utf-8"><style>' + css + '</style></head><body><div class="receipt-container">' + clone.innerHTML + '</div><scr' + 'ipt>setTimeout(function(){window.print();},400);</scr' + 'ipt></body></html>');
    printWin.document.close();
}
</script>

