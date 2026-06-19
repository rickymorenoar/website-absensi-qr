<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan foreign key agar bisa truncate
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan semua tabel utama
        DB::table('attendances')->truncate();
        DB::table('users')->truncate();
        DB::table('classes')->truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // 4. Buat akun Admin utama
        User::create([
            'name' => 'Ricky Moreno',
            'email' => 'rickymoreno851@gmail.com',
            'password' => Hash::make('rickymoreno668@'),
            'role' => 'admin',
        ]);
        
        // 5. Panggil seeder kelas (pastikan file ClassSeeder.php sudah ada)
        $this->call([
            ClassSeeder::class,
        ]);
    }
}