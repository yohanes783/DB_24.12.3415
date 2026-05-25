<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // 1. Akun Admin Utama
        \App\Models\User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Insert Kategori Event
        $category = \App\Models\Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $category2 = \App\Models\Category::firstOrCreate([
            'name' => 'Entertaiment',
            'slug' => 'entertaiment',
        ]);

        $category3 = \App\Models\Category::firstOrCreate([
            'name' => 'E-Sport',
            'slug' => 'e-sport',
        ]);

        // 3. Insert Sampel Events
        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik jazz
            yang merdu.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi
            inovatif untuk tantangan masa depan!',
            'date' => '2026-05-05 10:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI & FUTURE TECH SUMMIT 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan
            teknologi masa depan bersama para ahli di bidangnya.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Valorant Campus Tournament',
            'description' => 'Ikuti Tournament kampus satu indonesia',
            'date' => '2026-04-02 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 25000,
            'stock' => 50,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Bersahabat Dengan UI/UX',
            'description' => 'Jelajahi tren terkini dalam design web dan
            aplikasi masa depan bersama para ahli di bidangnya',
            'date' => '2026-07-02 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 30000,
            'stock' => 50,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Perkembangan E-Sport Di Indonesia',
            'description' => 'Mengetahui latar belakang E-Sport yang sedang
            ramai di Indonesia',
            'date' => '2026-06-02 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 20000,
            'stock' => 50,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Hardcore Local-Mu',
            'description' => 'Dentuman drum dan suara Scream yang menantimu.',
            'date' => '2026-06-11 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);
    }
}
