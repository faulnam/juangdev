@extends('layouts.admin')

@section('title', 'Tambah Portfolio Baru')
@section('page_title', 'Tambah Portfolio Baru')

@section('content')
    <div class="max-w-4xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Informasi Dasar -->
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-[#2563EB]"></i>
                    Informasi Dasar Proyek
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Proyek *</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            placeholder="Contoh: Property Management App / Healthcare ERP" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Proyek *</label>
                            <select 
                                name="category" 
                                required 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer"
                            >
                                <option value="">Pilih Kategori...</option>
                                <option value="Aplikasi Web">Aplikasi Web</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Custom Web App">Custom Web App</option>
                                <option value="Landing Page">Landing Page</option>
                                <option value="Company Profile">Company Profile</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Live Demo / Website URL</label>
                            <input 
                                type="url" 
                                name="live_url" 
                                placeholder="https://example.com" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Klien / Perusahaan</label>
                            <input 
                                type="text" 
                                name="client" 
                                placeholder="Contoh: PT Medika Nusantara" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Client Industry</label>
                            <input 
                                type="text" 
                                name="client_industry" 
                                placeholder="Contoh: Property, Landlord, and Rental" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Duration (Durasi)</label>
                            <input 
                                type="text" 
                                name="duration" 
                                placeholder="Contoh: Januari 2024 - Juni 2024 (6 Bulan)" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat *</label>
                        <textarea 
                            name="description" 
                            rows="2" 
                            required 
                            placeholder="Ringkasan singkat proyek yang tampil pada kartu portofolio..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Overview Lengkap Proyek</label>
                        <textarea 
                            name="overview" 
                            rows="4" 
                            placeholder="Jelaskan secara mendalam mengenai latar belakang, tantangan, dan solusi yang diimplementasikan..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Key Features (Fitur Utama - 1 Fitur per Baris)</label>
                        <textarea 
                            name="key_features" 
                            rows="4" 
                            placeholder="Property & Room Management&#10;Member & Tenant Management&#10;Payment & Transaction System&#10;Reporting & Data Export"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Upload 5 Gambar & Pin Cover Image -->
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-[#2563EB]"></i>
                    Galeri Tangkapan Layar (Upload Maksimal 5 Gambar)
                </h3>
                <p class="text-xs text-slate-500 mb-4 font-normal">
                    Pilih salah satu gambar sebagai <strong class="text-slate-700">📌 Layar Utama (Hero Banner)</strong> yang tampil di bagian atas halaman detail.
                </p>

                <div class="space-y-4">
                    @for($i = 0; $i < 5; $i++)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Gambar #{{ $i + 1 }}</span>
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1 rounded-full border border-slate-300 text-xs font-semibold text-slate-700 hover:border-[#2563EB]">
                                    <input 
                                        type="radio" 
                                        name="pinned_image_index" 
                                        value="{{ $i }}" 
                                        {{ $i === 0 ? 'checked' : '' }}
                                        class="text-[#2563EB] focus:ring-0 cursor-pointer"
                                    >
                                    <span>📌 Pin Layar Utama (Hero)</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Judul / Caption Gambar</label>
                                    <input 
                                        type="text" 
                                        name="gallery_titles[{{ $i }}]" 
                                        placeholder="Contoh: Property & Room Management" 
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Upload File Gambar</label>
                                    <input 
                                        type="file" 
                                        name="gallery_files[{{ $i }}]" 
                                        accept="image/*"
                                        class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-white"
                                    >
                                </div>
                            </div>

                            <div>
                                <input 
                                    type="text" 
                                    name="gallery_urls[{{ $i }}]" 
                                    placeholder="Atau masukkan URL gambar: /uploads/... atau https://..." 
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white"
                                >
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Tech Stack Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Teknologi (Tech Stack)</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                    @php
                        $techOptions = ['Laravel', 'Blade', 'Tailwind CSS', 'Alpine.js', 'MySQL', 'MongoDB', 'Bootstrap', 'Vue.js', 'React', 'Next.js', 'Node.js', 'PostgreSQL', 'Docker', 'WebSockets', 'Midtrans', 'Python', 'Redis', 'AWS'];
                    @endphp
                    @foreach($techOptions as $tech)
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 hover:text-slate-900 select-none">
                            <input type="checkbox" name="technologies[]" value="{{ $tech }}" class="rounded text-[#2563EB] focus:ring-0">
                            <span>{{ $tech }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Portfolio
                </button>
                <a href="{{ route('admin.portfolios.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
