<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\Program;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua program studi yang ada
        $programs = Program::all();

        foreach ($programs as $program) {
            // Buat kurikulum untuk setiap program studi
            Curriculum::create([
                'program_id' => $program->id,
                'name' => 'Kurikulum Merdeka ' . $program->name_id, // Gunakan name_id sesuai struktur tabel Anda
                'start_year' => 2021,
                'is_active' => true,
            ]);
        }
    }
}