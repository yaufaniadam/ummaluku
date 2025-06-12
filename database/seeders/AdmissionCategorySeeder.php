<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admission_categories')->insert([
            [
                'name' => 'Jalur Prestasi',
                'slug' => 'prestasi',
                'description' => 'Pendaftaran melalui seleksi nilai rapor atau sertifikat prestasi non-akademik. ',
                'price' => 0, // Ada kuota gratis biaya pendaftaran untuk jalur prestasi 
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur Tes',
                'slug' => 'tes',
                'description' => 'Pendaftaran melalui seleksi tes tulis/CBT sesuai jadwal yang ditentukan. ',
                'price' => 200000, // Biaya pendaftaran 200rb 
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur Mandiri',
                'slug' => 'mandiri',
                'description' => 'Pendaftaran secara mandiri dengan menghubungi Kepala TU Program Studi/Fakultas. ',
                'price' => 200000, // Biaya pendaftaran 200rb 
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}