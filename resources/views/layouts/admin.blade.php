<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — JuangDev</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Standalone CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f0f4f9] text-slate-800 antialiased selection:bg-blue-600 selection:text-white">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Clean White Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200/80 flex flex-col h-full flex-shrink-0 transition-transform duration-300 ease-in-out shadow-xs"
        >
            <!-- Logo Header -->
            <div class="p-6 flex items-center justify-between border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#0A1E5E] text-[#C7F236] flex items-center justify-center font-extrabold text-base shadow-xs shrink-0">
                        JD
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-tight">JuangDev</h2>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Panel Admin</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-7 admin-sidebar">
                
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        MENU UTAMA
                    </div>
                    <nav class="space-y-1">
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Dashboard</span>
                        </a>

                        <a 
                            href="{{ route('admin.contacts.index') }}" 
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <div class="flex items-center gap-3">
                                <i data-lucide="inbox" class="w-4 h-4"></i>
                                <span>Pesan Masuk</span>
                            </div>
                            @php $unreadCount = \App\Models\Contact::where('status', 'unread')->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        LAYANAN &amp; HARGA
                    </div>
                    <nav class="space-y-1">
                        <a 
                            href="{{ route('admin.services.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            <span>Layanan Utama</span>
                        </a>

                        <a 
                            href="{{ route('admin.service-features.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.service-features.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span>Fitur Add-on</span>
                        </a>

                        <a 
                            href="{{ route('admin.pricing.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.pricing.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            <span>Paket Harga</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        KELOLA KONTEN
                    </div>
                    <nav class="space-y-1">
                        <a 
                            href="{{ route('admin.portfolios.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.portfolios.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                            <span>Portofolio Proyek</span>
                        </a>

                        <a 
                            href="{{ route('admin.testimonials.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.testimonials.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span>Testimoni Klien</span>
                        </a>

                        <a 
                            href="{{ route('admin.blogs.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.blogs.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>Artikel &amp; Blog</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        PENGATURAN SISTEM
                    </div>
                    <nav class="space-y-1">
                        <a 
                            href="{{ route('admin.settings.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#eef3fb] text-[#2563EB] font-bold shadow-xs' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}"
                        >
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>Pengaturan Website</span>
                        </a>

                        <a 
                            href="{{ route('home') }}" 
                            target="_blank" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors"
                        >
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span>Lihat Website</span>
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST" class="pt-3">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors"
                            >
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Keluar Admin</span>
                            </button>
                        </form>
                    </nav>
                </div>

            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Bar Header with Title & User Profile Pill -->
            <header class="h-20 px-6 sm:px-8 flex items-center justify-between flex-shrink-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-white shadow-xs">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Dashboard / Ringkasan</p>
                    </div>
                </div>

                <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
                    <!-- User Profile Dropdown Pill -->
                    <div class="relative">
                        <button 
                            @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2.5 bg-white border border-slate-200/80 rounded-full py-1.5 px-3.5 shadow-xs hover:bg-slate-50 transition-colors"
                        >
                            <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                AD
                            </div>
                            <span class="text-xs font-bold text-slate-800 max-w-[140px] truncate">Admin JuangDev</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>

                        <div 
                            x-show="userMenuOpen" 
                            @click.away="userMenuOpen = false" 
                            x-cloak 
                            class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-2xl shadow-lg py-2 z-50 text-xs font-medium"
                        >
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700">
                                <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                                <span>Pengaturan</span>
                            </a>
                            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700">
                                <i data-lucide="external-link" class="w-4 h-4 text-slate-400"></i>
                                <span>Lihat Website</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 hover:bg-red-50 text-red-600 font-semibold">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Body -->
            <main class="flex-1 overflow-y-auto px-6 sm:px-8 pb-8">
                <div class="max-w-[1400px] mx-auto">
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-xs font-semibold shadow-xs">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center gap-3 text-xs font-semibold shadow-xs">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
