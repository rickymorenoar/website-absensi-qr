<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        Schema::disableForeignKeyConstraints();
        DB::table('classes')->truncate();
        Schema::enableForeignKeyConstraints();

        $levels = [7, 8, 9];
        $sections = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $data = [];

        foreach ($levels as $level) {
            foreach ($sections as $section) {
                $data[] = [
                    'name' => $level . $section,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('classes')->insert($data);
    }
}