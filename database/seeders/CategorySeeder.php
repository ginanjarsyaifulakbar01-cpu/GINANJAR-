<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sains', 'icon' => 'fas fa-flask'],
            ['name' => 'Novel', 'icon' => 'fas fa-book'],
            ['name' => 'Sejarah', 'icon' => 'fas fa-landmark'],
            ['name' => 'Bisnis', 'icon' => 'fas fa-briefcase'],
            ['name' => 'Agama', 'icon' => 'fas fa-pray'],
            ['name' => 'Teknologi', 'icon' => 'fas fa-microchip'],
            ['name' => 'Seni', 'icon' => 'fas fa-paint-brush'],
            ['name' => 'Kesehatan', 'icon' => 'fas fa-heartbeat'],
            ['name' => 'Bahasa', 'icon' => 'fas fa-language'],
            ['name' => 'Budaya', 'icon' => 'fas fa-globe-asia'],
            ['name' => 'Komik', 'icon' => 'fas fa-comment-dots'],
            ['name' => 'Fisika', 'icon' => 'fas fa-atom'],
            ['name' => 'Kimia', 'icon' => 'fas fa-vial'],
            ['name' => 'Biologi', 'icon' => 'fas fa-dna'],
            ['name' => 'Ekonomi', 'icon' => 'fas fa-chart-line'],
            ['name' => 'Politik', 'icon' => 'fas fa-fist-raised'],
            ['name' => 'Hukum', 'icon' => 'fas fa-gavel'],
            ['name' => 'Filsafat', 'icon' => 'fas fa-brain'],
            ['name' => 'Psikologi', 'icon' => 'fas fa-user-friends'],
            ['name' => 'Sastra', 'icon' => 'fas fa-feather'],
            ['name' => 'Kuliner', 'icon' => 'fas fa-utensils'],
            ['name' => 'Wisata', 'icon' => 'fas fa-plane'],
            ['name' => 'Olahraga', 'icon' => 'fas fa-basketball-ball'],
            ['name' => 'Musik', 'icon' => 'fas fa-music'],
            ['name' => 'Film', 'icon' => 'fas fa-film'],
            ['name' => 'Fotografi', 'icon' => 'fas fa-camera'],
            ['name' => 'Desain', 'icon' => 'fas fa-bezier-curve'],
            ['name' => 'Coding', 'icon' => 'fas fa-code'],
            ['name' => 'Astronomi', 'icon' => 'fas fa-star'],
            ['name' => 'Arsitektur', 'icon' => 'fas fa-building'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'icon' => $cat['icon'],
                ]
            );
        }
    }
}