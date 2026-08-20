@extends('layouts.app')

@section('title', 'Kontak — JuangDev')
@section('meta_description', 'Hubungi tim JuangDev untuk mendiskusikan proyek website dan software digital Anda. Kami siap berkolaborasi.')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin konsultasi terkait proyek baru.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
    $email = $settings['email'] ?? 'halo@juangdev.com';
    $phone = $settings['phone'] ?? '+62 812-3456-7890';
    $address = $settings['address'] ?? 'Jakarta, Indonesia';
    $hours = $settings['working_hours'] ?? 'Senin - Sabtu: 09:00 - 18:00 WIB';
@endphp

@section('content')
    <!-- Contact Hero -->
    <section 
        class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden text-white text-center"
        style="background: linear-gradient(160deg, #1a3fa0 0%, #122d78 45%, #0A1E5E 100%);"
    >
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight mb-6">
                Mari Bangun Sesuatu yang <span class="font-serif italic text-[#C7F236]">Luar Biasa Bersama</span>
            </h1>
            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                Memiliki ide proyek atau ingin bertanya mengenai layanan kami? Kirimkan pesan kepada kami atau hubungi langsung via WhatsApp.
            </p>
        </div>
    </section>

    <!-- Main Form & Info Section -->
    <section class="py-16 md:py-24 bg-[#f8f9fc]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-6 rounded-2xl bg-emerald-50 border-2 border-emerald-500 text-emerald-900 flex items-center gap-4 shadow-lg shadow-emerald-500/10">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="check" class="w-6 h-6 stroke-[3]"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg">Pesan Berhasil Terkirim!</h4>
                        <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] gap-12 lg:gap-16 items-start">
                
                <!-- Contact Form Card -->
                <div class="bg-white rounded-[2rem] border-2 border-slate-100 p-8 sm:p-10 md:p-12 shadow-xl shadow-slate-200/50">
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Kirimkan Pesan Kepada Kami</h3>
                    <p class="text-slate-500 text-sm mb-8 font-medium">Isi formulir di bawah ini dan tim kami akan merespon dalam waktu 24 jam.</p>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    required 
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email *</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required 
                                    placeholder="budi@example.com"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    placeholder="+62 812 3456 7890"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Layanan yang Dibutuhkan</label>
                                <select 
                                    name="service"
                                    class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors"
                                >
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach($services as $srv)
                                        <option value="{{ $srv->name }}" {{ ($selectedService == $srv->slug) ? 'selected' : '' }}>
                                            {{ $srv->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Anggaran / Budget</label>
                            <select 
                                name="budget"
                                class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors"
                            >
                                <option value="">-- Pilih Rentang Budget --</option>
                                <option value="< 500 Ribu">&lt; Rp 500.000 (Landing Page Starter)</option>
                                <option value="500 Ribu - 1.5 Juta">Rp 500.000 - Rp 1.500.000</option>
                                <option value="1.5 Juta - 5 Juta">Rp 1.500.000 - Rp 5.000.000</option>
                                <option value="> 5 Juta">&gt; Rp 5.000.000 (Custom Web App / ERP)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pesan atau Keterangan Proyek *</label>
                            <textarea 
                                name="message" 
                                rows="4" 
                                required
                                placeholder="Jelaskan kebutuhan, target, atau fitur yang Anda inginkan..."
                                class="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-800 font-medium focus:outline-none focus:border-[#2563EB] transition-colors resize-none"
                            ></textarea>
                        </div>

                        <button 
                            type="submit"
                            class="w-full py-4 rounded-xl bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-bold text-base shadow-lg shadow-[#2563EB]/25 transition-all flex items-center justify-center gap-2"
                        >
                            <span>Kirim Pesan Sekarang</span>
                            <i data-lucide="send" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>

                <!-- Direct Contact Info Cards -->
                <div class="space-y-6">
                    
                    <!-- WhatsApp Card -->
                    <div class="bg-[#0A1E5E] text-white rounded-[2rem] p-8 shadow-xl shadow-[#0A1E5E]/20">
                        <div class="w-12 h-12 rounded-2xl bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center mb-6 font-bold">
                            <i data-lucide="message-circle" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-2xl font-black mb-2">Respon Cepat WhatsApp</h4>
                        <p class="text-white/80 text-sm leading-relaxed mb-6 font-medium">
                            Ingin respon cepat dalam hitungan menit? Langsung hubungi kami via WhatsApp untuk diskusi instan.
                        </p>
                        <a 
                            href="{{ $whatsappUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-sm font-bold bg-[#C7F236] text-[#0A1E5E] hover:bg-[#b5dd2a] transition-all shadow-md w-full"
                        >
                            <span>Chat via WhatsApp</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                        </a>
                    </div>

                    <!-- Contact Details Box -->
                    <div class="bg-white rounded-[2rem] border-2 border-slate-100 p-8 shadow-xl shadow-slate-200/50 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center shrink-0">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email Kami</p>
                                <p class="text-slate-900 font-bold text-base mt-0.5">{{ $email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center shrink-0">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon / HP</p>
                                <p class="text-slate-900 font-bold text-base mt-0.5">{{ $phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jam Kerja</p>
                                <p class="text-slate-900 font-bold text-base mt-0.5">{{ $hours }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi Kantor</p>
                                <p class="text-slate-900 font-bold text-base mt-0.5">{{ $address }}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- FAQ -->
    @include('partials.faq')
@endsection
