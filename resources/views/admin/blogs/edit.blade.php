@extends('layouts.admin')

@section('title', 'Edit Insight / Artikel')
@section('page_title', 'Edit Insight: ' . $blog->title)

@push('styles')
<style>
    /* Styling khusus area editor teks agar tampilan bullet, number, heading, quote persis seperti artikel */
    .blog-editor-content {
        outline: none;
        min-height: 380px;
    }
    .blog-editor-content p {
        margin-bottom: 0.85rem;
        line-height: 1.75;
        color: #334155;
    }
    .blog-editor-content h2 {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        margin-top: 1.5rem !important;
        margin-bottom: 0.6rem !important;
        line-height: 1.3 !important;
    }
    .blog-editor-content h3 {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-top: 1.25rem !important;
        margin-bottom: 0.5rem !important;
        line-height: 1.35 !important;
    }
    .blog-editor-content h4 {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-top: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    .blog-editor-content ul {
        list-style-type: disc !important;
        padding-left: 1.75rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 1rem !important;
    }
    .blog-editor-content ol {
        list-style-type: decimal !important;
        padding-left: 1.75rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 1rem !important;
    }
    .blog-editor-content li {
        display: list-item !important;
        margin-bottom: 0.35rem !important;
        line-height: 1.65 !important;
        color: #334155 !important;
    }
    .blog-editor-content blockquote {
        border-left: 4px solid #2563EB !important;
        background: #f8fafc !important;
        padding: 0.85rem 1.15rem !important;
        margin: 1rem 0 !important;
        border-radius: 0 0.5rem 0.5rem 0 !important;
        font-style: italic !important;
        color: #475569 !important;
    }
    .blog-editor-content a {
        color: #2563EB !important;
        font-weight: 600 !important;
        text-decoration: underline !important;
        text-underline-offset: 3px !important;
    }
    .blog-editor-content code {
        background: #f1f5f9;
        color: #0f172a;
        padding: 0.15rem 0.4rem;
        border-radius: 0.25rem;
        font-family: monospace;
        font-size: 0.875em;
    }
    .blog-editor-content pre {
        background: #0f172a;
        color: #f8fafc;
        padding: 0.85rem 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1rem 0;
        font-family: monospace;
        font-size: 0.875rem;
    }
    .blog-editor-content hr {
        border: 0;
        border-top: 1px solid #e2e8f0;
        margin: 1.5rem 0;
    }
</style>
@endpush

@php
    $initialStatus = 'published';
    if (!$blog->is_published) {
        $initialStatus = 'draft';
    } elseif ($blog->published_at && $blog->published_at->isFuture()) {
        $initialStatus = 'scheduled';
    }
    
    // Check if content has HTML tags; if not, wrap paragraphs for initial visual editor display
    $initialContent = $blog->content ?? '';
    if (strip_tags($initialContent) === $initialContent && !empty($initialContent)) {
        $initialContent = '<p>' . nl2br(e($initialContent)) . '</p>';
    }
@endphp

@section('content')
<div x-data="blogEditForm()" class="space-y-6">
    <!-- Header Title Bar -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <span class="text-[#2563EB]">✏️</span> Edit Insight
        </h2>
        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" id="blogEditForm">
        @csrf
        @method('PUT')

        <!-- 2-Column Main Form Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: Main Form Inputs (Title, Excerpt, Content Editor) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Judul Insight / Artikel -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Judul Insight *</label>
                    <input 
                        type="text" 
                        name="title" 
                        required 
                        value="{{ old('title', $blog->title) }}"
                        x-model="title"
                        @input="updateTitle($event.target.value)"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <!-- Excerpt / Ringkasan -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Excerpt / Ringkasan (Opsional)</label>
                    <textarea 
                        name="excerpt" 
                        rows="3" 
                        x-model="excerpt"
                        @input="updateExcerpt($event.target.value)"
                        placeholder="Ringkasan singkat 1-2 kalimat untuk deskripsi kartu dan preview SEO..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                    >{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <!-- Konten Utama (Rich Text Editor with Custom Actions & Formatting Toolbar) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 pb-3 border-b border-slate-100">
                        <label class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span>Konten Utama *</span>
                            <span class="text-[11px] font-normal text-slate-400 normal-case">(Tulis &amp; rapikan artikel dengan tools)</span>
                        </label>
                        
                        <!-- Insertion Quick Action Buttons -->
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @mousedown.prevent="insertLinkProduct()"
                                class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5 border border-slate-200 shadow-xs cursor-pointer"
                            >
                                <span>📦 Sisipkan Link Produk</span>
                            </button>

                            <button 
                                type="button" 
                                @mousedown.prevent="insertLinkArticle()"
                                class="px-3 py-1.5 rounded-lg bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-xs font-bold transition-colors flex items-center gap-1.5 border border-cyan-200 shadow-xs cursor-pointer"
                            >
                                <span>📝 Sisipkan Link Artikel</span>
                            </button>

                            <button 
                                type="button" 
                                @click="toggleSourceMode()"
                                :class="sourceMode ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-slate-50 text-slate-600 border-slate-200'"
                                class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center gap-1 border shadow-xs cursor-pointer"
                                title="Lihat / Edit Kode HTML Langsung"
                            >
                                <i data-lucide="code" class="w-3.5 h-3.5"></i>
                                <span x-text="sourceMode ? 'Visual Mode' : 'HTML Mode'">HTML</span>
                            </button>
                        </div>
                    </div>

                    <!-- WYSIWYG Formatting Toolbar (Hidden in HTML Source Mode) -->
                    <div x-show="!sourceMode" class="flex flex-wrap items-center gap-1 bg-slate-50 p-2 rounded-t-xl border border-slate-200 border-b-0 text-slate-700 select-none">
                        <button type="button" @mousedown.prevent="formatDoc('undo')" class="p-2 hover:bg-slate-200 rounded text-xs font-bold" title="Undo (Ctrl+Z)"><i data-lucide="undo" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('redo')" class="p-2 hover:bg-slate-200 rounded text-xs font-bold" title="Redo (Ctrl+Y)"><i data-lucide="redo" class="w-4 h-4"></i></button>
                        <div class="w-px h-5 bg-slate-300 mx-1"></div>

                        <!-- Header Format Selector -->
                        <select @change="insertHeader($event.target.value); $event.target.value = ''" class="px-2.5 py-1 bg-white border border-slate-200 rounded text-xs font-semibold cursor-pointer">
                            <option value="">Gaya Teks...</option>
                            <option value="P">Paragraph (Normal)</option>
                            <option value="H2">Heading 2 (Subjudul Utama)</option>
                            <option value="H3">Heading 3 (Subjudul Kecil)</option>
                            <option value="H4">Heading 4</option>
                            <option value="BLOCKQUOTE">Kutipan / Blockquote</option>
                        </select>

                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        
                        <!-- Text Styles -->
                        <button type="button" @mousedown.prevent="formatDoc('bold')" class="p-2 hover:bg-slate-200 rounded font-black text-xs w-8 text-center" title="Tebal (Bold / Ctrl+B)">B</button>
                        <button type="button" @mousedown.prevent="formatDoc('italic')" class="p-2 hover:bg-slate-200 rounded italic font-serif text-xs w-8 text-center" title="Miring (Italic / Ctrl+I)">I</button>
                        <button type="button" @mousedown.prevent="formatDoc('underline')" class="p-2 hover:bg-slate-200 rounded underline text-xs w-8 text-center" title="Garis Bawah (Underline / Ctrl+U)">U</button>
                        <button type="button" @mousedown.prevent="formatDoc('strikeThrough')" class="p-2 hover:bg-slate-200 rounded line-through text-xs w-8 text-center" title="Coret (Strikethrough)">S</button>
                        
                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        
                        <!-- Alignments -->
                        <button type="button" @mousedown.prevent="formatDoc('justifyLeft')" class="p-2 hover:bg-slate-200 rounded" title="Rata Kiri"><i data-lucide="align-left" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('justifyCenter')" class="p-2 hover:bg-slate-200 rounded" title="Rata Tengah"><i data-lucide="align-center" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('justifyRight')" class="p-2 hover:bg-slate-200 rounded" title="Rata Kanan"><i data-lucide="align-right" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('justifyFull')" class="p-2 hover:bg-slate-200 rounded" title="Rata Kanan Kiri (Justify)"><i data-lucide="align-justify" class="w-4 h-4"></i></button>

                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        
                        <!-- List Tools (CRITICAL) -->
                        <button type="button" @mousedown.prevent="formatDoc('insertUnorderedList')" class="p-2 hover:bg-blue-100 hover:text-[#2563EB] rounded text-slate-700 transition-colors cursor-pointer" title="Bullet List (Daftar Titik)">
                            <i data-lucide="list" class="w-4 h-4"></i>
                        </button>
                        <button type="button" @mousedown.prevent="formatDoc('insertOrderedList')" class="p-2 hover:bg-blue-100 hover:text-[#2563EB] rounded text-slate-700 transition-colors cursor-pointer" title="Numbered List (Daftar Nomor)">
                            <i data-lucide="list-ordered" class="w-4 h-4"></i>
                        </button>

                        <div class="w-px h-5 bg-slate-300 mx-1"></div>

                        <!-- Link & Formatting Tools -->
                        <button type="button" @mousedown.prevent="insertCustomLink()" class="p-2 hover:bg-slate-200 rounded cursor-pointer" title="Sisipkan Tautan Web"><i data-lucide="link" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('unlink')" class="p-2 hover:bg-slate-200 rounded cursor-pointer" title="Hapus Tautan"><i data-lucide="link-2-off" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('insertHorizontalRule')" class="p-2 hover:bg-slate-200 rounded cursor-pointer" title="Garis Pembatas (HR)"><i data-lucide="minus" class="w-4 h-4"></i></button>
                        <button type="button" @mousedown.prevent="formatDoc('removeFormat')" class="p-2 hover:bg-red-100 hover:text-red-600 rounded text-slate-700 transition-colors cursor-pointer" title="Hapus Pemformatan"><i data-lucide="eraser" class="w-4 h-4"></i></button>
                    </div>

                    <!-- Hidden Input for Form Submission -->
                    <input type="hidden" name="content" :value="content">

                    <!-- Editable Visual Content Area -->
                    <div 
                        x-show="!sourceMode"
                        x-ref="wysiwygEditor"
                        contenteditable="true"
                        @input="syncContentFromEditor()"
                        @blur="syncContentFromEditor()"
                        @keyup="syncContentFromEditor()"
                        class="blog-editor-content w-full min-h-[380px] p-5 bg-white border border-slate-200 rounded-b-xl focus:outline-none text-slate-800 text-sm leading-relaxed overflow-y-auto"
                        style="max-height: 650px;"
                    ></div>

                    <!-- HTML Source Mode Area -->
                    <div x-show="sourceMode" x-cloak>
                        <textarea 
                            x-model="content"
                            @input="syncContentFromTextarea()"
                            class="w-full min-h-[380px] p-4 bg-slate-900 text-slate-100 font-mono text-xs leading-relaxed rounded-b-xl border border-slate-800 focus:outline-none focus:border-[#2563EB] resize-y"
                            placeholder="Tulis atau tempel kode HTML artikel di sini..."
                        ></textarea>
                    </div>

                    <!-- Editor Status Footer Bar -->
                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                        <div class="flex items-center gap-3">
                            <span class="bg-blue-50 text-[#2563EB] font-bold px-2 py-0.5 rounded text-[10px] uppercase">
                                <span x-text="sourceMode ? 'Mode Kode HTML' : 'Visual Editor'">Visual Editor</span>
                            </span>
                            <span class="hidden sm:inline">Gunakan toolbar untuk format list &amp; subjudul</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span x-text="wordCount + ' kata'">0 kata</span>
                            <span x-text="charCount + ' karakter'">0 karakter</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sidebar Settings (Penulis, Status, Cover, Alt, Slug, SEO) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                    
                    <!-- Penulis -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Penulis</label>
                        <input 
                            type="text" 
                            name="author" 
                            value="{{ old('author', $blog->author) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Status Publikasi *</label>
                        <select 
                            name="status" 
                            x-model="status" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer"
                        >
                            <option value="published">Published (Rilis Sekarang)</option>
                            <option value="draft">Draft (Simpan Dulu)</option>
                            <option value="scheduled">Scheduled (Jadwalkan Rilis Otomatis)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1 font-normal" x-show="status === 'published'">
                            ✅ Artikel tampil di website utama dan halaman /blog.
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1 font-normal" x-show="status === 'draft'">
                            📁 Artikel berstatus draft dan belum tampil di website publik.
                        </p>
                    </div>

                    <!-- Scheduled Date & Time Picker (HANYA MUNCUL KETIKA STATUS = SCHEDULED) -->
                    <div x-show="status === 'scheduled'" x-cloak class="p-4 rounded-xl bg-amber-50/80 border border-amber-200/80 space-y-2">
                        <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider">
                            Jadwal Rilis Otomatis *
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            x-model="publishedAt"
                            :required="status === 'scheduled'"
                            class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white"
                        >
                        <p class="text-[11px] text-amber-700 font-semibold flex items-center gap-1.5 leading-relaxed">
                            <i data-lucide="calendar-clock" class="w-3.5 h-3.5 shrink-0"></i>
                            <span>Artikel akan otomatis terbit dan tampil di website publik pada tanggal &amp; jam yang ditentukan di atas.</span>
                        </p>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                            @php
                                $blogCats = ['Technology', 'Web Development', 'E-Commerce', 'AI & Automation', 'Business Strategy', 'Web Design'];
                                $currentBlogCat = old('category', $blog->category);
                            @endphp
                            @foreach($blogCats as $bcat)
                                <option value="{{ $bcat }}" {{ strtolower($currentBlogCat) === strtolower($bcat) ? 'selected' : '' }}>
                                    {{ $bcat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estimasi Waktu Baca -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Waktu Baca</label>
                        <input 
                            type="text" 
                            name="read_time" 
                            value="{{ old('read_time', $blog->read_time ?? '5 min read') }}"
                            placeholder="Contoh: 4 min read"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <!-- Gambar Utama -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Gambar Cover Utama</label>
                        @if($blog->image_url)
                            <div class="mb-2 flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                <img src="{{ $blog->image_url }}" alt="Preview" class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                                <div>
                                    <span class="text-[11px] text-slate-700 font-bold block">Gambar Saat Ini</span>
                                    <span class="text-[10px] text-slate-400 truncate max-w-[180px] block">{{ $blog->image_url }}</span>
                                </div>
                            </div>
                        @endif
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-slate-50/50 mb-2"
                        >
                        <input 
                            type="text" 
                            name="image_url" 
                            value="{{ old('image_url', $blog->image_url) }}"
                            placeholder="Atau masukan URL Gambar..." 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <!-- Alt Gambar -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Alt Gambar</label>
                        <input 
                            type="text" 
                            name="alt_image" 
                            value="{{ old('alt_image', $blog->alt_image) }}"
                            placeholder="Deskripsi gambar untuk SEO..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Slug URL</label>
                        <input 
                            type="text" 
                            name="slug" 
                            value="{{ old('slug', $blog->slug) }}"
                            x-model="slug"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-slate-50/50"
                        >
                        <p class="text-[11px] text-slate-400 mt-1 font-normal">Otomatis terisi dari title, bisa diedit manual.</p>
                    </div>

                    <!-- Meta Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Meta Title</label>
                        <input 
                            type="text" 
                            name="meta_title" 
                            value="{{ old('meta_title', $blog->meta_title ?? $blog->title) }}"
                            x-model="metaTitle"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                        <p class="text-[11px] text-slate-400 mt-1 font-normal">Otomatis terisi dari title, max 60 karakter.</p>
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Meta Description</label>
                        <textarea 
                            name="meta_description" 
                            rows="3" 
                            x-model="metaDescription"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        >{{ old('meta_description', $blog->meta_description ?? $blog->excerpt) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1 font-normal">Otomatis terisi dari excerpt, max 160 karakter.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <!-- Primary Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full py-3 px-6 rounded-xl bg-[#0A1E5E] hover:bg-[#122d78] text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span x-text="status === 'scheduled' ? 'Jadwalkan Perubahan Insight' : (status === 'draft' ? 'Simpan Sebagai Draft' : 'Perbarui & Terbitkan Insight')">Update Insight</span>
                        </button>

                        <!-- Secondary Preview Button -->
                        <button 
                            type="button" 
                            @click="
                                if (!slug) { alert('Silakan isi judul artikel terlebih dahulu!'); return; }
                                window.open('/blog/' + slug, '_blank');
                            "
                            class="w-full py-3 px-6 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            <span>Preview Insight</span>
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

<script>
function blogEditForm() {
    return {
        title: @json($blog->title),
        slug: @json($blog->slug),
        excerpt: @json($blog->excerpt ?? ''),
        content: @json($initialContent),
        metaTitle: @json($blog->meta_title ?? $blog->title),
        metaDescription: @json($blog->meta_description ?? $blog->excerpt ?? ''),
        status: @json($initialStatus),
        publishedAt: @json($blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->addDay()->format('Y-m-d\TH:i')),
        sourceMode: false,
        wordCount: 0,
        charCount: 0,

        init() {
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                editor.innerHTML = this.content;
                this.updateContent(this.content);
            }
        },

        updateTitle(val) {
            this.title = val;
        },

        updateExcerpt(val) {
            this.excerpt = val;
        },

        updateContent(val) {
            this.content = val;
            let plainText = val.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
            let words = plainText.length > 0 ? plainText.split(' ').filter(w => w.length > 0) : [];
            this.wordCount = words.length;
            this.charCount = plainText.length;
        },

        formatDoc(cmd, value = null) {
            if (this.sourceMode) return;
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                editor.focus();
            }
            document.execCommand(cmd, false, value);
            this.syncContentFromEditor();
        },

        insertHeader(tag) {
            if (this.sourceMode || !tag) return;
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                editor.focus();
            }
            document.execCommand('formatBlock', false, tag);
            this.syncContentFromEditor();
        },

        insertCustomLink() {
            if (this.sourceMode) return;
            let url = prompt('Masukkan URL tautan (contoh: https://example.com atau /portfolio):');
            if (url) {
                let text = window.getSelection().toString();
                if (!text) {
                    let title = prompt('Masukkan teks tautan:', 'Klik di sini');
                    if (title) {
                        let html = '<a href="' + url + '" target="_blank">' + title + '</a>';
                        document.execCommand('insertHTML', false, html);
                    }
                } else {
                    document.execCommand('createLink', false, url);
                }
                this.syncContentFromEditor();
            }
        },

        insertLinkProduct() {
            if (this.sourceMode) return;
            let title = prompt('Masukkan nama produk / portofolio:', 'Healthcare ERP System');
            let url = prompt('Masukkan URL produk / portofolio:', '/portfolio/healthcare-erp');
            if (title && url) {
                let html = '<a href="' + url + '" class="text-[#2563EB] font-bold underline" target="_blank">' + title + '</a> ';
                document.execCommand('insertHTML', false, html);
                this.syncContentFromEditor();
            }
        },

        insertLinkArticle() {
            if (this.sourceMode) return;
            let title = prompt('Masukkan judul artikel terkait:', 'Tips SEO Website 2026');
            let url = prompt('Masukkan URL artikel:', '/blog/seo-optimization-tips-2026');
            if (title && url) {
                let html = '<a href="' + url + '" class="text-[#2563EB] font-bold underline">' + title + '</a> ';
                document.execCommand('insertHTML', false, html);
                this.syncContentFromEditor();
            }
        },

        toggleSourceMode() {
            this.sourceMode = !this.sourceMode;
            if (!this.sourceMode) {
                // Switching from HTML to Visual
                let editor = this.$refs.wysiwygEditor;
                if (editor) {
                    editor.innerHTML = this.content;
                }
            } else {
                // Switching from Visual to HTML
                let editor = this.$refs.wysiwygEditor;
                if (editor) {
                    this.content = editor.innerHTML;
                }
            }
            this.updateContent(this.content);
        },

        syncContentFromEditor() {
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                this.updateContent(editor.innerHTML);
            }
        },

        syncContentFromTextarea() {
            this.updateContent(this.content);
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                editor.innerHTML = this.content;
            }
        }
    };
}
</script>
@endsection
