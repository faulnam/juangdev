@extends('layouts.admin')

@section('title', 'Edit Layanan')
@section('page_title', 'Edit Layanan: ' . $service->name)

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Layanan *</label>
                <input 
                    type="text" 
                    name="name" 
                    required 
                    value="{{ old('name', $service->name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tagline / Sub-judul Layanan</label>
                <input 
                    type="text" 
                    name="tagline" 
                    value="{{ old('tagline', $service->tagline) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Layanan *</label>
                <textarea 
                    name="description" 
                    rows="3" 
                    required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Base Price (Angka Estimator) *</label>
                    <input 
                        type="number" 
                        name="base_price" 
                        required 
                        value="{{ old('base_price', $service->base_price) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Starting Price (Tampilan Teks) *</label>
                    <input 
                        type="text" 
                        name="starting_price" 
                        required 
                        value="{{ old('starting_price', $service->starting_price) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <!-- Features Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fitur-Fitur Layanan</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                    @php
                        $featureOptions = [
                            'Desain Responsif', 'SEO Basic', 'Mobile Friendly', 'Call to Action Optimal',
                            'Setup Domain & Hosting', 'Hingga 5 Halaman', 'Galeri Portfolio',
                            'Form Kontak Interaktif', 'Integrasi Google Maps', 'Katalog Produk',
                            'Keranjang Belanja', 'Integrasi WA Checkout', 'Dashboard Penjualan',
                            'Manajemen Data Terpadu', 'Dashboard Analitik Visual', 'Export Laporan PDF/Excel',
                            'Hak Akses Multi-Level', 'Cloud Backup', 'Desain UI/UX Custom Eksklusif',
                            'API Integration & Webhook', 'Garansi & Maintenance'
                        ];
                        $currentFeats = is_array($service->features) ? $service->features : explode(',', $service->features ?? '');
                        $currentFeats = array_map('trim', $currentFeats);
                    @endphp
                    @foreach($featureOptions as $feat)
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
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Icon (Lucide Icon)</label>
                    <select name="icon" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="globe" {{ old('icon', $service->icon) === 'globe' ? 'selected' : '' }}>Globe (Landing Page)</option>
                        <option value="monitor" {{ old('icon', $service->icon) === 'monitor' ? 'selected' : '' }}>Monitor (Company Profile)</option>
                        <option value="shopping-bag" {{ old('icon', $service->icon) === 'shopping-bag' ? 'selected' : '' }}>Shopping Bag (E-Commerce)</option>
                        <option value="bot" {{ old('icon', $service->icon) === 'bot' ? 'selected' : '' }}>Bot / Database (Sistem Informasi)</option>
                        <option value="palette" {{ old('icon', $service->icon) === 'palette' ? 'selected' : '' }}>Palette (Custom App)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Layanan</label>
                    <select name="is_active" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="1" {{ $service->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$service->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Update Layanan
                </button>
                <a href="{{ route('admin.services.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
