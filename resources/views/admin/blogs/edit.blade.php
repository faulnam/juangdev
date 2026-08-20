@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('page_title', 'Edit Artikel: ' . $blog->title)

@section('content')
    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Artikel *</label>
                <input 
                    type="text" 
                    name="title" 
                    required 
                    value="{{ old('title', $blog->title) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Artikel *</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                        @php
                            $blogCats = ['Technology', 'Web Development', 'E-Commerce', 'AI & Automation', 'Business & Marketing', 'UI/UX Design'];
                            $currentBlogCat = old('category', $blog->category);
                        @endphp
                        @foreach($blogCats as $bcat)
                            <option value="{{ $bcat }}" {{ strtolower($currentBlogCat) === strtolower($bcat) ? 'selected' : '' }}>
                                {{ $bcat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Penulis / Author</label>
                    <input 
                        type="text" 
                        name="author" 
                        value="{{ old('author', $blog->author) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <!-- Upload Cover Image (File Input) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Cover Image</label>
                @if($blog->image_url)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $blog->image_url }}" alt="Preview" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-xs">
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
                        value="{{ old('image_url', $blog->image_url) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ringkasan / Excerpt</label>
                <textarea 
                    name="excerpt" 
                    rows="2" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konten Artikel (Markdown / HTML) *</label>
                <textarea 
                    name="content" 
                    rows="10" 
                    required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >{{ old('content', $blog->content) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Publikasi</label>
                    <select name="is_published" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]">
                        <option value="1" {{ $blog->is_published ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ !$blog->is_published ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Update Artikel
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
