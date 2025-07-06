<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultyAndProgramSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Fakultas
        DB::table('faculties')->insert([
            [
                'id' => 1,
                'name_id' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'name_en' => 'Faculty of Teacher Training and Education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name_id' => 'Fakultas Perikanan dan Kehutanan',
                'name_en' => 'Faculty of Fisheries and Forestry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan fakultas lain di sini jika ada
        ]);

        // 2. Membuat Program Studi
        DB::table('programs')->insert([
            // Prodi di bawah FKIP (faculty_id = 1)
            [
                'faculty_id' => 1,
                'code' => '11',
                'name_id' => 'Pendidikan Biologi',
                'name_en' => 'Biology Education',
                'degree' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => 1,
                'code' => '12',
                'name_id' => 'Pendidikan Matematika',
                'name_en' => 'Mathematics Education',
                'degree' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Prodi di bawah Perikanan & Kehutanan (faculty_id = 2)
            [
                'faculty_id' => 2,
                'code' => '21',
                'name_id' => 'Ilmu Kelautan',
                'name_en' => 'Marine Science',
                'degree' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => 2,
                'code' => '22',
                'name_id' => 'Perikanan Tangkap',
                'name_en' => 'Fishing Technology',
                'degree' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => 2,
                'code' => '23',
                'name_id' => 'Kehutanan',
                'name_en' => 'Forestry',
                'degree' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}