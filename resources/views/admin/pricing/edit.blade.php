@extends('layouts.admin')

@section('title', 'Edit Paket Harga')
@section('page_title', 'Edit Paket: ' . $pricing->name)

@section('content')
    <div 
        class="max-w-5xl"
        x-data="{
            name: '{{ old('name', $pricing->name) }}',
            category: '{{ old('category', $pricing->category) }}',
            badge: '{{ old('badge', $pricing->badge ?? '') }}',
            popular: '{{ old('popular', $pricing->popular ? '1' : '0') }}',
            originalPrice: '{{ old('original_price', $pricing->original_price ?? '') }}',
            price: '{{ old('price', $pricing->price ?? '') }}',
            discountPercent: '{{ old('discount_percent', $pricing->discount_percent ?? '') }}',
            description: {{ json_encode(old('description', $pricing->description ?? '')) }},
            
            parseNumber(val) {
                if (!val) return 0;
                if (typeof val === 'number') return val;
                let s = val.toString().toLowerCase().trim();
                if (s.includes('-')) s = s.split('-')[0].trim();
                if (s.includes('k')) {
                    let clean = s.replace(/[^0-9]/g, '').trim();
                    let num = parseInt(clean);
                    return isNaN(num) ? 0 : num * 1000;
                }
                let num = parseInt(s.replace(/[^0-9]/g, ''));
                return isNaN(num) ? 0 : num;
            },
            
            formatNumber(num) {
                if (!num && num !== 0) return '0';
                return new Intl.NumberFormat('id-ID').format(num);
            },
            
            get originalNum() {
                return this.parseNumber(this.originalPrice);
            },
            
            get finalNum() {
                return this.parseNumber(this.price);
            },
            
            get savingsNum() {
                if (this.originalNum > 0 && this.finalNum > 0 && this.originalNum > this.finalNum) {
                    return this.originalNum - this.finalNum;
                }
                return 0;
            },
            
            get calculatedPercent() {
                if (this.discountPercent && parseInt(this.discountPercent) > 0) {
                    return parseInt(this.discountPercent);
                }
                if (this.originalNum > 0 && this.finalNum > 0 && this.originalNum > this.finalNum) {
                    return Math.round(((this.originalNum - this.finalNum) / this.originalNum) * 100);
                }
                return 0;
            },
            
            applyDiscountPercent(percent) {
                this.discountPercent = percent;
                if (this.originalNum > 0) {
                    let discounted = Math.round(this.originalNum * (1 - (percent / 100)));
                    this.price = this.formatNumber(discounted);
                }
            },
            
            resetDiscount() {
                this.discountPercent = '';
                this.originalPrice = '';
            },
            
            onOriginalPriceInput() {
                if (this.discountPercent && parseInt(this.discountPercent) > 0 && this.originalNum > 0) {
                    let discounted = Math.round(this.originalNum * (1 - (parseInt(this.discountPercent) / 100)));
                    this.price = this.formatNumber(discounted);
                }
            },
            
            onPriceInput() {
                if (this.originalNum > 0 && this.finalNum > 0 && this.originalNum > this.finalNum) {
                    this.discountPercent = Math.round(((this.originalNum - this.finalNum) / this.originalNum) * 100);
                }
            }
        }"
    >
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left 7 Cols: Form Inputs -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <form action="{{ route('admin.pricing.update', $pricing->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Paket *</label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="name"
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Layanan *</label>
                            <select name="category" x-model="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                                <option value="landing-page">Landing Page</option>
                                <option value="company-profile">Company Profile</option>
                                <option value="ecommerce">E-Commerce</option>
                                <option value="sistem-informasi">Sistem Informasi</option>
                                <option value="custom-app">Custom Web App</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Periode / Satuan</label>
                            <input 
                                type="text" 
                                name="period" 
                                value="{{ old('period', $pricing->period ?? 'proyek') }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>
                    </div>

                    <!-- ==============================================
                         HARGA & LIVE INTERACTIVE DISCOUNT CALCULATOR 
                         ============================================== -->
                    <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/60 to-indigo-50/40 border-2 border-blue-200/80 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calculator" class="w-4 h-4 text-[#2563EB]"></i>
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Kalkulator &amp; Diskon Harga Layanan</h4>
                            </div>
                            <span class="text-[10px] font-bold text-[#2563EB] bg-blue-100 px-2 py-0.5 rounded-full">Hitung Otomatis</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Original Price (Sebelum Diskon) -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                    <span>Harga Normal / Asli</span>
                                    <span class="text-[10px] text-slate-400 font-normal">(Harga Coret)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input 
                                        type="text" 
                                        name="original_price" 
                                        x-model="originalPrice"
                                        @input="onOriginalPriceInput()"
                                        placeholder="Contoh: 599.000 atau 599k" 
                                        class="w-full pl-8 pr-3 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-800 bg-white focus:outline-none focus:border-[#2563EB]"
                                    >
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 font-medium">Kosongkan jika paket ini tidak memiliki diskon.</p>
                            </div>

                            <!-- Discount Percentage (%) -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                    <span>Diskon (%)</span>
                                    <span class="text-[10px] text-emerald-600 font-bold" x-show="calculatedPercent > 0" x-text="calculatedPercent + '% OFF'"></span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="discount_percent" 
                                        x-model="discountPercent"
                                        @input="applyDiscountPercent($event.target.value)"
                                        min="0"
                                        max="100"
                                        placeholder="Contoh: 20" 
                                        class="w-full pl-4 pr-8 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-800 bg-white focus:outline-none focus:border-[#2563EB]"
                                    >
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Discount Preset Buttons -->
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Preset Cepat Diskon:</p>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(10)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 10 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    10%
                                </button>
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(15)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 15 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    15%
                                </button>
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(20)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 20 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    20%
                                </button>
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(25)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 25 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    25%
                                </button>
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(30)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 30 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    30%
                                </button>
                                <button 
                                    type="button" 
                                    @click="applyDiscountPercent(50)" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all"
                                    :class="calculatedPercent === 50 ? 'bg-[#2563EB] text-white border-[#2563EB]' : 'bg-white text-slate-700 border-slate-200 hover:bg-blue-50'"
                                >
                                    50%
                                </button>
                                <button 
                                    type="button" 
                                    @click="resetDiscount()" 
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 transition-all ml-auto"
                                >
                                    Reset Diskon
                                </button>
                            </div>
                        </div>

                        <!-- Final Active Price Field -->
                        <div class="pt-2 border-t border-blue-200/60">
                            <label class="block text-xs font-black text-slate-900 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>Harga Final / Promo (Tampilan Teks) *</span>
                                <span class="text-[10px] text-emerald-700 font-bold" x-show="savingsNum > 0" x-text="'Hemat Rp ' + formatNumber(savingsNum)"></span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-[#2563EB]">Rp</span>
                                <input 
                                    type="text" 
                                    name="price" 
                                    x-model="price"
                                    @input="onPriceInput()"
                                    required 
                                    placeholder="Contoh: 399.000 atau 399k" 
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-[#2563EB]/40 bg-white text-base font-black text-slate-900 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20"
                                >
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 font-medium">Harga ini yang akan ditagihkan dan dibayarkan oleh klien di website &amp; estimator.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Ringkas</label>
                        <textarea 
                            name="description" 
                            x-model="description"
                            rows="2" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        ></textarea>
                    </div>

                    <!-- Features Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Fitur Paket (Centang yang sesuai)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 max-h-56 overflow-y-auto">
                            @php
                                $planFeatures = [
                                    '1 Halaman Landing Page', 'Desain Template Premium', 'Mobile Responsive',
                                    'Tombol WhatsApp', 'Waktu Pengerjaan 2 Hari', 'Desain UI/UX Custom',
                                    'Copywriting Persuasif', 'Integrasi Google Analytics', 'Setup Domain & Hosting',
                                    'Animasi Interaktif (GSAP)', 'Form Kontak', 'Galeri Foto',
                                    'CMS untuk Update Konten', 'SEO Basic Setup', 'Katalog Produk',
                                    'Keranjang Belanja', 'Integrasi WA Checkout', 'Payment Gateway Integrasi',
                                    'Perhitungan Ongkir Otomatis', 'Manajemen Inventaris', 'Dashboard Analitik',
                                    'Multi-role Akses', 'API Integration', 'Cloud Server Setup'
                                ];
                                $currentFeats = is_array($pricing->features) ? $pricing->features : explode(',', $pricing->features ?? '');
                                $currentFeats = array_map('trim', $currentFeats);
                                $customFeats = array_diff($currentFeats, $planFeatures);
                            @endphp
                            @foreach($planFeatures as $feat)
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 hover:text-slate-900 select-none">
                                    <input 
                                        type="checkbox" 
                                        name="features[]" 
                                        value="{{ $feat }}" 
                                        {{ in_array($feat, $currentFeats) ? 'checked' : '' }}
                                        class="rounded text-[#2563EB] focus:ring-0"
                                    >
                                    <span>{{ $feat }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Custom Features Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fitur Tambahan Kustom (Opsional)</label>
                        <textarea 
                            name="custom_features" 
                            rows="2" 
                            placeholder="Tulis fitur lain di luar pilihan di atas, 1 fitur per baris..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >{{ implode("\n", $customFeats) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tandai Paling Populer?</label>
                            <select name="popular" x-model="popular" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                                <option value="0">Tidak (Standar)</option>
                                <option value="1">Ya (Populer / Best Seller)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Custom Badge Text (Opsional)</label>
                            <input 
                                type="text" 
                                name="badge" 
                                x-model="badge"
                                placeholder="Contoh: BEST SELLER, REKOMENDASI" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Tampilan *</label>
                            <select name="is_active" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                                <option value="1" {{ $pricing->is_active ? 'selected' : '' }}>Aktif (Tampilkan di Website)</option>
                                <option value="0" {{ !$pricing->is_active ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (Display Order)</label>
                            <input 
                                type="number" 
                                name="display_order" 
                                value="{{ old('display_order', $pricing->display_order ?? 0) }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all cursor-pointer">
                            Update Paket
                        </button>
                        <a href="{{ route('admin.pricing.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Right 5 Cols: Live Real-Time Card Preview -->
            <div class="lg:col-span-5 sticky top-28 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="eye" class="w-4 h-4 text-[#2563EB]"></i>
                        <span>Live Preview Kartu Layanan</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Tampilan Pengunjung</span>
                </div>

                <!-- Preview Card Representation -->
                <div 
                    class="rounded-[2rem] overflow-hidden p-7 transition-all duration-300 relative shadow-xl"
                    :class="popular === '1' || badge 
                        ? 'bg-gradient-to-br from-[#c8f135] to-[#a3c922] border-2 border-[#a3c922] border-b-[8px] border-b-[#82a313] text-[#0A1E5E]' 
                        : 'bg-white border-2 border-slate-200 border-b-[8px] border-b-slate-300 text-slate-900'"
                >
                    <!-- Top Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <span 
                            class="text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-xs"
                            :class="popular === '1' || badge ? 'bg-[#0A1E5E] text-white' : 'bg-blue-100 text-blue-800'"
                            x-text="badge ? badge : (popular === '1' ? 'Paling Populer' : 'Paket Standar')"
                        ></span>

                        <!-- Discount Badge -->
                        <template x-if="calculatedPercent > 0 || originalPrice">
                            <span class="bg-rose-500 text-white text-[10px] font-black uppercase tracking-wide px-2.5 py-1 rounded-full shadow-xs">
                                <span x-text="calculatedPercent > 0 ? ('Diskon ' + calculatedPercent + '%') : 'PROMO'"></span>
                            </span>
                        </template>
                    </div>

                    <!-- Package Name & Description -->
                    <h3 class="text-2xl font-black mb-1.5" x-text="name ? name : 'Nama Paket'"></h3>
                    <p class="text-xs font-medium leading-relaxed opacity-80 min-h-[36px]" x-text="description ? description : 'Deskripsi ringkas paket...'"></p>

                    <!-- Price Section (Original Strikethrough & Active Promo Price) -->
                    <div class="mt-5 pt-4 border-t border-black/10">
                        <template x-if="originalPrice">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-400 line-through">
                                    Rp <span x-text="originalPrice"></span>
                                </span>
                                <span class="text-[10px] font-extrabold text-rose-600 bg-rose-50 border border-rose-200 px-1.5 py-0.2 rounded" x-show="calculatedPercent > 0" x-text="'-' + calculatedPercent + '%'"></span>
                            </div>
                        </template>

                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight leading-none">
                                <span class="text-base font-bold mr-0.5">Rp</span>
                                <span x-text="price ? price : '0'"></span>
                            </span>
                            <span class="text-xs font-semibold opacity-70">/proyek</span>
                        </div>

                        <!-- Savings Callout -->
                        <template x-if="savingsNum > 0">
                            <p class="text-[11px] font-bold text-emerald-700 bg-emerald-100/70 border border-emerald-300/80 rounded-lg px-2.5 py-1 mt-2 inline-block">
                                🎉 Pelanggan Hemat: Rp <span x-text="formatNumber(savingsNum)"></span>
                            </p>
                        </template>
                    </div>

                    <!-- Action Button Preview -->
                    <div class="mt-6">
                        <div 
                            class="w-full py-3 rounded-full font-bold text-xs flex items-center justify-center gap-2 shadow-md"
                            :class="popular === '1' || badge ? 'bg-[#0A1E5E] text-white' : 'bg-white border-2 border-slate-200 text-slate-900'"
                        >
                            <span>Pilih Paket Ini</span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
