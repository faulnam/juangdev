@extends('layouts.admin')

@section('title', 'Tambah Insight / Artikel Baru')
@section('page_title', 'Tambah Insight Baru')

@section('content')
<div x-data="blogCreateForm()" class="space-y-6">
    <!-- Header Title Bar -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <span class="text-[#2563EB]">+</span> Tambah Insight Baru
        </h2>
        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf

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
                        x-model="title"
                        @input="updateTitle($event.target.value)"
                        placeholder="Contoh: 5 Perbedaan Lapangan Padel dan Tenis yang Wajib Tahu" 
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
                        placeholder="Ringkasan singkat 1-2 kalimat..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                    ></textarea>
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

                    <!-- Tiny / Editor Status Footer Bar -->
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
                            value="JuangDev Team"
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
                            <option value="Technology">Technology</option>
                            <option value="Web Development">Web Development</option>
                            <option value="E-Commerce">E-Commerce</option>
                            <option value="AI & Automation">AI & Automation</option>
                            <option value="Business Strategy">Business Strategy</option>
                            <option value="Web Design">Web Design</option>
                        </select>
                    </div>

                    <!-- Gambar Utama -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Gambar Utama</label>
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-700 cursor-pointer bg-slate-50/50 mb-2"
                        >
                        <input 
                            type="text" 
                            name="image_url" 
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
                            x-model="slug"
                            @input="manualSlug = true"
                            placeholder="slug-otomatis" 
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
                            x-model="metaTitle"
                            @input="manualMetaTitle = true"
                            placeholder="Meta Title SEO..." 
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
                            @input="manualMetaDesc = true"
                            placeholder="Meta Description SEO..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                        ></textarea>
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
                            <span>Simpan Insight</span>
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
function blogCreateForm() {
    return {
        title: '',
        slug: '',
        excerpt: '',
        content: '',
        metaTitle: '',
        metaDescription: '',
        status: 'published',
        publishedAt: '',
        wordCount: 0,
        charCount: 0,
        manualSlug: false,
        manualMetaTitle: false,
        manualMetaDesc: false,

        updateTitle(val) {
            this.title = val;
            if (!this.manualSlug) {
                this.slug = this.slugify(val);
            }
            if (!this.manualMetaTitle) {
                this.metaTitle = val.substring(0, 60);
            }
        },

        updateExcerpt(val) {
            this.excerpt = val;
            if (!this.manualMetaDesc && val.trim() !== '') {
                this.metaDescription = val.substring(0, 160);
            }
        },

        updateContent(val) {
            this.content = val;
            let words = val.trim().split(/\s+/).filter(w => w.length > 0);
            this.wordCount = words.length;
            this.charCount = val.length;
            if (!this.manualMetaDesc && (!this.excerpt || this.excerpt.trim() === '')) {
                let plainText = val.replace(/<[^>]*>/g, '').trim();
                this.metaDescription = plainText.substring(0, 160);
            }
        },

        slugify(text) {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-');
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
