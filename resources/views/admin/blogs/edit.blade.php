@extends('layouts.admin')

@section('title', 'Edit Insight / Artikel')
@section('page_title', 'Edit Insight: ' . $blog->title)

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
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                    >{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <!-- Konten Utama (Rich Text Editor with Custom Actions & Formatting Toolbar) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 pb-3 border-b border-slate-100">
                        <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Konten Utama *</label>
                        
                        <!-- Insertion Quick Action Buttons -->
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="insertLinkProduct()"
                                class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5 border border-slate-200 shadow-xs"
                            >
                                <span>📦 Sisipkan Link Produk</span>
                            </button>

                            <button 
                                type="button" 
                                @click="insertLinkArticle()"
                                class="px-3 py-1.5 rounded-lg bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-xs font-bold transition-colors flex items-center gap-1.5 border border-cyan-200 shadow-xs"
                            >
                                <span>📝 Sisipkan Link Artikel</span>
                            </button>
                        </div>
                    </div>

                    <!-- WYSIWYG Formatting Toolbar -->
                    <div class="flex flex-wrap items-center gap-1 bg-slate-50 p-2 rounded-t-xl border border-slate-200 border-b-0 text-slate-700">
                        <button type="button" @click="formatDoc('undo')" class="p-2 hover:bg-slate-200 rounded text-xs font-bold" title="Undo"><i data-lucide="undo" class="w-4 h-4"></i></button>
                        <button type="button" @click="formatDoc('redo')" class="p-2 hover:bg-slate-200 rounded text-xs font-bold" title="Redo"><i data-lucide="redo" class="w-4 h-4"></i></button>
                        <div class="w-px h-5 bg-slate-300 mx-1"></div>

                        <select @change="insertHeader($event.target.value)" class="px-2 py-1 bg-white border border-slate-200 rounded text-xs font-semibold cursor-pointer">
                            <option value="P">Paragraph</option>
                            <option value="H2">Heading 2</option>
                            <option value="H3">Heading 3</option>
                            <option value="BLOCKQUOTE">Blockquote</option>
                        </select>

                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        <button type="button" @click="formatDoc('bold')" class="p-2 hover:bg-slate-200 rounded font-black text-xs" title="Bold">B</button>
                        <button type="button" @click="formatDoc('italic')" class="p-2 hover:bg-slate-200 rounded italic font-serif text-xs" title="Italic">I</button>
                        
                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        <button type="button" @click="formatDoc('justifyLeft')" class="p-2 hover:bg-slate-200 rounded" title="Align Left"><i data-lucide="align-left" class="w-4 h-4"></i></button>
                        <button type="button" @click="formatDoc('justifyCenter')" class="p-2 hover:bg-slate-200 rounded" title="Align Center"><i data-lucide="align-center" class="w-4 h-4"></i></button>
                        <button type="button" @click="formatDoc('justifyRight')" class="p-2 hover:bg-slate-200 rounded" title="Align Right"><i data-lucide="align-right" class="w-4 h-4"></i></button>
                        <button type="button" @click="formatDoc('justifyFull')" class="p-2 hover:bg-slate-200 rounded" title="Justify"><i data-lucide="align-justify" class="w-4 h-4"></i></button>

                        <div class="w-px h-5 bg-slate-300 mx-1"></div>
                        <button type="button" @click="formatDoc('insertUnorderedList')" class="p-2 hover:bg-slate-200 rounded" title="Bullet List"><i data-lucide="list" class="w-4 h-4"></i></button>
                        <button type="button" @click="formatDoc('insertOrderedList')" class="p-2 hover:bg-slate-200 rounded" title="Numbered List"><i data-lucide="list-ordered" class="w-4 h-4"></i></button>
                    </div>

                    <!-- Hidden Input for Form Submission -->
                    <input type="hidden" name="content" :value="content">

                    <!-- Editable Content Area -->
                    <div 
                        x-ref="wysiwygEditor"
                        contenteditable="true"
                        @input="syncContentFromEditor()"
                        @blur="syncContentFromEditor()"
                        class="w-full min-h-[360px] p-4 bg-white border border-slate-200 rounded-b-xl focus:outline-none text-slate-800 text-sm leading-relaxed overflow-y-auto"
                        style="max-height: 600px;"
                    ></div>

                    <!-- Editor Status Footer Bar -->
                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                        <div class="flex items-center gap-3">
                            <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-mono">p</span>
                            <span>Press Alt+0 for help</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span x-text="wordCount + ' words'">0 words</span>
                            <span x-text="charCount + ' chars'">0 chars</span>
                            <span class="text-slate-300">|</span>
                            <span class="font-bold text-slate-500">tiny</span>
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
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Status *</label>
                        <select name="status" x-model="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                            <option value="published">Published (Rilis Sekarang)</option>
                            <option value="draft">Draft (Simpan Dulu)</option>
                            <option value="scheduled">Scheduled (Jadwalkan Rilis Otomatis)</option>
                        </select>
                    </div>

                    <!-- Scheduled / Published Date & Time Picker -->
                    <div x-show="status === 'scheduled' || status === 'published'" x-cloak>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">
                            <span x-text="status === 'scheduled' ? 'Jadwal Rilis Otomatis *' : 'Tanggal & Jam Rilis'">Jadwal Rilis</span>
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            x-model="publishedAt"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB]"
                        >
                        <p x-show="status === 'scheduled'" class="text-[11px] text-amber-600 mt-1 font-semibold flex items-center gap-1">
                            ⏰ Artikel akan terbit otomatis di website publik pada tanggal &amp; jam yang ditentukan.
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

                    <!-- Gambar Utama -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Gambar Utama</label>
                        @if($blog->image_url)
                            <div class="mb-2 flex items-center gap-3">
                                <img src="{{ $blog->image_url }}" alt="Preview" class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                                <span class="text-[11px] text-slate-500 font-medium">Gambar saat ini</span>
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
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Slug</label>
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
                        <p class="text-[11px] text-slate-400 mt-1 font-normal">Otomatis terisi dari title, bisa diedit manual. max 60 karakter.</p>
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
                        <p class="text-[11px] text-slate-400 mt-1 font-normal">Otomatis terisi dari excerpt, bisa diedit manual. max 160 karakter.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <!-- Primary Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full py-3 px-6 rounded-xl bg-[#0A1E5E] hover:bg-[#122d78] text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2"
                        >
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Update Insight</span>
                        </button>

                        <!-- Secondary Preview Button -->
                        <button 
                            type="button" 
                            @click="
                                if (!slug) { alert('Silakan isi judul artikel terlebih dahulu!'); return; }
                                window.open('/blog/' + slug, '_blank');
                            "
                            class="w-full py-3 px-6 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2"
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
        content: @json($blog->content ?? ''),
        metaTitle: @json($blog->meta_title ?? $blog->title),
        metaDescription: @json($blog->meta_description ?? $blog->excerpt ?? ''),
        status: @json(!$blog->is_published ? 'draft' : ($blog->published_at && $blog->published_at->isFuture() ? 'scheduled' : 'published')),
        publishedAt: @json($blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : ''),
        wordCount: 0,
        charCount: 0,

        init() {
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                editor.innerHTML = this.content;
                this.updateContent(editor.innerHTML);
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
            let words = val.trim().split(/\s+/).filter(w => w.length > 0);
            this.wordCount = words.length;
            this.charCount = val.length;
        },

        formatDoc(cmd, value = null) {
            document.execCommand(cmd, false, value);
            this.syncContentFromEditor();
        },

        insertHeader(level) {
            document.execCommand('formatBlock', false, level);
            this.syncContentFromEditor();
        },

        insertLinkProduct() {
            let title = prompt('Masukkan nama produk / portofolio:', 'Healthcare ERP System');
            let url = prompt('Masukkan URL produk / portofolio:', '/portfolio/healthcare-erp');
            if (title && url) {
                let html = '<a href="' + url + '" class="text-[#2563EB] font-bold underline" target="_blank">' + title + '</a>';
                document.execCommand('insertHTML', false, html);
                this.syncContentFromEditor();
            }
        },

        insertLinkArticle() {
            let title = prompt('Masukkan judul artikel terkait:', 'Tips SEO Website 2026');
            let url = prompt('Masukkan URL artikel:', '/blog/seo-optimization-tips-2026');
            if (title && url) {
                let html = '<a href="' + url + '" class="text-[#2563EB] font-bold underline">' + title + '</a>';
                document.execCommand('insertHTML', false, html);
                this.syncContentFromEditor();
            }
        },

        syncContentFromEditor() {
            let editor = this.$refs.wysiwygEditor;
            if (editor) {
                this.updateContent(editor.innerHTML);
            }
        }
    };
}
</script>
@endsection
