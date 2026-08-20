@extends('layouts.admin')

@section('title', 'Tambah Paket Harga')
@section('page_title', 'Tambah Paket Harga Baru')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.pricing.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Paket *</label>
                <input 
                    type="text" 
                    name="name" 
                    required 
                    placeholder="Contoh: Starter, Pro, Enterprise" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Layanan *</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                        <option value="landing-page">Landing Page</option>
                        <option value="company-profile">Company Profile</option>
                        <option value="ecommerce">E-Commerce</option>
                        <option value="sistem-informasi">Sistem Informasi</option>
                        <option value="custom-app">Custom Web App</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga (Tampilan Teks) *</label>
                    <input 
                        type="text" 
                        name="price" 
                        required 
                        placeholder="Contoh: 99k atau 1.499k" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Ringkas</label>
                <textarea 
                    name="description" 
                    rows="2" 
                    placeholder="Cocok untuk personal, UMKM, validasi ide, dll..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                ></textarea>
            </div>

            <!-- Features Selection (Clean Checkboxes / Tag Badges, No "pisahkan dengan koma") -->
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
                    @endphp
                    @foreach($planFeatures as $feat)
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 hover:text-slate-900 select-none">
                            <input type="checkbox" name="features[]" value="{{ $feat }}" class="rounded text-[#2563EB] focus:ring-0">
                            <span>{{ $feat }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tandai Paling Populer?</label>
                    <select name="popular" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="0">Tidak</option>
                        <option value="1">Ya (Populer Badge)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Custom Badge Text (Opsional)</label>
                    <input 
                        type="text" 
                        name="badge" 
                        placeholder="Contoh: BEST SELLER, REKOMENDASI" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Paket
                </button>
                <a href="{{ route('admin.pricing.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
