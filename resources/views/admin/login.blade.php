<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — JuangDev</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Standalone CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans min-h-screen bg-[#0A1E5E] flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Ambient Glows -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#2563EB]/40 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#C7F236]/20 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 sm:p-10 shadow-2xl relative z-10 border border-slate-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0A1E5E] text-[#C7F236] font-serif font-black text-2xl mb-4 shadow-lg">
                J
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Admin Portal</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Masuk untuk mengelola konten dan pesanan JuangDev</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username / Email</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="username" 
                        required 
                        value="{{ old('username') }}"
                        placeholder="admin" 
                        class="w-full pl-11 pr-4 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-900 font-medium focus:outline-none focus:border-[#2563EB] transition-colors text-sm"
                    >
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="••••••••" 
                        class="w-full pl-11 pr-4 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-slate-900 font-medium focus:outline-none focus:border-[#2563EB] transition-colors text-sm"
                    >
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-4 rounded-xl bg-[#0A1E5E] hover:bg-[#122d78] text-[#C7F236] font-bold text-sm shadow-lg shadow-[#0A1E5E]/20 transition-all flex items-center justify-center gap-2"
            >
                <span>Masuk ke Dashboard</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                &larr; Kembali ke Website Utama
            </a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
