@extends('layouts.app')

@section('title', 'Estimator Biaya Pembuatan Website & Software — JuangDev')
@section('meta_description', 'Hitung simulasi estimasi biaya pembuatan website, landing page, toko online, dan sistem web kustom Anda secara instan dan transparan di JuangDev.')
@section('meta_keywords', 'estimator biaya website, hitung harga website, kalkulator web developer, biaya pembuatan landing page, harga jasa bikin web, estimasi project juangdev')

@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '62859171681988';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin konsultasi langsung mengenai estimasi biaya proyek saya.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

@section('content')
    <!-- Estimator Hero Section -->
    <section 
        class="relative pt-32 pb-16 md:pt-40 md:pb-24 overflow-hidden text-white text-center bg-[#071542]"
        style="background: linear-gradient(160deg, #071542 0%, #0A1E5E 50%, #122d78 100%);"
    >
        <!-- Decorative Grid & Glowing Orbs -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
        <div class="absolute -top-24 right-1/4 w-96 h-96 rounded-full bg-[#2563EB]/25 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 rounded-full bg-[#C7F236]/10 blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
            <!-- Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight mb-6">
                Hitung Estimasi Biaya Proyek Digital Anda Secara <span class="text-[#C7F236]">Transparan</span>
            </h1>

            <p class="text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-medium mb-4">
                Pilih kategori layanan, paket spesifikasi, dan fitur add-on sesuai kebutuhan bisnis Anda. Dapatkan rincian anggaran serta skema pembayaran Uang Muka (DP 50%) secara instan.
            </p>
        </div>
    </section>

    <!-- Main Estimator Interactive Component -->
    <div class="relative bg-[#f8f9fc]">
        @include('partials.estimator')
    </div>

    <!-- Need Custom Architecture CTA Section -->
    <section class="py-16 md:py-20 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-6 sm:px-8 text-center">
            <div class="p-8 sm:p-12 rounded-3xl bg-[#0A1E5E] text-white relative overflow-hidden shadow-2xl">
                <div class="absolute -right-20 -top-20 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-[#C7F236]/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <span class="inline-block text-xs font-bold uppercase tracking-wider text-[#C7F236] bg-white/10 px-3.5 py-1 rounded-full mb-4">
                        Punya Spesifikasi Khusus?
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black mb-4">
                        Butuh Konsultasi Kustom &amp; Penawaran Khusus?
                    </h3>
                    <p class="text-white/80 text-sm sm:text-base leading-relaxed mb-8">
                        Jika sistem Anda memerlukan integrasi API kompleks, migrasi database skala besar, arsitektur ERP/CRM tingkat lanjut, tim arsitek software JuangDev siap membantu menyusun proposal dan NDA resmi.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a 
                            href="{{ $whatsappUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#C7F236] text-[#0A1E5E] font-black text-sm px-7 py-3.5 rounded-full hover:bg-[#b5dd2a] hover:scale-105 transition-all shadow-lg shadow-[#C7F236]/20"
                        >
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Konsultasi Teknis via WhatsApp</span>
                        </a>
                        <a 
                            href="{{ route('contact') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold text-sm px-6 py-3.5 rounded-full border border-white/20 transition-all"
                        >
                            <span>Isi Formulir Kontak</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
