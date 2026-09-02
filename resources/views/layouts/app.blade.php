<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="vX9QufAoSYunJ7w_SR7QIQ5WwaxXkZoLA_kNq7tv-Ns" />
    
    <!-- Core SEO Meta Tags -->
    <title>@yield('title', 'JuangDev — Jasa Pembuatan Website & Custom Software')</title>
    <meta name="description" content="@yield('meta_description', 'JuangDev membantu bisnis, startup, dan UMKM membangun website profesional, aplikasi web, toko online, dan sistem kustom berkualitas tinggi dengan harga transparan.')">
    <meta name="keywords" content="@yield('meta_keywords', 'jasa pembuatan website, buat website murah, bikin web toko online, company profile profesional, aplikasi web kustom, sistem informasi bisnis, web developer indonesia, juangdev')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="author" content="JuangDev">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    
    @php
        $rawOgImage = View::getSection('og_image', asset('logo1.png'));
        $ogImage = $rawOgImage ? (str_starts_with($rawOgImage, 'http') ? $rawOgImage : url($rawOgImage)) : asset('logo1.png');
        $imgExt = strtolower(pathinfo(parse_url($ogImage, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $mimeMap = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
        ];
        $ogImageType = $mimeMap[$imgExt] ?? 'image/png';
        $resolvedTitle = View::getSection('og_title', View::getSection('title', 'JuangDev — Jasa Pembuatan Website & Custom Software'));
        $resolvedDesc = View::getSection('og_description', View::getSection('meta_description', 'JuangDev membantu bisnis, startup, dan UMKM membangun website profesional, aplikasi web, toko online, dan sistem kustom berkualitas tinggi.'));
    @endphp

    <!-- Open Graph / WhatsApp / Facebook -->
    <meta property="og:site_name" content="JuangDev">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $resolvedTitle }}">
    <meta property="og:description" content="{{ $resolvedDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:type" content="{{ $ogImageType }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $resolvedTitle }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $resolvedTitle }}">
    <meta name="twitter:description" content="{{ $resolvedDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    
    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" href="{{ asset('logo2.png') }}?v={{ filemtime(public_path('logo2.png')) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="apple-touch-icon" href="{{ asset('logo2.png') }}?v={{ filemtime(public_path('logo2.png')) }}">
    
    <!-- SEO Discovery Links -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    
    <!-- Performance DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Structured JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ProfessionalService",
      "name": "JuangDev",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('logo1.png') }}",
      "image": "{{ asset('logo1.png') }}",
      "description": "Studio teknologi pembuatan website profesional, aplikasi web kustom, toko online, dan sistem informasi digital.",
      "telephone": "{{ $settings['phone'] ?? '+6283852174877' }}",
      "email": "{{ $settings['email'] ?? 'hello@juangdev.com' }}",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "ID",
        "addressLocality": "Jakarta"
      },
      "priceRange": "Rp 99.000 - Rp 5.000.000+"
    }
    </script>
    
    <!-- Tailwind CSS Standalone CDN (No npm run dev needed!) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#0A1E5E',
                            dark: '#071542',
                            blue: '#2563EB',
                            lime: '#C7F236',
                            limeHover: '#b5dd2a',
                            bg: '#f8f9fc',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' },
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        }
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        marquee: 'marquee 35s linear infinite',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Custom scrollbars and styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="font-sans text-slate-800 bg-[#f8f9fc] antialiased selection:bg-[#C7F236] selection:text-[#0A1E5E]">

    <!-- Navbar Partial -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Partial -->
    @include('partials.footer')

    <!-- Floating Gemini AI Chatbot -->
    @include('partials.chatbot')

    <!-- Reusable Customer Auth Modal -->
    @include('partials.auth-modal')

    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
