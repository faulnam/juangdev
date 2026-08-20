@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page_title', 'Kelola Artikel & Blog')

@section('content')
    <div x-data="{ search: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari judul artikel, kategori, penulis..." 
                        class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-64 sm:w-80 bg-white shadow-sm"
                    >
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $blogs->count() }} artikel</span>
            </div>
            
            <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A1E5E] text-[#C7F236] text-xs font-bold hover:bg-[#122d78] shadow-md transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tulis Artikel Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Judul Artikel</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Penulis</th>
                            <th class="py-3.5 px-6">Tanggal</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($blogs as $blog)
                            <tr 
                                x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50/80 transition-colors"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $blog->title }}
                                    <p class="text-xs font-mono text-slate-400 font-normal mt-0.5">/blog/{{ $blog->slug }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-blue-50 text-[#2563EB] text-xs font-bold px-2.5 py-1 rounded-md">
                                        {{ $blog->category ?? 'Technology' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-600 font-medium">
                                    {{ $blog->author ?? 'JuangDev Team' }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $blog->created_at ? $blog->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($blog->is_published)
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">Published</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">Draft</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
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
                                <td colspan="6" class="py-12 text-center text-slate-400 text-sm font-medium">
                                    Belum ada artikel blog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
