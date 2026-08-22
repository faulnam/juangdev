@extends('layouts.admin')

@section('title', 'Tambah FAQ')
@section('page_title', 'Tambah Pertanyaan FAQ Baru')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pertanyaan (Question) *</label>
                <input 
                    type="text" 
                    name="question" 
                    required 
                    placeholder="Contoh: Berapa lama waktu pengerjaan website?"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jawaban (Answer) *</label>
                <textarea 
                    name="answer" 
                    rows="5" 
                    required 
                    placeholder="Tuliskan jawaban yang ringkas, jelas, dan membantu calon klien..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (Display Order)</label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="1" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700 select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-[#2563EB] focus:ring-0">
                        <span>Tampilkan di Website (Aktif)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan FAQ
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
