@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')
@section('page_title', 'Tulis Artikel Blog Baru')

@section('content')
    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Artikel *</label>
                <input 
                    type="text" 
                    name="title" 
                    required 
                    placeholder="Contoh: 7 Tips Membangun Website Konversi Tinggi di 2026" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Artikel *</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                        <option value="Technology">Technology</option>
                        <option value="Web Development">Web Development</option>
                        <option value="E-Commerce">E-Commerce</option>
                        <option value="AI & Automation">AI & Automation</option>
                        <option value="Business & Marketing">Business & Marketing</option>
                        <option value="UI/UX Design">UI/UX Design</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Penulis / Author</label>
                    <input 
                        type="text" 
                        name="author" 
                        value="JuangDev Team"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <!-- Upload Cover Image (File Input) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Cover Image *</label>
                <div class="space-y-3">
                    <input 
                        type="file" 
                        name="image_file" 
                        accept="image/*"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-slate-50/50"
                    >
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">Atau masukkan URL gambar langsung:</span>
                    </div>
                    <input 
                        type="text" 
                        name="image_url" 
                        placeholder="https://images.unsplash.com/... atau /uploads/artikel.png" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ringkasan / Excerpt</label>
                <textarea 
                    name="excerpt" 
                    rows="2" 
                    placeholder="Ringkasan 1-2 kalimat untuk thumbnail dan meta description..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                ></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konten Artikel (Markdown / HTML) *</label>
                <textarea 
                    name="content" 
                    rows="10" 
                    required 
                    placeholder="Tulis artikel lengkap di sini..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Publikasi</label>
                    <select name="is_published" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="1">Published</option>
                        <option value="0">Draft</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Publikasikan Artikel
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
