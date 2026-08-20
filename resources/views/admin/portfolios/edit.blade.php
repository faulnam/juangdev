@extends('layouts.admin')

@section('title', 'Edit Portfolio')
@section('page_title', 'Edit Portfolio: ' . $portfolio->title)

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Proyek *</label>
                    <select 
                        name="category" 
                        required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer"
                    >
                        @php
                            $catOptions = ['Sistem Informasi', 'E-Commerce', 'Custom Web App', 'Landing Page', 'Company Profile'];
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
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Live Demo / Website URL</label>
                    <input 
                        type="url" 
                        name="live_url" 
                        value="{{ old('live_url', $portfolio->live_url) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Proyek *</label>
                <textarea 
                    name="description" 
                    rows="3" 
                    required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ old('description', $portfolio->description) }}</textarea>
            </div>

            <!-- Upload Gambar Portfolio (File Input) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Gambar / Mockup Proyek</label>
                @if($portfolio->image_url)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $portfolio->image_url }}" alt="Preview" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-xs">
                        <span class="text-xs text-slate-500 font-medium">Gambar saat ini</span>
                    </div>
                @endif
                <div class="space-y-3">
                    <input 
                        type="file" 
                        name="image_file" 
                        accept="image/*"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-slate-50/50"
                    >
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">Atau ubah URL gambar langsung:</span>
                    </div>
                    <input 
                        type="text" 
                        name="image_url" 
                        value="{{ old('image_url', $portfolio->image_url) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <!-- Tech Stack Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Teknologi (Tech Stack)</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                    @php
                        $techOptions = ['Laravel', 'Blade', 'Tailwind CSS', 'Alpine.js', 'MySQL', 'Vue.js', 'React', 'Next.js', 'Node.js', 'PostgreSQL', 'Docker', 'WebSockets', 'Midtrans', 'Python', 'Stripe', 'Redis', 'AWS'];
                        $currentTechs = is_array($portfolio->technologies) ? $portfolio->technologies : explode(',', $portfolio->technologies ?? '');
                        $currentTechs = array_map('trim', $currentTechs);
                    @endphp
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
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
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
