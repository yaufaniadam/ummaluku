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
        // Konfigurasi Rentang Tahun
        $startYear = 2021;
        $endYear   = 2028;
        
        // Tentukan kode tahun yang aktif (2026/2027 Ganjil)
        $activeYearCode = '20261'; 

        for ($year = $startYear; $year <= $endYear; $year++) {
            $nextYear = $year + 1;

            // --- 1. SEMESTER GANJIL ---
            // Pola: Kode Tahun + '1' (Misal: 20211)
            $codeGanjil = $year . '1';
            
            AcademicYear::create([
                'year_code'      => $codeGanjil,
                'name'           => "Semester Ganjil $year/$nextYear",
                'semester_type'  => 'Ganjil',
                'start_date'     => "$year-09-01",
                'end_date'       => "$nextYear-01-31",
                'krs_start_date' => "$year-08-15",
                'krs_end_date'   => "$year-08-30",
                'is_active'      => ($codeGanjil === $activeYearCode),
            ]);

            // --- 2. SEMESTER GENAP ---
            // Pola: Kode Tahun + '2' (Misal: 20212)
            $codeGenap = $year . '2';

            AcademicYear::create([
                'year_code'      => $codeGenap,
                'name'           => "Semester Genap $year/$nextYear",
                'semester_type'  => 'Genap',
                'start_date'     => "$nextYear-02-01",
                'end_date'       => "$nextYear-06-30",
                'krs_start_date' => "$nextYear-01-15",
                'krs_end_date'   => "$nextYear-01-30",
                'is_active'      => false, // Genap tidak aktif sesuai request
            ]);
        }
    }
}