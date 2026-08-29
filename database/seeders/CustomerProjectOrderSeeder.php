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
     * Helper to get official JuangDev pricing for services and tiers.
     */
    private function getOfficialPackagePrice(string $category, string $tier): int
    {
        $cat = strtolower($category);
        $t = strtolower($tier);

        if (str_contains($cat, 'landing')) {
            if (str_contains($t, 'basic')) return 99000;
            if (str_contains($t, 'premium')) return 499000;
            return 299000; // Rekomendasi
        }

        if (str_contains($cat, 'company') || str_contains($cat, 'profil')) {
            if (str_contains($t, 'basic')) return 199000;
            if (str_contains($t, 'premium')) return 899000;
            return 499000; // Rekomendasi
        }

        if (str_contains($cat, 'commerce') || str_contains($cat, 'toko')) {
            if (str_contains($t, 'basic')) return 499000;
            if (str_contains($t, 'premium')) return 999000;
            return 899000; // Rekomendasi
        }

        if (str_contains($cat, 'sistem') || str_contains($cat, 'informasi')) {
            if (str_contains($t, 'basic')) return 399000;
            if (str_contains($t, 'premium')) return 999000;
            return 799000; // Rekomendasi
        }

        // Custom Web App / Aplikasi Web
        if (str_contains($t, 'basic')) return 499000;
        if (str_contains($t, 'premium')) return 999000;
        return 799000; // Rekomendasi
    }

    /**
     * Run the database seeds for 17 dummy customer project orders with exact official pricing.
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

        // Clean up previous dummy orders to avoid duplicates
        $dummyEmails = array_column($dummyCustomers, 'email');
        Order::whereIn('customer_email', $dummyEmails)->delete();

        // Ensure we have available portfolios to associate
        $portfolios = Portfolio::all();

        // 17 Order configuration specifications (Randomized statuses: DP vs Selesai)
        // Staggered dates within the last few weeks/months
        $orderConfigs = [
            // 1. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 4,
            ],
            // 2. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 38,
            ],
            // 3. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 6,
            ],
            // 4. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 45,
            ],
            // 5. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 2,
            ],
            // 6. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 52,
            ],
            // 7. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 9,
            ],
            // 8. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 60,
            ],
            // 9. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 5,
            ],
            // 10. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 28,
            ],
            // 11. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 11,
            ],
            // 12. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 35,
            ],
            // 13. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 8,
            ],
            // 14. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 14,
            ],
            // 15. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 3,
            ],
            // 16. Selesai / Fully Paid (Completed)
            [
                'payment_scheme' => 'full_100',
                'payment_status' => 'fully_paid',
                'project_status' => 'completed',
                'days_ago' => 42,
            ],
            // 17. DP 50% (In Progress)
            [
                'payment_scheme' => 'dp_50',
                'payment_status' => 'dp_paid',
                'project_status' => 'in_progress',
                'days_ago' => 1,
            ],
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

            // Select portfolio from available list
            $portfolio = null;
            if ($portfolios->isNotEmpty()) {
                $portfolio = $portfolios[$index % $portfolios->count()];
            }

            $boilerplateId = $portfolio ? $portfolio->id : null;
            $boilerplateName = $portfolio ? $portfolio->title : ($customerData['company'] . ' System');
            $serviceCategory = $portfolio && !empty($portfolio->category) ? $portfolio->category : 'Aplikasi Web';
            $packageTier = $portfolio && !empty($portfolio->package_tier) ? $portfolio->package_tier : 'Rekomendasi';
            $packageName = 'Paket ' . $packageTier;
            $projectName = 'Proyek Website & Sistem ' . $customerData['company'];

            // Track boilerplate sold count
            if ($portfolio && $portfolio->is_boilerplate) {
                if (!isset($boilerplateSoldCountMap[$portfolio->id])) {
                    $boilerplateSoldCountMap[$portfolio->id] = 0;
                }
                $boilerplateSoldCountMap[$portfolio->id]++;
            }

            // Get exact official JuangDev package price
            $totalAmount = $this->getOfficialPackagePrice($serviceCategory, $packageTier);
            $originalAmount = $totalAmount;
            $discountAmount = 0;

            $isFull = $config['payment_scheme'] === 'full_100';
            if ($isFull) {
                $dpAmount = $totalAmount;
                $remainingAmount = 0;
            } else {
                $dpAmount = (int) round($totalAmount * 0.5);
                $remainingAmount = $config['payment_status'] === 'fully_paid' ? 0 : ($totalAmount - $dpAmount);
            }

            // Invoice number format
            $invDate = $createdAt->format('Ymd');
            $invSuffix = strtoupper(Str::random(5));
            $invoiceNumber = 'INV-' . $invDate . '-' . $invSuffix;

            Order::create([
                'user_id' => $user->id,
                'invoice_number' => $invoiceNumber,
                'token' => Str::random(32),
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'],
                'project_name' => $projectName,
                'service_name' => $serviceCategory,
                'package_name' => $packageName,
                'boilerplate_id' => $boilerplateId,
                'boilerplate_name' => $boilerplateName,
                'addons' => [],
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

        $this->command->info('Seeding berhasil: 17 Customer Dummy beserta Order dengan harga resmi JuangDev & status DP / Selesai telah dibuat.');
    }
}
