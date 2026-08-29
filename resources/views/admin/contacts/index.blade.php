@extends('layouts.admin')

@section('title', 'Pesan Masuk')
@section('page_title', 'Inbox Pesan Masuk')

@section('content')
<div x-data="{ search: '', activeContact: null }" class="space-y-6">
    
    <!-- Filter Tabs & Search Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 hide-scrollbar">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs shrink-0 {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Semua ({{ \App\Models\Contact::count() }})
            </a>
            <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs shrink-0 {{ request('status') === 'unread' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Belum Dibaca ({{ \App\Models\Contact::where('status', 'unread')->count() }})
            </a>
            <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs shrink-0 {{ request('status') === 'read' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Sudah Dibaca ({{ \App\Models\Contact::where('status', 'read')->count() }})
            </a>
            <a href="{{ route('admin.contacts.index', ['status' => 'replied']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs shrink-0 {{ request('status') === 'replied' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Sudah Dibalas ({{ \App\Models\Contact::where('status', 'replied')->count() }})
            </a>
        </div>

        <div class="relative">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input 
                type="text" 
                x-model="search" 
                placeholder="Cari pengirim, email, pesan..." 
                class="pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-[#2563EB] w-full md:w-72 bg-white shadow-xs"
            >
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Pengirim</th>
                        <th class="py-4 px-6">Layanan &amp; Budget</th>
                        <th class="py-4 px-6">Ringkasan Pesan</th>
                        <th class="py-4 px-6">Waktu</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($contacts as $contact)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $contact->phone ?? '');
                            if(str_starts_with($cleanPhone, '0')) { $cleanPhone = '62' . substr($cleanPhone, 1); }
                            $waReply = urlencode("Halo {$contact->name}, terima kasih telah menghubungi JuangDev terkait {$contact->service}.");
                            $waUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text={$waReply}" : '#';
                        @endphp
                        <tr 
                            x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
                            class="hover:bg-slate-50/80 transition-colors {{ $contact->status === 'unread' ? 'bg-blue-50/30 font-medium' : '' }}"
                        >
                            <!-- Pengirim -->
                            <td class="py-4 px-6 min-w-[200px]">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#2563EB] font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $contact->name }}</p>
                                        <p class="text-xs text-slate-500 font-normal mt-0.5">{{ $contact->email }}</p>
                                        @if($contact->phone)
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $contact->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Layanan & Budget -->
                            <td class="py-4 px-6 min-w-[180px]">
                                <span class="inline-block bg-blue-50 text-[#2563EB] text-[11px] font-bold px-2.5 py-1 rounded-md border border-blue-100">
                                    {{ $contact->service ?? 'Konsultasi Umum' }}
                                </span>
                                <p class="text-xs text-slate-500 mt-1 font-semibold">{{ $contact->budget ?? 'Budget Fleksibel' }}</p>
                            </td>

                            <!-- Ringkasan Pesan -->
                            <td class="py-4 px-6 max-w-xs">
                                <p class="text-xs text-slate-700 font-normal line-clamp-2 leading-relaxed">
                                    {{ $contact->message }}
                                </p>
                                <button 
                                    type="button"
                                    @click="activeContact = {{ json_encode([
                                        'id' => $contact->id,
                                        'name' => $contact->name,
                                        'email' => $contact->email,
                                        'phone' => $contact->phone,
                                        'service' => $contact->service ?? 'Konsultasi Umum',
                                        'budget' => $contact->budget ?? 'Budget Fleksibel',
                                        'message' => $contact->message,
                                        'status' => $contact->status,
                                        'created_at' => $contact->created_at ? $contact->created_at->format('d M Y, H:i') : '-',
                                        'wa_url' => $waUrl
                                    ]) }}"
                                    class="text-[11px] text-[#2563EB] font-bold hover:underline mt-1 inline-flex items-center gap-1"
                                >
                                    <span>Lihat Detail</span>
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </button>
                            </td>

                            <!-- Waktu -->
                            <td class="py-4 px-6 text-xs text-slate-400 whitespace-nowrap">
                                {{ $contact->created_at ? $contact->created_at->format('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Status Select -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select 
                                        name="status" 
                                        onchange="this.form.submit()" 
                                        class="text-[11px] font-bold rounded-lg border border-slate-200 px-2.5 py-1.5 bg-white focus:outline-none cursor-pointer shadow-xs"
                                    >
                                        <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>🔴 Belum Dibaca</option>
                                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>🔵 Sudah Dibaca</option>
                                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>🟢 Sudah Dibalas</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Detail Modal Button -->
                                    <button 
                                        type="button"
                                        @click="activeContact = {{ json_encode([
                                            'id' => $contact->id,
                                            'name' => $contact->name,
                                            'email' => $contact->email,
                                            'phone' => $contact->phone,
                                            'service' => $contact->service ?? 'Konsultasi Umum',
                                            'budget' => $contact->budget ?? 'Budget Fleksibel',
                                            'message' => $contact->message,
                                            'status' => $contact->status,
                                            'created_at' => $contact->created_at ? $contact->created_at->format('d M Y, H:i') : '-',
                                            'wa_url' => $waUrl
                                        ]) }}"
                                        class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg transition-colors"
                                        title="Lihat Detail Pesan"
                                    >
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    @if($contact->phone)
                                        <a 
                                            href="{{ $waUrl }}" 
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
                                Belum ada pesan dalam kategori ini.
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

    <!-- Contact Detail Modal -->
    <div 
        x-show="activeContact !== null" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.away="activeContact = null"
            class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-slate-100"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <span class="text-[#2563EB]">📩</span> Detail Pesan Masuk
                </h3>
                <button @click="activeContact = null" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">✕</button>
            </div>

            <template x-if="activeContact">
                <div class="space-y-5">
                    <!-- Sender Profile Card -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengirim</span>
                            <span class="text-xs text-slate-400 font-medium" x-text="activeContact.created_at"></span>
                        </div>
                        <h4 class="text-base font-black text-slate-900" x-text="activeContact.name"></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-medium text-slate-600">
                            <div>📧 Email: <span class="font-bold text-slate-800" x-text="activeContact.email"></span></div>
                            <div>📱 WhatsApp: <span class="font-bold text-slate-800" x-text="activeContact.phone || '-'"></span></div>
                        </div>
                    </div>

                    <!-- Service & Budget Badges -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="bg-blue-50 text-[#2563EB] px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-100">
                            Layanan: <span x-text="activeContact.service"></span>
                        </div>
                        <div class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200">
                            Budget: <span x-text="activeContact.budget"></span>
                        </div>
                    </div>

                    <!-- Full Message Box -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pesan Lengkap</label>
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 leading-relaxed font-normal whitespace-pre-wrap max-h-60 overflow-y-auto" x-text="activeContact.message"></div>
                    </div>

                    <!-- Modal Action Footer -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="activeContact = null"
                            class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all"
                        >
                            Tutup
                        </button>

                        <template x-if="activeContact.phone && activeContact.wa_url !== '#'">
                            <a 
                                :href="activeContact.wa_url"
                                target="_blank"
                                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-all flex items-center gap-2"
                            >
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                <span>Balas via WhatsApp</span>
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
