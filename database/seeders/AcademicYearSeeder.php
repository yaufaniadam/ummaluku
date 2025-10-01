<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh Tahun Ajaran 2024/2025 Ganjil
        AcademicYear::create([
            'year_code' => '20241',
            'name' => 'Semester Ganjil 2024/2025',
            'semester_type' => 'Ganjil',
            'start_date' => '2024-09-01',
            'end_date' => '2025-01-31',
            'krs_start_date' => '2024-08-15',
            'krs_end_date' => '2024-08-30',
            'is_active' => false,
        ]);

        // Contoh Tahun Ajaran 2024/2025 Genap
        AcademicYear::create([
            'year_code' => '20242',
            'name' => 'Semester Genap 2024/2025',
            'semester_type' => 'Genap',
            'start_date' => '2025-02-01',
            'end_date' => '2025-06-30',
            'krs_start_date' => '2025-01-15',
            'krs_end_date' => '2025-01-30',
            'is_active' => false,
        ]);

        // Contoh Tahun Ajaran 2025/2026 Ganjil (Aktif)
        AcademicYear::create([
            'year_code' => '20251',
            'name' => 'Semester Ganjil 2025/2026',
            'semester_type' => 'Ganjil',
            'start_date' => '2025-11-01',
            'end_date' => '2026-01-31',
            'krs_start_date' => '2025-08-15',
            'krs_end_date' => '2025-10-30',
            'is_active' => true, // Set semester ini sebagai yang aktif
        ]);
    }
}