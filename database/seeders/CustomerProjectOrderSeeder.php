<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Portfolio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerProjectOrderSeeder extends Seeder
{
    /**
     * Run the database seeds for 17 dummy customer project orders.
     */
    public function run(): void
    {
        // 17 Dummy Customers with realistic Indonesian profiles
        $dummyCustomers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso88@gmail.com',
                'phone' => '081289123451',
                'company' => 'Santoso Property Group',
                'notes' => 'Perlu penyesuaian alur pembayaran sewa bulanan dan integrasi WhatsApp gateway.',
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@techcorp.id',
                'phone' => '085719827362',
                'company' => 'PT Hidayat Solusi Digital',
                'notes' => 'Sistem rekam medis dan antrean pasien klinik untuk 3 cabang baru.',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@aurafashion.co.id',
                'phone' => '081399881122',
                'company' => 'Aura Fashion Boutique',
                'notes' => 'Toko online terintegrasi cek ongkir otomatis dan payment gateway QRIS.',
            ],
            [
                'name' => 'Agus Prasetyo',
                'email' => 'agus.prasetyo@bengkeljaya.com',
                'phone' => '082133445566',
                'company' => 'Bengkel Jaya Motor',
                'notes' => 'Aplikasi POS kasir bengkel dengan tracking histori servis berkala kendaraan pelanggan.',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@berkahlogistik.id',
                'phone' => '085677889900',
                'company' => 'Berkah Logistik Express',
                'notes' => 'Website company profile modern dengan fitur cek resi tracking paket pengiriman.',
            ],
            [
                'name' => 'Fajar Pratama',
                'email' => 'fajar.pratama@kulinerindonesia.com',
                'phone' => '081277112233',
                'company' => 'Rasa Nusantara Resto',
                'notes' => 'Sistem manajemen resto multi-cabang & menu QR digital di meja makan.',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@pradanapartner.com',
                'phone' => '081388776655',
                'company' => 'Pradana & Partners Law Firm',
                'notes' => 'Website portal konsultasi hukum elegan dengan formulir appointment online.',
            ],
            [
                'name' => 'Andi Firmansyah',
                'email' => 'andi.firmansyah@tournesia.id',
                'phone' => '085811223344',
                'company' => 'Tournesia Travel & Tour',
                'notes' => 'Website pemesanan paket wisata domestik lengkap dengan payment gateway.',
            ],
            [
                'name' => 'Maya Safitri',
                'email' => 'maya.safitri@dentalcare.com',
                'phone' => '081299334455',
                'company' => 'SmileCare Dental Clinic',
                'notes' => 'Aplikasi reservasi dokter gigi dan rekam medis riwayat tindakan pasien.',
            ],
            [
                'name' => 'Rizky Ramadhani',
                'email' => 'rizky.rama@creativehub.id',
                'phone' => '087811992288',
                'company' => 'CreativeHub Media',
                'notes' => 'Company profile agency kreatif dengan portofolio video interaktif dan blog.',
            ],
            [
                'name' => 'Eko Saputra',
                'email' => 'eko.saputra@sinergigroup.co.id',
                'phone' => '081234998877',
                'company' => 'PT Sinergi Dinamika Abadi',
                'notes' => 'Portal korporat holding company untuk presentasi investor dan tender instansi.',
            ],
            [
                'name' => 'Dina Kartika',
                'email' => 'dina.kartika@glowbeauty.id',
                'phone' => '085788990011',
                'company' => 'Glow Beauty Skin Clinic',
                'notes' => 'Katalog produk skincare + booking konsultasi dokter estetika bersertifikat.',
            ],
            [
                'name' => 'Wahyu Setiawan',
                'email' => 'wahyu.setiawan@autocare.com',
                'phone' => '082211556677',
                'company' => 'AutoCare Service Center',
                'notes' => 'Sistem inventaris sparepart dan reminder servis otomatis via WA bot.',
            ],
            [
                'name' => 'Anisa Permata',
                'email' => 'anisa.permata@edukreasi.id',
                'phone' => '081377889922',
                'company' => 'EduKreasi Nusantara',
                'notes' => 'Landing page program kursus online persiapan karir digital marketing.',
            ],
            [
                'name' => 'Bayu Nugroho',
                'email' => 'bayu.nugroho@propertindo.com',
                'phone' => '085612347890',
                'company' => 'Propertindo Cluster Mandiri',
                'notes' => 'Website katalog perumahan real estate dengan simulasi KPR interaktif.',
            ],
            [
                'name' => 'Farah Nabila',
                'email' => 'farah.nabila@sweetbake.id',
                'phone' => '087799112233',
                'company' => 'SweetBake Artisan Bakery',
                'notes' => 'Toko online pre-order kue artisan & hampers lebaran dengan custom packaging.',
            ],
            [
                'name' => 'Dimas Anggara',
                'email' => 'dimas.anggara@solusitech.com',
                'phone' => '081288443322',
                'company' => 'PT Solusi Teknologi Bersama',
                'notes' => 'Sistem tiket support internal perusahaan dan monitoring SLA layanan IT.',
            ],
        ];

        // Ensure we have available portfolios to associate
        $portfolios = Portfolio::all();

        // 17 Order configuration specifications (Randomized statuses: DP vs Selesai)
        // 9 orders DP Paid (In Progress), 8 orders Selesai / Fully Paid (Completed)
        $orderConfigs = [
            // 1. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 1999000,
                'discount_amount' => 150000,
                'days_ago' => 5,
                'tier' => 'Rekomendasi',
                'service' => 'Aplikasi Web',
            ],
            // 2. Selesai (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 2499000,
                'discount_amount' => 0,
                'days_ago' => 45,
                'tier' => 'Premium',
                'service' => 'Sistem Informasi',
            ],
            // 3. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 1299000,
                'discount_amount' => 100000,
                'days_ago' => 8,
                'tier' => 'Basic',
                'service' => 'E-Commerce',
            ],
            // 4. Selesai (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 1799000,
                'discount_amount' => 100000,
                'days_ago' => 60,
                'tier' => 'Rekomendasi',
                'service' => 'Sistem Informasi',
            ],
            // 5. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 899000,
                'discount_amount' => 0,
                'days_ago' => 3,
                'tier' => 'Basic',
                'service' => 'Company Profile',
            ],
            // 6. Selesai (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 2899000,
                'discount_amount' => 200000,
                'days_ago' => 75,
                'tier' => 'Premium',
                'service' => 'Aplikasi Web',
            ],
            // 7. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 1499000,
                'discount_amount' => 0,
                'days_ago' => 12,
                'tier' => 'Rekomendasi',
                'service' => 'Company Profile',
            ],
            // 8. Selesai (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 1199000,
                'discount_amount' => 50000,
                'days_ago' => 90,
                'tier' => 'Basic',
                'service' => 'E-Commerce',
            ],
            // 9. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 2199000,
                'discount_amount' => 200000,
                'days_ago' => 7,
                'tier' => 'Premium',
                'service' => 'Sistem Informasi',
            ],
            // 10. Selesai (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 999000,
                'discount_amount' => 0,
                'days_ago' => 30,
                'tier' => 'Basic',
                'service' => 'Company Profile',
            ],
            // 11. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 1699000,
                'discount_amount' => 100000,
                'days_ago' => 14,
                'tier' => 'Rekomendasi',
                'service' => 'Company Profile',
            ],
            // 12. Selesai (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 1599000,
                'discount_amount' => 100000,
                'days_ago' => 40,
                'tier' => 'Rekomendasi',
                'service' => 'Aplikasi Web',
            ],
            // 13. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 2599000,
                'discount_amount' => 200000,
                'days_ago' => 10,
                'tier' => 'Premium',
                'service' => 'Sistem Informasi',
            ],
            // 14. Selesai (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 499000,
                'discount_amount' => 0,
                'days_ago' => 15,
                'tier' => 'Basic',
                'service' => 'Landing Page',
            ],
            // 15. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 1899000,
                'discount_amount' => 150000,
                'days_ago' => 6,
                'tier' => 'Rekomendasi',
                'service' => 'Company Profile',
            ],
            // 16. Selesai (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'original_amount' => 1399000,
                'discount_amount' => 50000,
                'days_ago' => 50,
                'tier' => 'Basic',
                'service' => 'E-Commerce',
            ],
            // 17. DP (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'original_amount' => 2299000,
                'discount_amount' => 150000,
                'days_ago' => 2,
                'tier' => 'Premium',
                'service' => 'Aplikasi Web',
            ],
        ];

        $addonOptions = [
            ['name' => 'Custom Domain .COM (1 Tahun)', 'price' => 175000],
            ['name' => 'Cloud Hosting NVMe Speed Plus', 'price' => 250000],
            ['name' => 'Integrasi WhatsApp Gateway Bot', 'price' => 300000],
            ['name' => 'Multi-Language (ID & EN)', 'price' => 200000],
            ['name' => 'Optimasi SEO On-Page Premium', 'price' => 150000],
        ];

        $boilerplateSoldCountMap = [];

        foreach ($dummyCustomers as $index => $customerData) {
            $config = $orderConfigs[$index];
            $createdAt = Carbon::now()->subDays($config['days_ago'])->subHours(rand(1, 10));

            // Create or update Customer User
            $user = User::updateOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'phone' => $customerData['phone'],
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );

            // Select portfolio
            $portfolio = null;
            if ($portfolios->isNotEmpty()) {
                $portfolio = $portfolios[$index % $portfolios->count()];
            }

            $boilerplateId = $portfolio ? $portfolio->id : null;
            $boilerplateName = $portfolio ? $portfolio->title : ($customerData['company'] . ' System');
            $serviceName = $portfolio && !empty($portfolio->category) ? $portfolio->category : $config['service'];
            $packageName = 'Paket ' . ($portfolio->package_tier ?? $config['tier']);
            $projectName = 'Proyek Website & Sistem ' . $customerData['company'];

            // Track boilerplate sold count
            if ($portfolio && $portfolio->is_boilerplate) {
                if (!isset($boilerplateSoldCountMap[$portfolio->id])) {
                    $boilerplateSoldCountMap[$portfolio->id] = 0;
                }
                $boilerplateSoldCountMap[$portfolio->id]++;
            }

            // Calculation
            $originalAmount = $config['original_amount'];
            $discountAmount = $config['discount_amount'];
            $totalAmount = $originalAmount - $discountAmount;

            $isFull = $config['payment_scheme'] === 'full_100';
            $isDp = !$isFull;

            if ($isFull) {
                $dpAmount = $totalAmount;
                $remainingAmount = 0;
            } else {
                $dpAmount = (int) round($totalAmount * 0.5);
                $remainingAmount = $config['payment_status'] === 'fully_paid' ? 0 : ($totalAmount - $dpAmount);
            }

            // Assign 1 or 2 addons randomly
            $selectedAddons = [];
            if ($index % 2 === 0) {
                $selectedAddons[] = $addonOptions[$index % count($addonOptions)];
            }
            if ($index % 3 === 0) {
                $selectedAddons[] = $addonOptions[($index + 2) % count($addonOptions)];
            }

            // Invoice number format
            $invDate = $createdAt->format('Ymd');
            $invSuffix = strtoupper(Str::random(5));
            $invoiceNumber = 'INV-' . $invDate . '-' . $invSuffix;

            // Check if existing dummy order for this user/invoice exists
            Order::create([
                'user_id' => $user->id,
                'invoice_number' => $invoiceNumber,
                'token' => Str::random(32),
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'],
                'project_name' => $projectName,
                'service_name' => $serviceName,
                'package_name' => $packageName,
                'boilerplate_id' => $boilerplateId,
                'boilerplate_name' => $boilerplateName,
                'addons' => $selectedAddons,
                'original_amount' => $originalAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'payment_scheme' => $config['payment_scheme'],
                'payment_status' => $config['payment_status'],
                'project_status' => $config['project_status'],
                'pakasir_trx_id' => 'PKS-' . strtoupper(Str::random(10)),
                'notes' => $customerData['notes'],
                'created_at' => $createdAt,
                'updated_at' => $config['payment_status'] === 'fully_paid' ? $createdAt->copy()->addDays(rand(2, 10)) : $createdAt,
            ]);
        }

        // Update portfolio sold counts based on sales
        foreach ($boilerplateSoldCountMap as $portId => $count) {
            $p = Portfolio::find($portId);
            if ($p) {
                $p->sold_count = ($p->sold_count ?? 0) + $count;
                $p->save();
            }
        }

        $this->command->info('Seeding berhasil: 17 Customer Dummy beserta Order & Status DP / Selesai telah dibuat.');
    }
}
