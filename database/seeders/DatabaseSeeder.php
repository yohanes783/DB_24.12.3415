<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Kategori Event
        $catMusik = Category::create(['name' => 'Konser Musik', 'slug' => 'konser-musik']);
        $catTech  = Category::create(['name' => 'Workshop Tech', 'slug' => 'workshop-tech']);

        // 2. Buat Akun Superadmin
        User::create([
            'name'     => 'Super Admin Hub',
            'email'    => 'superadmin@amikom.com',
            'password' => Hash::make('password123'),
            'role'     => 'superadmin',
        ]);

        // 3. Buat Akun Partner (HIMA IF) & Profil Partner-nya
        $partnerUser = User::create([
            'name'     => 'Panitia HIMA IF',
            'email'    => 'himaif@amikom.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'partner',
        ]);

        Partner::create([
            'user_id'     => $partnerUser->id,
            'name'        => 'HIMA Informatika Amikom',
            'slug'        => 'hima-if',
            'description' => 'Himpunan Mahasiswa Jurusan Informatika Universitas Amikom Yogyakarta',
            'status'      => 'approved', // Langsung di-approve untuk testing
        ]);

        // 4. Buat Akun User / Peserta
        User::create([
            'name'     => 'Budi Peserta',
            'email'    => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);
    }
}
