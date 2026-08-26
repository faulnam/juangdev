<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::truncate();

        $testimonials = [
            [
                'name' => 'Rian Pratama',
                'rating' => 5,
                'content' => 'Pelayanan mantap, fast respon banget di WA. Web landing page saya langsung rame orderan.',
                'display_order' => 1,
            ],
            [
                'name' => 'Siti Rahma',
                'rating' => 5,
                'content' => 'Awalnya ragu bikin web, tapi setelah jadi hasilnya bagus bgt dan gampang dibuka di HP. Makasih mas!',
                'display_order' => 2,
            ],
            [
                'name' => 'Budi Santoso',
                'rating' => 4,
                'content' => 'Pengerjaan rapi dan tepat waktu. Kemarin ada sedikit revisi pas awal tapi langsung dibenerin cepat.',
                'display_order' => 3,
            ],
            [
                'name' => 'Aldi',
                'rating' => 5,
                'content' => 'Recommended! Web landing page iklan saya lancar jaya, konversi orderan makin rame.',
                'display_order' => 4,
            ],
            [
                'name' => 'drg. Fitri',
                'rating' => 5,
                'content' => 'Web klinik gigi saya jadi kelihatan jauh lebih profesional. Pasien jadi gampang cek jadwal praktek.',
                'display_order' => 5,
            ],
            [
                'name' => 'Dimas Setiawan',
                'rating' => 5,
                'content' => 'Recommended buat yang nyari jasa web terjangkau tp kualitasnya bagus. Diajarin juga cara pakainya.',
                'display_order' => 6,
            ],
            [
                'name' => 'Novi K.',
                'rating' => 4,
                'content' => 'Desainnya simpel & estetik. Proses pengerjaan 4 hari udah beres live. Mantul pokoknya.',
                'display_order' => 7,
            ],
            [
                'name' => 'Lina Marlina',
                'rating' => 5,
                'content' => 'Adminnya ramah dan sabar banget ngejelasin buat yang masih awam. Puas sama hasilnya.',
                'display_order' => 8,
            ],
            [
                'name' => 'Fajar',
                'rating' => 4,
                'content' => 'Web toko online lancar, integrasi payment & WhatsApp order-nya jalan normal tanpa kendala.',
                'display_order' => 9,
            ],
            [
                'name' => 'Andi Saputra',
                'rating' => 5,
                'content' => 'Top markotop! Kodingan rapi dan harganya masuk akal ga ada biaya siluman.',
                'display_order' => 10,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}

