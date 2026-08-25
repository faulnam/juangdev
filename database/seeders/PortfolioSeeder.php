<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Portfolio::truncate();

        $portfolios = [
            [
                'id' => 1,
                'slug' => 'property-management-app',
                'title' => 'Property Management App',
                'client' => 'Kos Pangeran & Landlord Group',
                'client_industry' => 'Property, Landlord, and Rental',
                'duration' => 'Januari 2024 - Juni 2024 (6 Bulan)',
                'category' => 'Aplikasi Web',
                'description' => 'Aplikasi pengelolaan properti lengkap untuk membantu pemilik properti mengelola kontrakan, apartemen, atau rumah kos dengan mudah dan efisien.',
                'overview' => 'Full management apps in website to help homeowner to manage their property like house-rental, apartment, or boarding house. Designed to be simple and easy to use, Kos Pangeran is a perfect solution for those who want to manage their property without any hassle.',
                'key_features' => [
                    'Property & Room Management',
                    'Member & Tenant Management',
                    'Payment & Transaction System',
                    'Reporting & Data Export'
                ],
                'gallery' => [
                    ['title' => 'Property & Room Management', 'image_url' => '/uploads/1781526251754-169158715-9.png'],
                    ['title' => 'Member & Tenant Management', 'image_url' => '/uploads/1781526353116-173345013-10.png'],
                    ['title' => 'Payment & Transaction System', 'image_url' => '/uploads/1781526529261-225644737-11.png'],
                    ['title' => 'Reporting & Data Export', 'image_url' => '/uploads/1781526616429-46906960-12.png'],
                ],
                'image_url' => '/uploads/1781526860110-978453413-15.png',
                'live_url' => 'https://demo.juangdev.com/property-app',
                'technologies' => ['Laravel', 'MongoDB', 'Bootstrap', 'Tailwind CSS'],
                'featured' => true,
                'is_boilerplate' => true,
                'sold_count' => 3,
                'package_tier' => 'Rekomendasi',
                'display_order' => 1,
            ],
            [
                'id' => 2,
                'slug' => 'healthcare-erp',
                'title' => 'Healthcare ERP System',
                'client' => 'PT Medika Nusantara',
                'client_industry' => 'Healthcare & Medical Industry',
                'duration' => 'Februari 2024 - Mei 2024 (4 Bulan)',
                'category' => 'Sistem Informasi',
                'description' => 'Sistem manajemen rumah sakit terpadu lengkap dengan pendaftaran pasien, penjadwalan janji medis, tagihan otomatis, dan manajemen apotek.',
                'overview' => 'Sistem informasi manajemen kesehatan (SIMRS) komprehensif yang mengintegrasikan alur kerja pendaftaran pasien, rekam medis elektronik, antrean dokter, farmasi, dan billing tagihan rumah sakit secara realtime.',
                'key_features' => [
                    'Manajemen Antrean & Rekam Medis Digital',
                    'Integrasi Sistem Farmasi & Inventaris Obat',
                    'Kasir & Billing Tagihan Pasien Otomatis',
                    'Dashboard Laporan Operasional Rumah Sakit'
                ],
                'gallery' => [
                    ['title' => 'Dashboard Rekam Medis', 'image_url' => '/uploads/1781526251754-169158715-9.png'],
                    ['title' => 'Manajemen Antrean Pasien', 'image_url' => '/uploads/1781526353116-173345013-10.png'],
                    ['title' => 'Sistem Farmasi & Kasir', 'image_url' => '/uploads/1781526703401-819330083-13.png'],
                    ['title' => 'Laporan Analitik Medis', 'image_url' => '/uploads/1781527358962-872605838-15.png'],
                ],
                'image_url' => '/uploads/1781526251754-169158715-9.png',
                'live_url' => 'https://demo.juangdev.com/healthcare',
                'technologies' => ['Laravel', 'Blade', 'MySQL', 'Tailwind CSS', 'Docker'],
                'featured' => true,
                'is_boilerplate' => false,
                'sold_count' => 0,
                'package_tier' => 'Premium',
                'display_order' => 2,
            ],
            [
                'id' => 3,
                'slug' => 'school-management-system',
                'title' => 'Sistem Informasi Sekolah Digital',
                'client' => 'Yayasan Pendidikan Maju',
                'client_industry' => 'Education & E-Learning',
                'duration' => 'Maret 2024 - Juli 2024 (5 Bulan)',
                'category' => 'Sistem Informasi',
                'description' => 'Platform manajemen sekolah terpadu yang menangani pendaftaran siswa, penilaian akademis, presensi digital, dan komunikasi orang tua.',
                'overview' => 'Platform sekolah pintar (Smart Campus) yang dirancang untuk mempermudah tata kelola akademis, pembagian nilai, pembayaran SPP online, serta portal komunikasi terpadu untuk orang tua dan guru.',
                'key_features' => [
                    'PPDB & Portal Akademik Siswa',
                    'Presensi Digital & Rekap Nilai Rapor',
                    'Payment Gateway Pembayaran SPP',
                    'Notifikasi WhatsApp Otomatis Ke Orang Tua'
                ],
                'gallery' => [
                    ['title' => 'Portal Nilai & Rapor', 'image_url' => '/uploads/1781526353116-173345013-10.png'],
                    ['title' => 'Dashboard Pendaftaran PPDB', 'image_url' => '/uploads/1781526529261-225644737-11.png'],
                    ['title' => 'Pembayaran SPP Online', 'image_url' => '/uploads/1781526616429-46906960-12.png'],
                    ['title' => 'Presensi & Kehadiran', 'image_url' => '/uploads/1781526786919-43514709-14.png'],
                ],
                'image_url' => '/uploads/1781526353116-173345013-10.png',
                'live_url' => 'https://demo.juangdev.com/school',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
                'featured' => true,
                'is_boilerplate' => false,
                'sold_count' => 0,
                'package_tier' => 'Rekomendasi',
                'display_order' => 3,
            ],
            [
                'id' => 4,
                'slug' => 'property-marketplace',
                'title' => 'Marketplace Properti Real Estate',
                'client' => 'PropertyKu Indonesia',
                'client_industry' => 'Real Estate & Brokerage',
                'duration' => 'Agustus 2023 - Desember 2023 (5 Bulan)',
                'category' => 'E-Commerce',
                'description' => 'Platform direktori properti real estate dengan fitur pencarian lanjutan, tur virtual 360, dan kalkulator KPR terintegrasi.',
                'overview' => 'Website portal jual beli properti modern yang menghubungkan agen properti, pengembang, dan pembeli. Dilengkapi peta interaktif Google Maps, filter harga, simulasi KPR, dan pesan langsung via WhatsApp.',
                'key_features' => [
                    'Filter Pencarian Properti Canggih',
                    'Simulasi Kalkulator KPR Interaktif',
                    'Integrasi Peta & Lokasi Properti',
                    'Manajemen Listing Agen & Developer'
                ],
                'gallery' => [
                    ['title' => 'Pencarian Properti Interaktif', 'image_url' => '/uploads/1781526529261-225644737-11.png'],
                    ['title' => 'Detail Halaman Unit Properti', 'image_url' => '/uploads/1781526860110-978453413-15.png'],
                    ['title' => 'Kalkulator KPR', 'image_url' => '/uploads/1781526941397-658466739-16.png'],
                    ['title' => 'Dashboard Agen Properti', 'image_url' => '/uploads/1781527358962-872605838-15.png'],
                ],
                'image_url' => '/uploads/1781526529261-225644737-11.png',
                'live_url' => 'https://demo.juangdev.com/property',
                'technologies' => ['Next.js', 'Laravel API', 'PostgreSQL', 'AWS'],
                'featured' => true,
                'is_boilerplate' => false,
                'sold_count' => 0,
                'package_tier' => 'Premium',
                'display_order' => 4,
            ],
            [
                'id' => 5,
                'slug' => 'restaurant-pos',
                'title' => 'Sistem Kasir Restoran (POS)',
                'client' => 'Kopi Senja Nusantara',
                'client_industry' => 'Food & Beverage (F&B)',
                'duration' => 'Oktober 2023 - Desember 2023 (3 Bulan)',
                'category' => 'Custom Web App',
                'description' => 'Sistem kasir digital dengan manajemen meja, tampilan dapur realtime, dan pelacakan pesanan otomatis.',
                'overview' => 'Solusi Point of Sale (POS) berbasis cloud untuk cafe dan restoran. Memungkinkan pelayan mengambil pesanan langsung via tablet/HP, dapur menerima tiket order secara realtime, dan manajemen memantau omset harian.',
                'key_features' => [
                    'Point of Sale (POS) Layar Sentuh',
                    'Kitchen Display System (KDS) Realtime',
                    'Manajemen Layout Meja Restoran',
                    'Laporan Penjualan & Stok Bahan Baku'
                ],
                'gallery' => [
                    ['title' => 'Layar Kasir POS', 'image_url' => '/uploads/1781526616429-46906960-12.png'],
                    ['title' => 'Manajemen Layout Meja', 'image_url' => '/uploads/1781526251754-169158715-9.png'],
                    ['title' => 'Laporan Omset Harian', 'image_url' => '/uploads/1781527358962-872605838-15.png'],
                    ['title' => 'Stok & Inventaris Bahan', 'image_url' => '/uploads/1781526703401-819330083-13.png'],
                ],
                'image_url' => '/uploads/1781526616429-46906960-12.png',
                'live_url' => 'https://demo.juangdev.com/pos',
                'technologies' => ['Laravel', 'Alpine.js', 'MySQL', 'WebSockets'],
                'featured' => false,
                'is_boilerplate' => true,
                'sold_count' => 2,
                'package_tier' => 'Basic',
                'display_order' => 5,
            ],
            [
                'id' => 6,
                'slug' => 'construction-dashboard',
                'title' => 'Dashboard Proyek Konstruksi',
                'client' => 'BuildPro Construction',
                'client_industry' => 'Construction & Engineering',
                'duration' => 'Januari 2024 - April 2024 (4 Bulan)',
                'category' => 'Custom Web App',
                'description' => 'Dashboard manajemen proyek konstruksi untuk alokasi sumber daya, manajemen material, dan pelacakan progres lapangan.',
                'overview' => 'Aplikasi monitoring proyek bangunan dan insfrastruktur. Membantu Manajer Proyek memantau kurva-S progres pengerjaan, penggunaan anggaran material, absensi pekerja lapangan, serta log pengiriman alat berat.',
                'key_features' => [
                    'Visualisasi Kurva-S Progres Proyek',
                    'Manajemen Budgeting & Material',
                    'Laporan Harian Pekerja Lapangan',
                    'Dokumentasi Foto Progres Berkelanjutan'
                ],
                'gallery' => [
                    ['title' => 'Overview Progres Proyek', 'image_url' => '/uploads/1781526703401-819330083-13.png'],
                    ['title' => 'Manajemen Anggaran Material', 'image_url' => '/uploads/1781527358962-872605838-15.png'],
                    ['title' => 'Absensi Pekerja Lapangan', 'image_url' => '/uploads/1781526353116-173345013-10.png'],
                    ['title' => 'Dokumentasi Progres', 'image_url' => '/uploads/1781526860110-978453413-15.png'],
                ],
                'image_url' => '/uploads/1781526703401-819330083-13.png',
                'live_url' => 'https://demo.juangdev.com/construction',
                'technologies' => ['Laravel', 'Tailwind CSS', 'Chart.js', 'Docker'],
                'featured' => false,
                'is_boilerplate' => false,
                'sold_count' => 0,
                'package_tier' => 'Premium',
                'display_order' => 6,
            ],
            [
                'id' => 7,
                'slug' => 'travel-booking-system',
                'title' => 'Sistem Reservasi Tour & Travel',
                'client' => 'Jelajah Tour & Travel',
                'client_industry' => 'Tourism & Travel Agency',
                'duration' => 'Mei 2024 - Juli 2024 (3 Bulan)',
                'category' => 'Landing Page',
                'description' => 'Platform biro perjalanan wisata online dengan pemesanan hotel, tiket pesawat, paket tour, dan integrasi pembayaran.',
                'overview' => 'Website portal wisata interaktif yang menyajikan paket liburan domestik dan internasional. Pengunjung dapat memilih tanggal, jumlah orang, melakukan booking instan, dan membayar melalui QRIS atau transfer bank.',
                'key_features' => [
                    'Katalog Paket Tour Interaktif',
                    'Form Booking & Kalender Ketersediaan',
                    'Integrasi Gateway Pembayaran QRIS',
                    'E-Voucher & Tiket PDF Otomatis'
                ],
                'gallery' => [
                    ['title' => 'Halaman Katalog Paket Tour', 'image_url' => '/uploads/1781526786919-43514709-14.png'],
                    ['title' => 'Form Booking & Tanggal', 'image_url' => '/uploads/1781526529261-225644737-11.png'],
                    ['title' => 'Integrasi Checkout QRIS', 'image_url' => '/uploads/1781526941397-658466739-16.png'],
                    ['title' => 'Generasi E-Voucher PDF', 'image_url' => '/uploads/1781526616429-46906960-12.png'],
                ],
                'image_url' => '/uploads/1781526786919-43514709-14.png',
                'live_url' => 'https://demo.juangdev.com/travel',
                'technologies' => ['Laravel', 'MySQL', 'Midtrans', 'Tailwind CSS'],
                'featured' => true,
                'is_boilerplate' => true,
                'sold_count' => 1,
                'package_tier' => 'Basic',
                'display_order' => 7,
            ],
            [
                'id' => 8,
                'slug' => 'ecommerce-platform',
                'title' => 'Platform E-Commerce Fashion',
                'client' => 'Fashion Outlet ID',
                'client_industry' => 'Retail & E-Commerce',
                'duration' => 'Februari 2024 - Juni 2024 (5 Bulan)',
                'category' => 'E-Commerce',
                'description' => 'Toko online multi-vendor dengan manajemen stok barang, pemrosesan pesanan otomatis, dan dashboard analitik penjualan.',
                'overview' => 'Toko online fashion berperforma tinggi yang dibangun untuk pengalaman belanja mulus di smartphone. Dilengkapi keranjang belanja, voucher diskon, hitung ongkir otomatis JNE/TIKI/POS, dan notifikasi resi pengiriman.',
                'key_features' => [
                    'Katalog Produk & Variansi Ukuran/Warna',
                    'Cek Ongkir Otomatis Kurir Indonesia',
                    'Payment Gateway (QRIS, VA, E-Wallet)',
                    'Dashboard Admin Penjualan & Resi'
                ],
                'gallery' => [
                    ['title' => 'Katalog Produk Fashion', 'image_url' => '/uploads/1781526860110-978453413-15.png'],
                    ['title' => 'Keranjang & Checkout', 'image_url' => '/uploads/1781526786919-43514709-14.png'],
                    ['title' => 'Integrasi Payment Gateway', 'image_url' => '/uploads/1781527358962-872605838-15.png'],
                    ['title' => 'Laporan Penjualan Toko', 'image_url' => '/uploads/1781526251754-169158715-9.png'],
                ],
                'image_url' => '/uploads/1781526941397-658466739-16.png',
                'live_url' => 'https://demo.juangdev.com/store',
                'technologies' => ['Laravel', 'Blade', 'MySQL', 'Midtrans'],
                'featured' => true,
                'is_boilerplate' => false,
                'sold_count' => 0,
                'package_tier' => 'Rekomendasi',
                'display_order' => 8,
            ],
        ];

        foreach ($portfolios as $port) {
            Portfolio::create($port);
        }
    }
}
