<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPerencanaanWilayahKotaProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Add new program: Perencanaan Wilayah dan Kota
     * Fakultas: Perikanan dan Kehutanan (ID: 2)
     */
    public function run(): void
    {
        // Find Fakultas Perikanan dan Kehutanan
        $faculty = DB::table('faculties')
            ->where('name_id', 'Fakultas Perikanan dan Kehutanan')
            ->first();

        if (!$faculty) {
            $this->command->error('Fakultas Perikanan dan Kehutanan not found!');
            return;
        }

        // Check if program already exists
        $exists = DB::table('programs')
            ->where('name_id', 'Perencanaan Wilayah dan Kota')
            ->exists();

        if ($exists) {
            $this->command->warn('Program Perencanaan Wilayah dan Kota already exists!');
            return;
        }

        // Insert new program
        DB::table('programs')->insert([
            'faculty_id' => $faculty->id,
            'code' => '24', // Next code after Kehutanan (23)
            'name_id' => 'Perencanaan Wilayah dan Kota',
            'name_en' => 'Urban and Regional Planning',
            'degree' => 'S1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✓ Program Perencanaan Wilayah dan Kota has been added successfully!');
    }
}
