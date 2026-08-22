@extends('layouts.admin')

@section('title', 'Kelola FAQ')
@section('page_title', 'Daftar Pertanyaan Umum (FAQ)')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-black text-slate-900">FAQ (Tanya Jawab)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola daftar pertanyaan yang sering diajukan calon klien</p>
        </div>
        <a 
            href="{{ route('admin.faqs.create') }}" 
            class="px-5 py-2.5 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-xs hover:bg-[#122d78] transition-all shadow-sm inline-flex items-center gap-2"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah FAQ Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 w-16 text-center">Urutan</th>
                        <th class="py-4 px-6">Pertanyaan</th>
                        <th class="py-4 px-6">Jawaban</th>
                        <th class="py-4 px-6 w-28 text-center">Status</th>
                        <th class="py-4 px-6 text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-400 text-xs">
                                {{ $faq->display_order }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900 max-w-xs">
                                {{ $faq->question }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600 max-w-md line-clamp-2">
                                {{ $faq->answer }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($faq->is_active)
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="{{ route('admin.faqs.edit', $faq->id) }}" 
                                        class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg transition-colors"
                                        title="Edit FAQ"
                                    >
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>

                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-sm font-medium">
                                Belum ada data FAQ. Klik tombol "Tambah FAQ Baru" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
