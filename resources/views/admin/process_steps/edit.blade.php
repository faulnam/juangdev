@extends('layouts.admin')

@section('title', 'Edit Langkah Pemesanan')
@section('page_title', 'Edit Langkah Cara Pemesanan')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
        <form action="{{ route('admin.process-steps.update', $processStep->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Langkah (Contoh: 01, 02)</label>
                    <input 
                        type="text" 
                        name="step_number" 
                        value="{{ $processStep->step_number }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Ikon Lucide</label>
                    <select name="icon" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] bg-white cursor-pointer">
                        <option value="monitor" {{ $processStep->icon === 'monitor' ? 'selected' : '' }}>monitor (Layar/Perangkat)</option>
                        <option value="lightbulb" {{ $processStep->icon === 'lightbulb' ? 'selected' : '' }}>lightbulb (Ide/Lampu)</option>
                        <option value="handshake" {{ $processStep->icon === 'handshake' ? 'selected' : '' }}>handshake (Kerjasama)</option>
                        <option value="rocket" {{ $processStep->icon === 'rocket' ? 'selected' : '' }}>rocket (Roket/Peluncuran)</option>
                        <option value="message-square" {{ $processStep->icon === 'message-square' ? 'selected' : '' }}>message-square (Pesan/Diskusi)</option>
                        <option value="check-circle" {{ $processStep->icon === 'check-circle' ? 'selected' : '' }}>check-circle (Centang/Selesai)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Langkah *</label>
                <input 
                    type="text" 
                    name="title" 
                    required 
                    value="{{ $processStep->title }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Penjelasan *</label>
                <textarea 
                    name="description" 
                    rows="4" 
                    required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ $processStep->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil</label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="{{ $processStep->display_order }}" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700 select-none">
                        <input type="checkbox" name="is_active" value="1" {{ $processStep->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-[#2563EB] focus:ring-0">
                        <span>Tampilkan di Website (Aktif)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.process-steps.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
