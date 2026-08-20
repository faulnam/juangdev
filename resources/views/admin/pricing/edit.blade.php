@extends('layouts.admin')

@section('title', 'Edit Paket Harga')
@section('page_title', 'Edit Paket: ' . $pricing->name)

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.pricing.update', $pricing->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Paket *</label>
                <input 
                    type="text" 
                    name="name" 
                    required 
                    value="{{ old('name', $pricing->name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Layanan *</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                        <option value="landing-page" {{ $pricing->category === 'landing-page' ? 'selected' : '' }}>Landing Page</option>
                        <option value="company-profile" {{ $pricing->category === 'company-profile' ? 'selected' : '' }}>Company Profile</option>
                        <option value="ecommerce" {{ $pricing->category === 'ecommerce' ? 'selected' : '' }}>E-Commerce</option>
                        <option value="sistem-informasi" {{ $pricing->category === 'sistem-informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="custom-app" {{ $pricing->category === 'custom-app' ? 'selected' : '' }}>Custom Web App</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga (Tampilan Teks) *</label>
                    <input 
                        type="text" 
                        name="price" 
                        required 
                        value="{{ old('price', $pricing->price) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Ringkas</label>
                <textarea 
                    name="description" 
                    rows="2" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ old('description', $pricing->description) }}</textarea>
            </div>

            <!-- Features Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Fitur Paket</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tandai Paling Populer?</label>
                    <select name="popular" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="0" {{ !$pricing->popular ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ $pricing->popular ? 'selected' : '' }}>Ya (Populer Badge)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Custom Badge Text (Opsional)</label>
                    <input 
                        type="text" 
                        name="badge" 
                        value="{{ old('badge', $pricing->badge) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Update Paket
                </button>
                <a href="{{ route('admin.pricing.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
