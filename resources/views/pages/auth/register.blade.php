@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan — JuangDev')
@section('meta_description', 'Daftar akun JuangDev mudah tanpa OTP dengan Email dan nomor WhatsApp aktif Anda untuk mulai memesan website dan aplikasi kustom.')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-[#0B1126] relative overflow-hidden">
    <!-- Subtle Ambient Glows -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#1a2d6b] rounded-full blur-[140px] opacity-30 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#14265e] rounded-full blur-[140px] opacity-30 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100/90 overflow-hidden">
        
        <!-- Header -->
        <div class="relative bg-[#0A1E5E] text-white p-7 text-center overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff10_1px,transparent_1px),linear-gradient(to_bottom,#ffffff10_1px,transparent_1px)] bg-[size:1.25rem_1.25rem] pointer-events-none"></div>
            
            <a href="{{ route('home') }}" class="inline-block relative z-10 mb-2">
                <img src="{{ asset('logo3.png') }}?v={{ filemtime(public_path('logo3.png')) }}" alt="JuangDev" class="h-8 w-auto mx-auto object-contain">
            </a>
            <h2 class="relative z-10 text-xl font-black tracking-tight text-white mt-2">Daftar Akun JuangDev</h2>
            <p class="relative z-10 text-xs text-white/70 font-medium mt-1">Daftar instan tanpa OTP dengan Email &amp; WhatsApp asli</p>
        </div>

        <div class="p-7 sm:p-8 space-y-5">

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-3.5 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Google Firebase Sign-In -->
            <button 
                type="button" 
                onclick="window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: { mode: 'register' } }))"
                class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-bold shadow-2xs transition-all active:scale-[0.98]"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar dengan Google</span>
            </button>

            <!-- Divider -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider shrink-0">atau dengan email</span>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="Contoh: Bagas Pratama"
                        class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Aktif</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="nama@email.com"
                        class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP Asli</label>
                    <input 
                        type="tel" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                    >
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">*Untuk konfirmasi tagihan resmi &amp; update pengerjaan proyek.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Min. 6 karakter"
                            class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Ulangi password"
                            class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-2xl bg-[#0A1E5E] hover:bg-[#122d78] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#0A1E5E]/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-2"
                >
                    <span>Daftar Akun Sekarang</span>
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>

                <div class="text-center pt-2">
                    <p class="text-xs text-slate-500 font-medium">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-[#2563EB] hover:underline ml-1">
                            Masuk di Sini
                        </a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
