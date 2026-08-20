@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page_title', 'Pengaturan Website & Kontak')

@section('content')
    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-black text-slate-900">Informasi Umum Website</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pengaturan judul dan deskripsi default</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Website</label>
                    <input 
                        type="text" 
                        name="site_name" 
                        value="{{ $settings['site_name'] ?? 'JuangDev' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Resmi</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ $settings['email'] ?? 'hello@juangdev.com' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Website</label>
                <textarea 
                    name="site_description" 
                    rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB] resize-none"
                >{{ $settings['site_description'] ?? 'JuangDev helps startups, SMEs, and enterprises build modern websites, web applications, and custom digital software.' }}</textarea>
            </div>

            <div class="border-b border-slate-100 pb-4 pt-4">
                <h3 class="text-base font-black text-slate-900">Kontak &amp; WhatsApp</h3>
                <p class="text-xs text-slate-500 mt-0.5">Nomor untuk redirect CTA dan Estimator Proyek</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp (Awali 62) *</label>
                    <input 
                        type="text" 
                        name="whatsapp_number" 
                        value="{{ $settings['whatsapp_number'] ?? '6283852174877' }}"
                        placeholder="6283852174877"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / Call</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ $settings['phone'] ?? '+62 812-3456-7890' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat / Lokasi</label>
                    <input 
                        type="text" 
                        name="address" 
                        value="{{ $settings['address'] ?? 'Jakarta, Indonesia' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Operasional</label>
                    <input 
                        type="text" 
                        name="working_hours" 
                        value="{{ $settings['working_hours'] ?? 'Senin - Sabtu: 09:00 - 18:00 WIB' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="border-b border-slate-100 pb-4 pt-4">
                <h3 class="text-base font-black text-slate-900">Social Media</h3>
                <p class="text-xs text-slate-500 mt-0.5">Link akun sosial media yang tampil di footer</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Instagram URL</label>
                    <input 
                        type="url" 
                        name="instagram_url" 
                        value="{{ $settings['instagram_url'] ?? '' }}"
                        placeholder="https://instagram.com/juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">X (Twitter) URL</label>
                    <input 
                        type="url" 
                        name="x_url" 
                        value="{{ $settings['x_url'] ?? '' }}"
                        placeholder="https://x.com/juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Threads URL</label>
                    <input 
                        type="url" 
                        name="threads_url" 
                        value="{{ $settings['threads_url'] ?? '' }}"
                        placeholder="https://threads.net/@juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">GitHub URL</label>
                    <input 
                        type="url" 
                        name="github_url" 
                        value="{{ $settings['github_url'] ?? '' }}"
                        placeholder="https://github.com/juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">LinkedIn URL</label>
                    <input 
                        type="url" 
                        name="linkedin_url" 
                        value="{{ $settings['linkedin_url'] ?? '' }}"
                        placeholder="https://linkedin.com/company/juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">TikTok URL</label>
                    <input 
                        type="url" 
                        name="tiktok_url" 
                        value="{{ $settings['tiktok_url'] ?? '' }}"
                        placeholder="https://tiktok.com/@juangdev"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:border-[#2563EB]"
                    >
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#0A1E5E] text-[#C7F236] font-bold text-sm hover:bg-[#122d78] shadow-md transition-all">
                    Simpan Perubahan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
