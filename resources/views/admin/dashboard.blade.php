@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <!-- Top Metric Cards Row (Exact Reference UI Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        
        <!-- Card 1: Total Pesan Masuk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-800 shrink-0 border border-slate-200/60">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['contacts'] ?? 0 }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Pesan Masuk</p>
            </div>
        </div>

        <!-- Card 2: Layanan Utama -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-800 shrink-0 border border-slate-200/60">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['services'] ?? 0 }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Layanan Utama</p>
            </div>
        </div>

        <!-- Card 3: Total Portfolio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-800 shrink-0 border border-slate-200/60">
                <i data-lucide="box" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['portfolios'] ?? 0 }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Portfolio</p>
            </div>
        </div>

        <!-- Card 4: Total Testimoni -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-800 shrink-0 border border-slate-200/60">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['testimonials'] ?? 0 }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Testimoni Klien</p>
            </div>
        </div>

    </div>

    <!-- Main Content Grid Row: Chart on Left, Status Summary on Right (Exact Reference UI Style) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Left: Line Chart Card (66% width) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900">Pesan Masuk 6 Bulan Terakhir</h3>
                <span class="text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-md">Bulanan</span>
            </div>
            <div class="relative w-full h-[240px]">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <!-- Right: Status Pesan List Card (33% width) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Status Pesan &amp; Layanan</h3>
            
            <div class="divide-y divide-slate-100 flex-1 flex flex-col justify-around">
                <div class="py-3 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-600">Belum Dibaca (Unread)</span>
                    <span class="text-sm font-bold text-slate-900">{{ $stats['unread_contacts'] ?? 0 }}</span>
                </div>

                <div class="py-3 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-600">Sudah Dibaca (Read)</span>
                    <span class="text-sm font-bold text-slate-900">
                        {{ \App\Models\Contact::where('status', 'read')->count() }}
                    </span>
                </div>

                <div class="py-3 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-600">Sudah Dibalas (Replied)</span>
                    <span class="text-sm font-bold text-slate-900">
                        {{ \App\Models\Contact::where('status', 'replied')->count() }}
                    </span>
                </div>

                <div class="py-3 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-600">Layanan Aktif</span>
                    <span class="text-sm font-bold text-slate-900">{{ $stats['services'] ?? 0 }}</span>
                </div>

                <div class="py-3 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-600">Proyek Portfolio</span>
                    <span class="text-sm font-bold text-slate-900">{{ $stats['portfolios'] ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Row: Recent Messages Table (Clean Reference Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Pesan Masuk Terbaru</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar calon klien yang menghubungi melalui website JuangDev</p>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="text-xs font-bold text-[#2563EB] hover:underline flex items-center gap-1">
                <span>Lihat Semua Inbox</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Nama &amp; Email</th>
                        <th class="py-3.5 px-6">Layanan</th>
                        <th class="py-3.5 px-6">Budget</th>
                        <th class="py-3.5 px-6">Pesan Klien</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($recentContacts as $contact)
                        <tr class="hover:bg-slate-50/70 transition-colors {{ $contact->status === 'unread' ? 'bg-blue-50/20' : '' }}">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-xs sm:text-sm">{{ $contact->name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $contact->email }} • {{ $contact->phone ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-block bg-[#eef3fb] text-[#2563EB] text-xs font-bold px-2.5 py-1 rounded-md">
                                    {{ $contact->service ?? 'Konsultasi Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-700 font-semibold">
                                {{ $contact->budget ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600 max-w-xs truncate">
                                {{ $contact->message }}
                            </td>
                            <td class="py-4 px-6">
                                @if($contact->status === 'unread')
                                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Unread</span>
                                @elseif($contact->status === 'read')
                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Read</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Replied</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('admin.contacts.index') }}" class="text-xs font-bold text-[#2563EB] hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 text-xs font-medium">
                                Belum ada pesan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('dashboardChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mar 2026', 'Apr 2026', 'May 2026', 'Jun 2026', 'Jul 2026', 'Aug 2026'],
                    datasets: [{
                        label: 'Pesan Masuk',
                        data: [2, 4, 7, 5, 9, {{ $stats['contacts'] ?? 12 }}],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: '#0A1E5E',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#94a3b8' }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 2 }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
