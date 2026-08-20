<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\DesignTier;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        Admin::truncate();
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'name' => 'Administrator JuangDev',
            'role' => 'admin',
        ]);

        // 2. Site Settings
        SiteSetting::truncate();
        $settings = [
            'site_name' => 'JuangDev',
            'site_tagline' => 'Jasa Pembuatan Website & Aplikasi Web Profesional',
            'site_description' => 'Solusi pembuatan website murah berkualitas tinggi dengan teknologi modern.',
            'whatsapp_number' => '6283852174877',
            'email' => 'halo@juangdev.com',
            'phone' => '+62 812-3456-7890',
            'address' => 'Jakarta, Indonesia',
            'working_hours' => 'Senin - Sabtu: 09:00 - 18:00 WIB',
            'instagram_url' => 'https://instagram.com/juangdev',
            'x_url' => 'https://x.com/juangdev',
            'threads_url' => 'https://threads.net/@juangdev',
            'github_url' => 'https://github.com/juangdev',
            'linkedin_url' => 'https://linkedin.com/company/juangdev',
            'tiktok_url' => '',
        ];
        foreach ($settings as $k => $v) {
            SiteSetting::create(['key' => $k, 'value' => $v]);
        }

        // 3. Services
        Service::truncate();
        $services = [
            [
                'id' => 1,
                'slug' => 'landing-page',
                'name' => 'Landing Page',
                'tagline' => 'Maksimalkan Konversi Penjualan',
                'description' => 'Halaman web tunggal yang dioptimasi secara khusus untuk memaksimalkan konversi pemasaran bisnis Anda.',
                'icon' => 'globe',
                'base_price' => 99000,
                'starting_price' => '99K',
                'delivery_time' => '2-3 Hari',
                'popular' => false,
                'features' => ['Desain Responsif', 'SEO Basic', 'Mobile Friendly', 'Call to Action Optimal', 'Setup Domain & Hosting'],
                'technologies' => ['HTML5', 'Tailwind CSS', 'Alpine.js', 'Next.js'],
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'slug' => 'company-profile',
                'name' => 'Company Profile',
                'tagline' => 'Tingkatkan Kredibilitas Bisnis',
                'description' => 'Website profesional dan elegan untuk membangun kredibilitas serta menampilkan identitas bisnis Anda ke publik.',
                'icon' => 'monitor',
                'base_price' => 199000,
                'starting_price' => '199K',
                'delivery_time' => '3-5 Hari',
                'popular' => true,
                'features' => ['Hingga 5 Halaman', 'Galeri Portfolio', 'Form Kontak Interaktif', 'SEO Friendly', 'Integrasi Google Maps'],
                'technologies' => ['Laravel', 'Blade', 'Tailwind CSS', 'MySQL'],
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'slug' => 'ecommerce',
                'name' => 'E-Commerce',
                'tagline' => 'Jual Produk Online 24/7',
                'description' => 'Toko online modern dengan sistem belanja terstruktur, lengkap dengan katalog produk dan checkout.',
                'icon' => 'shopping-bag',
                'base_price' => 399000,
                'starting_price' => '399K',
                'delivery_time' => '5-7 Hari',
                'popular' => false,
                'features' => ['Katalog Produk', 'Keranjang Belanja', 'Integrasi WA Checkout', 'Payment Gateway (Opsional)', 'Dashboard Penjualan'],
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Midtrans'],
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'slug' => 'sistem-informasi',
                'name' => 'Sistem Informasi',
                'tagline' => 'Digitalisasi Operasional Bisnis',
                'description' => 'Sistem digitalisasi pendataan dan pelaporan untuk mempermudah operasional internal perusahaan Anda.',
                'icon' => 'bot',
                'base_price' => 499000,
                'starting_price' => '499K',
                'delivery_time' => '7-14 Hari',
                'popular' => false,
                'features' => ['Manajemen Data Terpadu', 'Dashboard Analitik Visual', 'Export Laporan PDF/Excel', 'Hak Akses Multi-Level', 'Cloud Backup'],
                'technologies' => ['Laravel', 'Alpine.js', 'MySQL', 'Tailwind CSS'],
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'slug' => 'custom-app',
                'name' => 'Custom Web App',
                'tagline' => 'Aplikasi Sesuai Kebutuhan Spesifik',
                'description' => 'Pengembangan aplikasi web khusus dengan fitur kompleks yang dirancang mengikuti alur bisnis unik Anda.',
                'icon' => 'palette',
                'base_price' => 999000,
                'starting_price' => '999K',
                'delivery_time' => '14-30 Hari',
                'popular' => false,
                'features' => ['Desain UI/UX Custom Eksklusif', 'API Integration & Webhook', 'Sistem Login & Role Bertingkat', 'Skalabilitas Tinggi', 'Garansi & Maintenance'],
                'technologies' => ['Laravel', 'Next.js', 'Node.js', 'PostgreSQL', 'Docker'],
                'display_order' => 5,
                'is_active' => true,
            ],
        ];
        foreach ($services as $srv) {
            Service::create($srv);
        }

        // 4. Service Features (Add-ons for Estimator)
        ServiceFeature::truncate();
        $features = [
            [
                'id' => 1,
                'title' => 'Optimasi SEO Lanjutan',
                'description' => 'Optimasi mesin pencari mendalam agar website mudah ditemukan di halaman utama Google.',
                'category' => 'addon',
                'price' => 500000,
                'popular' => true,
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'title' => 'Payment Gateway Otomatis',
                'description' => 'Integrasi pembayaran otomatis QRIS, Transfer Bank, Kartu Kredit, dan E-Wallet.',
                'category' => 'addon',
                'price' => 800000,
                'popular' => true,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'title' => 'Dukungan Multi-Bahasa',
                'description' => 'Dukungan multi-bahasa (Bahasa Indonesia & Bahasa Inggris) dengan pemindah bahasa yang mudah.',
                'category' => 'addon',
                'price' => 1200000,
                'popular' => false,
                'display_order' => 3,
                'is_active' => true,
            ],
        ];
        foreach ($features as $f) {
            ServiceFeature::create($f);
        }

        // 5. Design Tiers (for Estimator)
        DesignTier::truncate();
        $tiers = [
            [
                'id' => 1,
                'name' => 'Putih Bersih (Standar)',
                'tagline' => 'Minimalis & Bersih',
                'description' => 'Tampilan profesional yang bersih, modern, dan nyaman dibaca pengunjung.',
                'price' => 0,
                'badge' => 'Termasuk',
                'features' => ['Tata Letak Bersih', 'Tipografi Standar', 'Optimasi Seluler'],
                'is_popular' => false,
                'display_order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Biru Gelap (Pro)',
                'tagline' => 'Mode Gelap Elegan',
                'description' => 'Desain gelap elegan bernuansa teknologi modern dengan aksen biru profesional.',
                'price' => 1500000,
                'badge' => 'Populer',
                'features' => ['Tampilan Mode Gelap', 'Mikro-Interaksi', 'Grafis Kustom', 'Tipografi Premium'],
                'is_popular' => true,
                'display_order' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Hijau Neon (Premium)',
                'tagline' => 'Futuristik & Modern',
                'description' => 'Desain futuristik dengan efek glassmorphism, glowing neon, dan animasi halus berkualitas tinggi.',
                'price' => 3500000,
                'badge' => 'Premium',
                'features' => ['Antarmuka Futuristik', 'Efek Glassmorphism', 'Gerakan Interaktif GSAP', 'Ikon Kustom Eksklusif'],
                'is_popular' => false,
                'display_order' => 3,
            ],
        ];
        foreach ($tiers as $dt) {
            DesignTier::create($dt);
        }

        // 6. Pricing Plans (All 15 plans across 5 categories)
        PricingPlan::truncate();
        $plans = [
            // Landing Page
            [
                'id' => 1,
                'category' => 'landing-page',
                'name' => 'Starter',
                'badge' => null,
                'price' => '99k',
                'period' => 'proyek',
                'description' => 'Sempurna untuk validasi ide & produk tunggal',
                'features' => ['1 Halaman Landing Page', 'Desain Template Premium', 'Mobile Responsive', 'Tombol WhatsApp', 'Waktu Pengerjaan 2 Hari'],
                'not_included' => ['Custom Desain dari Nol', 'SEO Advanced', 'Multi Bahasa'],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=landing-starter',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'category' => 'landing-page',
                'name' => 'Growth',
                'badge' => 'PALING POPULER',
                'price' => '299k',
                'period' => 'proyek',
                'description' => 'Pilihan terbaik untuk konversi marketing',
                'features' => ['Desain UI/UX Custom', 'Copywriting Persuasif', 'Integrasi Google Analytics', 'Setup Domain & Hosting', 'Waktu Pengerjaan 5 Hari'],
                'not_included' => ['Multi Bahasa'],
                'popular' => true,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=landing-growth',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'category' => 'landing-page',
                'name' => 'Scale',
                'badge' => null,
                'price' => '499k',
                'period' => 'proyek',
                'description' => 'Desain kompleks dengan animasi interaktif',
                'features' => ['Animasi Interaktif (GSAP)', 'A/B Testing Setup', 'Integrasi Email Marketing', 'Prioritas Support', 'Waktu Pengerjaan 7 Hari'],
                'not_included' => [],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=landing-scale',
                'display_order' => 3,
                'is_active' => true,
            ],

            // Company Profile
            [
                'id' => 4,
                'category' => 'company-profile',
                'name' => 'Basic',
                'badge' => null,
                'price' => '199k',
                'period' => 'proyek',
                'description' => 'Profil bisnis profesional & elegan',
                'features' => ['Maksimal 3 Halaman', 'Desain Responsif', 'Form Kontak', 'Galeri Foto', 'Waktu Pengerjaan 3 Hari'],
                'not_included' => ['CMS Admin Update', 'Multi Bahasa'],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=company-basic',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'category' => 'company-profile',
                'name' => 'Professional',
                'badge' => 'REKOMENDASI',
                'price' => '499k',
                'period' => 'proyek',
                'description' => 'Tampil meyakinkan di mata klien',
                'features' => ['Hingga 7 Halaman', 'Desain UI/UX Custom', 'CMS untuk Update Konten', 'SEO Basic Setup', 'Waktu Pengerjaan 7 Hari'],
                'not_included' => ['Multi Bahasa'],
                'popular' => true,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=company-professional',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'id' => 6,
                'category' => 'company-profile',
                'name' => 'Corporate',
                'badge' => null,
                'price' => '899k',
                'period' => 'proyek',
                'description' => 'Untuk perusahaan berskala besar',
                'features' => ['Halaman Tak Terbatas', 'Multi-bahasa (Bilingual)', 'Portal Karir', 'Integrasi Sistem Internal', 'Waktu Pengerjaan 14 Hari'],
                'not_included' => [],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=company-corporate',
                'display_order' => 6,
                'is_active' => true,
            ],

            // E-Commerce
            [
                'id' => 7,
                'category' => 'ecommerce',
                'name' => 'Basic Store',
                'badge' => null,
                'price' => '399k',
                'period' => 'proyek',
                'description' => 'Mulai jualan online dengan cepat',
                'features' => ['Katalog hingga 50 Produk', 'Keranjang Belanja', 'Integrasi WhatsApp Checkout', 'Desain Mobile Friendly'],
                'not_included' => ['Payment Gateway Otomatis', 'Hitung Ongkir Otomatis'],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=ecommerce-basic',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'id' => 8,
                'category' => 'ecommerce',
                'name' => 'Pro Store',
                'badge' => 'BEST SELLER',
                'price' => '799k',
                'period' => 'proyek',
                'description' => 'Toko online otomatis & scalable',
                'features' => ['Produk Tak Terbatas', 'Payment Gateway Integrasi', 'Perhitungan Ongkir Otomatis', 'Manajemen Inventaris', 'Dashboard Analitik'],
                'not_included' => ['Multi Vendor'],
                'popular' => true,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=ecommerce-pro',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'id' => 9,
                'category' => 'ecommerce',
                'name' => 'Marketplace',
                'badge' => null,
                'price' => '999k',
                'period' => 'proyek',
                'description' => 'Sistem toko online kompleks & custom',
                'features' => ['Multi-vendor Setup', 'Sistem Poin/Diskon', 'Integrasi POS Internal', 'Aplikasi Mobile WebView', 'Dukungan Prioritas 24/7'],
                'not_included' => [],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=ecommerce-marketplace',
                'display_order' => 9,
                'is_active' => true,
            ],

            // Sistem Informasi
            [
                'id' => 10,
                'category' => 'sistem-informasi',
                'name' => 'Basic App',
                'badge' => null,
                'price' => '499k',
                'period' => 'proyek',
                'description' => 'Aplikasi web internal sederhana',
                'features' => ['Modul Login/Register', 'CRUD Data Basic', 'Export PDF/Excel', 'Database Setup'],
                'not_included' => ['Multi-Role Bertingkat', 'API External'],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=sistem-basic',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'id' => 11,
                'category' => 'sistem-informasi',
                'name' => 'Pro App',
                'badge' => 'POPULER',
                'price' => '899k',
                'period' => 'proyek',
                'description' => 'Sistem operasional lengkap & aman',
                'features' => ['Multi-role Akses (Admin/User)', 'Dashboard Analitik', 'Notifikasi Email/WA', 'API Integration', 'Setup Cloud Server'],
                'not_included' => ['Microservices SLA'],
                'popular' => true,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=sistem-pro',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'id' => 12,
                'category' => 'sistem-informasi',
                'name' => 'Enterprise App',
                'badge' => null,
                'price' => '999k+',
                'period' => 'proyek',
                'description' => 'Sistem ERP/CRM skala perusahaan',
                'features' => ['Modul Tak Terbatas', 'Arsitektur Microservices', 'Keamanan Tingkat Tinggi', 'SLA Guarantee 99.9%', 'Maintenance 6 Bulan'],
                'not_included' => [],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=sistem-enterprise',
                'display_order' => 12,
                'is_active' => true,
            ],

            // Custom Web App
            [
                'id' => 13,
                'category' => 'custom-app',
                'name' => 'Starter',
                'badge' => null,
                'price' => '999k',
                'period' => 'proyek',
                'description' => 'Aplikasi web fungsional untuk bisnis',
                'features' => ['Aplikasi React/Laravel', 'Desain UI/UX Custom', 'API Integration', 'Basic Admin Panel'],
                'not_included' => ['Realtime WebSockets', 'Dedicated Microservices'],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=custom-starter',
                'display_order' => 13,
                'is_active' => true,
            ],
            [
                'id' => 14,
                'category' => 'custom-app',
                'name' => 'Pro',
                'badge' => 'UNGGULAN',
                'price' => '1.499k',
                'period' => 'proyek',
                'description' => 'Aplikasi kompleks dengan sistem canggih',
                'features' => ['Sistem Autentikasi Kompleks', 'Realtime Features', 'Dashboard Analytics', 'Cloud Server Setup'],
                'not_included' => [],
                'popular' => true,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=custom-pro',
                'display_order' => 14,
                'is_active' => true,
            ],
            [
                'id' => 15,
                'category' => 'custom-app',
                'name' => 'Enterprise',
                'badge' => null,
                'price' => '2.999k',
                'period' => 'proyek',
                'description' => 'Solusi enterprise berskala besar',
                'features' => ['Arsitektur Skalabel', 'SLA Guarantee 99.9%', 'Prioritas Support', 'Maintenance Mingguan'],
                'not_included' => [],
                'popular' => false,
                'cta_text' => 'Pilih Paket',
                'cta_href' => '/contact?plan=custom-enterprise',
                'display_order' => 15,
                'is_active' => true,
            ],
        ];
        foreach ($plans as $p) {
            PricingPlan::create($p);
        }

        // 7. Portfolios (8 items)
        Portfolio::truncate();
        $portfolios = [
            [
                'id' => 1,
                'slug' => 'healthcare-erp',
                'title' => 'Healthcare ERP',
                'client' => 'PT Medika Nusantara',
                'category' => 'Sistem Informasi',
                'description' => 'Sistem manajemen rumah sakit terpadu lengkap dengan pendaftaran pasien, penjadwalan janji medis, tagihan otomatis, dan manajemen apotek.',
                'image_url' => '/uploads/1781526251754-169158715-9.png',
                'live_url' => 'https://demo.juangdev.com/healthcare',
                'technologies' => ['Laravel', 'Blade', 'MySQL', 'Tailwind CSS', 'Docker'],
                'featured' => true,
                'display_order' => 1,
            ],
            [
                'id' => 2,
                'slug' => 'school-management-system',
                'title' => 'Sistem Informasi Sekolah',
                'client' => 'Yayasan Pendidikan Maju',
                'category' => 'Sistem Informasi',
                'description' => 'Platform manajemen sekolah terpadu yang menangani pendaftaran siswa, penilaian akademis, presensi digital, dan komunikasi orang tua.',
                'image_url' => '/uploads/1781526353116-173345013-10.png',
                'live_url' => 'https://demo.juangdev.com/school',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
                'featured' => true,
                'display_order' => 2,
            ],
            [
                'id' => 3,
                'slug' => 'property-marketplace',
                'title' => 'Marketplace Properti',
                'client' => 'PropertyKu Indonesia',
                'category' => 'E-Commerce',
                'description' => 'Platform direktori properti real estate dengan fitur pencarian lanjutan, tur virtual 360, dan kalkulator KPR terintegrasi.',
                'image_url' => '/uploads/1781526529261-225644737-11.png',
                'live_url' => 'https://demo.juangdev.com/property',
                'technologies' => ['Next.js', 'Laravel API', 'PostgreSQL', 'AWS'],
                'featured' => true,
                'display_order' => 3,
            ],
            [
                'id' => 4,
                'slug' => 'restaurant-pos',
                'title' => 'Sistem Kasir Restoran (POS)',
                'client' => 'Kopi Senja Nusantara',
                'category' => 'Company Profile',
                'description' => 'Sistem kasir digital dengan manajemen meja, tampilan dapur realtime, dan pelacakan pesanan otomatis.',
                'image_url' => '/uploads/1781526616429-46906960-12.png',
                'live_url' => 'https://demo.juangdev.com/pos',
                'technologies' => ['Laravel', 'Alpine.js', 'MySQL', 'WebSockets'],
                'featured' => false,
                'display_order' => 4,
            ],
            [
                'id' => 5,
                'slug' => 'construction-dashboard',
                'title' => 'Dashboard Proyek Konstruksi',
                'client' => 'BuildPro Construction',
                'category' => 'Custom Web App',
                'description' => 'Dashboard manajemen proyek konstruksi untuk alokasi sumber daya, manajemen material, dan pelacakan progres lapangan.',
                'image_url' => '/uploads/1781526703401-819330083-13.png',
                'live_url' => 'https://demo.juangdev.com/construction',
                'technologies' => ['Laravel', 'Tailwind CSS', 'Chart.js', 'Docker'],
                'featured' => false,
                'display_order' => 5,
            ],
            [
                'id' => 6,
                'slug' => 'travel-booking-system',
                'title' => 'Sistem Reservasi Travel',
                'client' => 'Jelajah Tour & Travel',
                'category' => 'Landing Page',
                'description' => 'Platform biro perjalanan wisata online dengan pemesanan hotel, tiket pesawat, paket tour, dan integrasi pembayaran.',
                'image_url' => '/uploads/1781526786919-43514709-14.png',
                'live_url' => 'https://demo.juangdev.com/travel',
                'technologies' => ['Laravel', 'MySQL', 'Midtrans', 'Tailwind CSS'],
                'featured' => true,
                'display_order' => 6,
            ],
            [
                'id' => 7,
                'slug' => 'financial-dashboard',
                'title' => 'Dashboard Analitik Keuangan',
                'client' => 'FinTrack Indonesia',
                'category' => 'Custom Web App',
                'description' => 'Dashboard analitik keuangan realtime dengan grafik visual interaktif, pelacakan portofolio investasi, dan data pasar.',
                'image_url' => '/uploads/1781527358962-872605838-15.png',
                'live_url' => 'https://demo.juangdev.com/financial',
                'technologies' => ['Laravel', 'Python Analytics', 'PostgreSQL', 'Redis'],
                'featured' => false,
                'display_order' => 7,
            ],
            [
                'id' => 8,
                'slug' => 'ecommerce-platform',
                'title' => 'Platform E-Commerce Fashion',
                'client' => 'Fashion Outlet ID',
                'category' => 'E-Commerce',
                'description' => 'Toko online multi-vendor dengan manajemen stok barang, pemrosesan pesanan otomatis, dan dashboard analitik penjualan.',
                'image_url' => '/uploads/1781526941397-658466739-16.png',
                'live_url' => 'https://demo.juangdev.com/store',
                'technologies' => ['Laravel', 'Blade', 'MySQL', 'Midtrans'],
                'featured' => true,
                'display_order' => 8,
            ],
        ];
        foreach ($portfolios as $port) {
            Portfolio::create($port);
        }

        // 8. Testimonials (8 items)
        Testimonial::truncate();
        $testimonials = [
            [
                'id' => 1,
                'name' => 'Ahmad Rizki',
                'role' => 'CEO di PT Nusantara Digital',
                'company' => 'PT Nusantara Digital',
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'content' => 'JuangDev mengubah total kehadiran digital bisnis kami. Website baru ini meningkatkan leads hingga 300% dalam waktu 3 bulan saja. Ketelitian dan kualitas pengerjaannya sangat luar biasa.',
                'rating' => 5,
                'featured' => true,
                'display_order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Sarah Putri',
                'role' => 'Pendiri TechStart Indonesia',
                'company' => 'TechStart Indonesia',
                'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
                'content' => 'Dari tahap konsep hingga rilis resmi, JuangDev berhasil meluncurkan MVP kami dalam waktu singkat. Kualitas kodenya sangat rapi dan siap dikembangkan lebih jauh.',
                'rating' => 5,
                'featured' => true,
                'display_order' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Budi Santoso',
                'role' => 'CTO di Maju Bersama Group',
                'company' => 'Maju Bersama Group',
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
                'content' => 'Solusi sistem informasi dari JuangDev mengoptimalkan operasional internal kami. Kami menghemat hingga 40% biaya operasional pada tahun pertama.',
                'rating' => 5,
                'featured' => true,
                'display_order' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Diana Kusuma',
                'role' => 'Direktur Klinik Sehat Selalu',
                'company' => 'Klinik Sehat Selalu',
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                'content' => 'Sistem manajemen klinik yang dibangun JuangDev sangat intuitif dan andal. Staf kami mudah menggunakannya dan kepuasan pasien meningkat drastis.',
                'rating' => 5,
                'featured' => true,
                'display_order' => 4,
            ],
            [
                'id' => 5,
                'name' => 'Reza Firmansyah',
                'role' => 'Co-Founder PropertyKu',
                'company' => 'PropertyKu',
                'avatar_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80',
                'content' => 'JuangDev membangun platform direktori properti kami dari nol. Platform ini mampu menangani ribuan listing dengan cepat dan lancar.',
                'rating' => 5,
                'featured' => false,
                'display_order' => 5,
            ],
            [
                'id' => 6,
                'name' => 'Linda Maharani',
                'role' => 'Pemilik Fashion Outlet ID',
                'company' => 'Fashion Outlet ID',
                'avatar_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80',
                'content' => 'Toko online dari JuangDev mendongkrak penjualan kami hingga 250%. Alur checkout sangat mudah dan dashboard admin membuat pengelolaan toko jadi menyenangkan.',
                'rating' => 5,
                'featured' => true,
                'display_order' => 6,
            ],
            [
                'id' => 7,
                'name' => 'Hendra Wijaya',
                'role' => 'Project Manager di BuildPro',
                'company' => 'BuildPro Construction',
                'avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=150&auto=format&fit=crop&q=80',
                'content' => 'Dashboard manajemen proyek membantu kami memantau 50+ lokasi proyek secara realtime. Sangat membantu efisiensi tim lapangan.',
                'rating' => 5,
                'featured' => false,
                'display_order' => 7,
            ],
            [
                'id' => 8,
                'name' => 'Maya Anggraini',
                'role' => 'CEO di EduTech Solutions',
                'company' => 'EduTech Solutions',
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
                'content' => 'JuangDev membangun sistem manajemen sekolah kami yang kini melayani lebih dari 10.000 siswa. Pemahaman mereka tentang sektor pendidikan sangat mengesankan.',
                'rating' => 5,
                'featured' => false,
                'display_order' => 8,
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }

        // 9. Blogs (3 items)
        Blog::truncate();
        $blogs = [
            [
                'id' => 1,
                'title' => 'Mengapa Laravel & PHP Modern adalah Solusi Web Terbaik di 2026',
                'slug' => 'laravel-future-2026',
                'excerpt' => 'Pelajari bagaimana Laravel dengan Blade, Alpine.js, dan Tailwind CSS menghadirkan performa cepat, keamanan tinggi, dan biaya perawatan hemat untuk bisnis Anda.',
                'content' => 'Dalam era digital saat ini, kecepatan dan keandalan website merupakan faktor penentu keberhasilan bisnis. Laravel 11 hadir dengan arsitektur yang sangat efisien, integrasi database MySQL yang solid, serta ekosistem yang matang untuk mendukung pertumbuhan bisnis mulai dari startup hingga korporasi besar.',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&auto=format&fit=crop&q=80',
                'category' => 'Technology',
                'author' => 'Tim JuangDev',
                'read_time' => '5 mnt baca',
                'published_at' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'id' => 2,
                'title' => '10 Fitur Wajib yang Harus Ada di Website E-Commerce',
                'slug' => 'ecommerce-essential-features',
                'excerpt' => 'Dari alur checkout instan hingga rekomendasi produk — ketahui fitur kunci yang membuat toko online sukses menghasilkan transaksi tinggi.',
                'content' => 'Membangun toko online tidak hanya sekedar memajang foto produk. Diperlukan alur checkout yang mulus, integrasi gateway pembayaran otomatis, notifikasi WhatsApp realtime, serta kecepatan loading halaman yang optimal agar pengunjung nyaman bertransaksi.',
                'image_url' => 'https://images.unsplash.com/photo-1556742049-0a67e5572263?w=600&auto=format&fit=crop&q=80',
                'category' => 'E-Commerce',
                'author' => 'Tim JuangDev',
                'read_time' => '7 mnt baca',
                'published_at' => now()->subDays(10),
                'is_published' => true,
            ],
            [
                'id' => 3,
                'title' => 'Bagaimana AI Mengubah Otomatisasi Bisnis & Layanan Pelanggan',
                'slug' => 'ai-business-automation',
                'excerpt' => 'Jelajahi bagaimana chatbot berbasis AI dan analitik prediktif membantu bisnis menghemat waktu dan biaya operasional.',
                'content' => 'Customer service berbasis AI kini mampu menjawab pertanyaan pelanggan secara cerdas dan kontekstual 24 jam nonstop. Dengan integrasi Google Gemini AI pada website JuangDev, konsultasi kebutuhan website kini dapat dilakukan secara instan.',
                'image_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&auto=format&fit=crop&q=80',
                'category' => 'AI & Automation',
                'author' => 'Tim JuangDev',
                'read_time' => '6 mnt baca',
                'published_at' => now()->subDays(15),
                'is_published' => true,
            ],
        ];
        foreach ($blogs as $b) {
            Blog::create($b);
        }
    }
}
