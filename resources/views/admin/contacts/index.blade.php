@extends('layouts.admin')

@section('title', 'Pesan Masuk')
@section('page_title', 'Inbox Pesan Masuk')

@section('content')
    <div x-data="{ search: '' }">
        <!-- Filter Tabs & Search -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !request('status') ? 'bg-[#0A1E5E] text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Semua ({{ \App\Models\Contact::count() }})
                </a>
                <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'unread' ? 'bg-red-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Belum Dibaca ({{ \App\Models\Contact::where('status', 'unread')->count() }})
                </a>
                <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'read' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Sudah Dibaca ({{ \App\Models\Contact::where('status', 'read')->count() }})
                </a>
                <a href="{{ route('admin.contacts.index', ['status' => 'replied']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'replied' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Sudah Dibalas ({{ \App\Models\Contact::where('status', 'replied')->count() }})
                </a>
            </div>

            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Cari pengirim, email, pesan..." 
                    class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-full md:w-64 bg-white shadow-sm"
                >
            </div>
        </div>

        <!-- Contacts Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Pengirim</th>
                            <th class="py-3.5 px-6">Layanan &amp; Budget</th>
                            <th class="py-3.5 px-6">Pesan Klien</th>
                            <th class="py-3.5 px-6">Waktu</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($contacts as $contact)
                            <tr 
                                x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50/80 transition-colors {{ $contact->status === 'unread' ? 'bg-blue-50/30 font-medium' : '' }}"
                            >
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-900">{{ $contact->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $contact->email }}</p>
                                    @if($contact->phone)
                                        <p class="text-xs text-slate-500">{{ $contact->phone }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-block bg-blue-50 text-[#2563EB] text-xs font-bold px-2.5 py-1 rounded-md">
                                        {{ $contact->service ?? 'Konsultasi Umum' }}
                                    </span>
                                    <p class="text-xs text-slate-500 mt-1 font-semibold">{{ $contact->budget ?? 'Budget Fleksibel' }}</p>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-700 max-w-sm">
                                    <div class="whitespace-pre-wrap leading-relaxed">{{ $contact->message }}</div>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $contact->created_at ? $contact->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-lg border border-slate-200 px-2 py-1 bg-white focus:outline-none cursor-pointer">
                                            <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>🔴 Unread</option>
                                            <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>🔵 Read</option>
                                            <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>🟢 Replied</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($contact->phone)
                                            @php
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $contact->phone);
                                                if(str_starts_with($cleanPhone, '0')) { $cleanPhone = '62' . substr($cleanPhone, 1); }
                                                $waReply = urlencode("Halo {$contact->name}, terima kasih telah menghubungi JuangDev terkait {$contact->service}.");
                                            @endphp
                                            <a 
                                                href="https://wa.me/{{ $cleanPhone }}?text={{ $waReply }}" 
                                                target="_blank"
                                                class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors"
                                                title="Balas via WhatsApp"
                                            >
                                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
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
                                    Tidak ada pesan dalam kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contacts->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
