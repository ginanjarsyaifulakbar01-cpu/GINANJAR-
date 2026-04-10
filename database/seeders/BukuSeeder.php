<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Inisialisasi Faker Indonesia
        $faker = Faker::create('id_ID');

        // 2. Ambil ID Kategori dari DB
        $categoryIds = Category::pluck('id')->toArray();

        // Backup kalau tabel categories kosong, biar ga error pas input category_id
        if (empty($categoryIds)) {
            $categories = ['Sains', 'Novel', 'Sejarah', 'Teknologi', 'Agama'];
            foreach ($categories as $cat) {
                $newCat = Category::create(['name' => $cat]);
                $categoryIds[] = $newCat->id;
            }
        }

        // 3. List 30 Judul Buku (Biar unik sesuai migrasi kamu)
        $judulBuku = [
            'Laskar Pelangi', 'Bumi Manusia', 'Filosofi Teras', 'Negeri 5 Menara', 
            'Pulang', 'Laut Bercerita', 'Hujan', 'Dilan 1990', 'Ayat-Ayat Cinta', 
            'Sang Pemimpi', 'Perahu Kertas', 'Ronggeng Dukuh Paruk', 'Cantik Itu Luka',
            'Garis Waktu', 'Sebuah Seni Untuk Bersikap Bodo Amat', 'Atomic Habits',
            'Rich Dad Poor Dad', 'Sapiens', 'Dunia Sophie', 'Madilog', 
            'Tenggelamnya Kapal Van der Wijck', 'Habis Gelap Terbitlah Terang',
            'Bumi', 'Bulan', 'Matahari', 'Bintang', 'Komet', 'Ceros dan Batozar',
            'Selena', 'Nebula'
        ];

       // 4. Generate 30 Data
        for ($i = 0; $i < 30; $i++) {
            $judul = $judulBuku[$i];
            $randomImage = rand(1, 14) . '.jpg';

            // Pake updateOrCreate biar ga bentrok sama data yang udah ada
            Buku::updateOrCreate(
                ['judul' => $judul], // Cek berdasarkan judul
                [
                    'category_id'  => $faker->randomElement($categoryIds),
                    'penulis'      => $faker->name,
                    'tahun_terbit' => $faker->year(),
                    'stok'         => $faker->numberBetween(5, 50),
                    'cover'        => 'cover-img/' . $randomImage, 
                ]
            );
        }
    }
}