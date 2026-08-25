@extends('layouts.admin')

@section('title', 'Edit Portfolio')
@section('page_title', 'Edit Portfolio: ' . $portfolio->title)

@section('content')
    <div class="max-w-4xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

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
                            value="{{ old('title', $portfolio->title) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Proyek *</label>
                            <select 
                                name="category" 
                                required 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer"
                            >
                                @php
                                    $catOptions = ['Aplikasi Web', 'Sistem Informasi', 'E-Commerce', 'Custom Web App', 'Landing Page', 'Company Profile'];
                                    $currentCat = old('category', $portfolio->category);
                                @endphp
                                @foreach($catOptions as $cat)
                                    <option value="{{ $cat }}" {{ strtolower($currentCat) === strtolower($cat) ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Paket</label>
                            <select 
                                name="package_tier" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer"
                            >
                                @php
                                    $currentTier = old('package_tier', $portfolio->package_tier);
                                @endphp
                                <option value="">-- Tanpa Paket --</option>
                                <option value="Basic" {{ $currentTier === 'Basic' ? 'selected' : '' }}>Basic</option>
                                <option value="Rekomendasi" {{ $currentTier === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                                <option value="Premium" {{ $currentTier === 'Premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Live Demo / Website URL</label>
                            <input 
                                type="url" 
                                name="live_url" 
                                value="{{ old('live_url', $portfolio->live_url) }}"
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
                                value="{{ old('client', $portfolio->client) }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Client Industry</label>
                            <input 
                                type="text" 
                                name="client_industry" 
                                value="{{ old('client_industry', $portfolio->client_industry) }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Duration (Durasi)</label>
                            <input 
                                type="text" 
                                name="duration" 
                                value="{{ old('duration', $portfolio->duration) }}"
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
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        >{{ old('description', $portfolio->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Overview Lengkap Proyek</label>
                        <textarea 
                            name="overview" 
                            rows="4" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        >{{ old('overview', $portfolio->overview) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Key Features (Fitur Utama - 1 Fitur per Baris)</label>
                        @php
                            $kfText = is_array($portfolio->key_features) ? implode("\n", $portfolio->key_features) : ($portfolio->key_features ?? '');
                        @endphp
                        <textarea 
                            name="key_features" 
                            rows="4" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        >{{ old('key_features', $kfText) }}</textarea>
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

                @php
                    $existingGallery = is_array($portfolio->gallery) ? $portfolio->gallery : [];
                @endphp

                <div class="space-y-4">
                    @for($i = 0; $i < 5; $i++)
                        @php
                            $gTitle = $existingGallery[$i]['title'] ?? '';
                            $gImg = $existingGallery[$i]['image_url'] ?? '';
                            $isPinned = !empty($existingGallery[$i]['is_pinned']) || ($portfolio->image_url === $gImg && $gImg != '');
                            if ($i === 0 && !$isPinned && empty(array_filter($existingGallery, fn($item) => !empty($item['is_pinned'])))) {
                                $isPinned = true;
                            }
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Gambar #{{ $i + 1 }}</span>
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1 rounded-full border border-slate-300 text-xs font-semibold text-slate-700 hover:border-[#2563EB]">
                                    <input 
                                        type="radio" 
                                        name="pinned_image_index" 
                                        value="{{ $i }}" 
                                        {{ $isPinned ? 'checked' : '' }}
                                        class="text-[#2563EB] focus:ring-0 cursor-pointer"
                                    >
                                    <span>📌 Pin Layar Utama (Hero)</span>
                                </label>
                            </div>

                            @if($gImg)
                                <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-slate-200">
                                    <img src="{{ $gImg }}" alt="Preview {{ $i }}" class="w-16 h-12 object-cover rounded-md border border-slate-200">
                                    <div class="text-xs">
                                        <span class="font-semibold text-slate-700 block">Gambar Tersimpan:</span>
                                        <span class="text-slate-500 font-mono text-[11px] truncate max-w-xs block">{{ $gImg }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Judul / Caption Gambar</label>
                                    <input 
                                        type="text" 
                                        name="gallery_titles[{{ $i }}]" 
                                        value="{{ old("gallery_titles.{$i}", $gTitle) }}"
                                        placeholder="Contoh: Property & Room Management" 
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Ganti File Gambar</label>
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
                                    value="{{ old("gallery_urls.{$i}", $gImg) }}"
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
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih &amp; Tambah Teknologi (Tech Stack)</label>
                @php
                    $techOptions = ['Laravel', 'Blade', 'Tailwind CSS', 'Alpine.js', 'MySQL', 'MongoDB', 'Bootstrap', 'Vue.js', 'React', 'Next.js', 'Node.js', 'PostgreSQL', 'Docker', 'WebSockets', 'Midtrans', 'Python', 'Redis', 'AWS'];
                    $currentTechs = is_array($portfolio->technologies) ? $portfolio->technologies : explode(',', $portfolio->technologies ?? '');
                    $currentTechs = array_values(array_filter(array_map('trim', $currentTechs)));
                    
                    // Identify any custom techs not in the default checklist
                    $customTechsList = array_diff($currentTechs, $techOptions);
                    $customTechsValue = implode(', ', $customTechsList);
                @endphp
                
                <!-- Preset Tech Stack Checkboxes -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 mb-3">
                    @foreach($techOptions as $tech)
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 hover:text-slate-900 select-none">
                            <input 
                                type="checkbox" 
                                name="technologies[]" 
                                value="{{ $tech }}" 
                                {{ in_array($tech, $currentTechs) ? 'checked' : '' }}
                                class="rounded text-[#2563EB] focus:ring-0"
                            >
                            <span>{{ $tech }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Custom Typed Technologies -->
                <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-200">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ketik Teknologi Kustom / Tambahan (Pisahkan dengan tanda koma)
                    </label>
                    <input 
                        type="text" 
                        name="custom_technologies" 
                        value="{{ old('custom_technologies', $customTechsValue) }}"
                        placeholder="Contoh: TypeScript, Flutter, Supabase, Inertia.js, Express.js" 
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white"
                    >
                    <p class="text-[11px] text-slate-500 mt-1 font-normal">
                        Teknologi yang diketik di sini akan otomatis digabungkan dengan pilihan centang di atas.
                    </p>
                </div>
            </div>

            <!-- Pengaturan Status & Boilerplate -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="sliders" class="w-4 h-4 text-[#2563EB]"></i>
                    <span>Opsi &amp; Status Portofolio</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Checkbox Boilerplate -->
                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-[#2563EB]/60 cursor-pointer transition-colors shadow-2xs">
                        <input 
                            type="checkbox" 
                            name="is_boilerplate" 
                            value="1" 
                            {{ old('is_boilerplate', $portfolio->is_boilerplate) ? 'checked' : '' }}
                            class="mt-1 rounded text-[#2563EB] focus:ring-0 w-4 h-4"
                        >
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Tandai sebagai Boilerplate</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5 leading-snug">Menampilkan badge tag khusus <strong class="text-blue-700 font-semibold">"Boilerplate"</strong> pada kartu portofolio di halaman utama dan katalog.</span>
                        </div>
                    </label>

                    <!-- Checkbox Featured -->
                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-[#2563EB]/60 cursor-pointer transition-colors shadow-2xs">
                        <input 
                            type="checkbox" 
                            name="featured" 
                            value="1" 
                            {{ old('featured', $portfolio->featured) ? 'checked' : '' }}
                            class="mt-1 rounded text-[#2563EB] focus:ring-0 w-4 h-4"
                        >
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Proyek Unggulan (Featured)</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5 leading-snug">Prioritaskan tampil pada bagian depan etalase proyek.</span>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jumlah Terjual (Khusus Boilerplate)</label>
                        <div class="relative">
                            <input 
                                type="number" 
                                name="sold_count" 
                                min="0"
                                value="{{ old('sold_count', $portfolio->sold_count ?? 0) }}" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#2563EB] bg-white"
                                placeholder="0"
                            >
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">x Terjual</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 font-normal">
                            Menampilkan label icon clean "Terjual Xx" pada kartu portofolio.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan Tampil (Display Order)</label>
                        <input 
                            type="number" 
                            name="display_order" 
                            value="{{ old('display_order', $portfolio->display_order ?? 0) }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#2563EB] bg-white"
                        >
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Update Portfolio
                </button>
                <a href="{{ route('admin.portfolios.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
