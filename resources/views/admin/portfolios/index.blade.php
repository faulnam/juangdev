@extends('layouts.admin')

@section('title', 'Portfolios')
@section('page_title', 'Kelola Showcase Portfolio')

@section('content')
    <div x-data="{ search: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari nama proyek, kategori, deskripsi..." 
                        class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-64 sm:w-80 bg-white shadow-sm"
                    >
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $portfolios->count() }} proyek</span>
            </div>
            
            <a href="{{ route('admin.portfolios.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A1E5E] text-[#C7F236] text-xs font-bold hover:bg-[#122d78] shadow-md transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Portfolio Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Proyek</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Deskripsi</th>
                            <th class="py-3.5 px-6">Live URL</th>
                            <th class="py-3.5 px-6">Featured</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($portfolios as $project)
                            <tr 
                                x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50/80 transition-colors"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-8 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                            @if($project->image_url)
                                                <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs font-black text-slate-400">J</div>
                                            @endif
                                        </div>
                                        <span>{{ $project->title }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="bg-blue-50 text-[#2563EB] text-xs font-bold px-2.5 py-1 rounded-md">
                                            {{ $project->category ?? 'Web Application' }}
                                        </span>
                                        @if($project->package_tier)
                                            <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-md">
                                                Paket {{ $project->package_tier }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500 max-w-xs truncate">
                                    {{ $project->description }}
                                </td>
                                <td class="py-4 px-6 text-xs font-mono text-blue-600">
                                    @if($project->live_url)
                                        <a href="{{ $project->live_url }}" target="_blank" class="hover:underline flex items-center gap-1">
                                            <span>Link</span>
                                            <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1">
                                        @if($project->is_boilerplate)
                                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">
                                                Boilerplate @if($project->sold_count > 0) • {{ $project->sold_count }}x Terjual @endif
                                            </span>
                                        @endif
                                        @if($project->featured)
                                            <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-2 py-0.5 rounded-md uppercase">Featured</span>
                                        @endif
                                        @if(!$project->is_boilerplate && !$project->featured)
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.portfolios.edit', $project->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.portfolios.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Hapus portfolio ini?')">
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
                                    Belum ada portfolio yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
