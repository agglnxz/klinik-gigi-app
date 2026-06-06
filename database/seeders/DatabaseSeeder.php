<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use App\Models\Asisten;
use App\Models\Laboratorium;
use App\Models\JenisGigi;
use App\Models\Pasien;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Direktur Winardi',
            'email' => 'direktur@klinik.com',
            'password' => Hash::make('password123'), // Password yang dienkripsi
            'role' => 'Direktur'
        ]);
        User::create([
            'name' => 'Admin Staff',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password123'), // Password yang dienkripsi
            'role' => 'Admin'
        ]);
        User::create([
            'name' => 'Marketing Staff',
            'email' => 'marketing@klinik.com',
            'password' => Hash::make('password123'), // Password yang dienkripsi
            'role' => 'Marketing'
        ]);
        // Eksekusi pembuatan data dummy secara masal
        Dokter::factory(10)->create();       // Buat 10 Dokter
        Asisten::factory(10)->create();      // Buat 10 Asisten
        Laboratorium::factory(5)->create();  // Buat 5 Lab Mitra
        JenisGigi::factory(8)->create();     // Buat 8 Katalog Jenis Gigi
        Pasien::factory(30)->create();       // Buat 30 Pasien
    }
}
