<?php

namespace Database\Seeders;

use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

class FeeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === Biaya untuk Prodi ID 1 (misal: Pendidikan Biologi) ===

        // Angkatan 2020
        FeeStructure::create([
            'program_id' => 1,
            'entry_year' => 2021,
            'fee_component_id' => 1, // SPP Tetap
            'amount' => 500000,
        ]);
        FeeStructure::create([
            'program_id' => 1,
            'entry_year' => 2021,
            'fee_component_id' => 2, // SPP Variabel per SKS
            'amount' => 100000,
        ]);
        FeeStructure::create([
            'program_id' => 1,
            'entry_year' => 2021,
            'fee_component_id' => 3, // SPP Variabel per Makuliah
            'amount' => 150000,
        ]);

        // Angkatan 2020
        FeeStructure::create([
            'program_id' => 2,
            'entry_year' => 2021,
            'fee_component_id' => 1, // SPP Tetap
            'amount' => 500000,
        ]);
        FeeStructure::create([
            'program_id' => 2,
            'entry_year' => 2021,
            'fee_component_id' => 2, // SPP Variabel per SKS
            'amount' => 100000,
        ]);
        FeeStructure::create([
            'program_id' => 2,
            'entry_year' => 2021,
            'fee_component_id' => 3, // SPP Variabel per Makuliah
            'amount' => 150000,
        ]);

        // Angkatan 2020
        FeeStructure::create([
            'program_id' => 3,
            'entry_year' => 2021,
            'fee_component_id' => 1, // SPP Tetap
            'amount' => 500000,
        ]);
        FeeStructure::create([
            'program_id' => 3,
            'entry_year' => 2021,
            'fee_component_id' => 2, // SPP Variabel per SKS
            'amount' => 100000,
        ]);
        FeeStructure::create([
            'program_id' => 3,
            'entry_year' => 2021,
            'fee_component_id' => 3, // SPP Variabel per Makuliah
            'amount' => 150000,
        ]); 

        // Angkatan 2020
        FeeStructure::create([
            'program_id' => 4,
            'entry_year' => 2021,
            'fee_component_id' => 1, // SPP Tetap
            'amount' => 500000,
        ]);
        FeeStructure::create([
            'program_id' => 4,
            'entry_year' => 2021,
            'fee_component_id' => 2, // SPP Variabel per SKS
            'amount' => 100000,
        ]);
        FeeStructure::create([
            'program_id' => 4,
            'entry_year' => 2021,
            'fee_component_id' => 3, // SPP Variabel per Makuliah
            'amount' => 150000,
        ]);

        // Angkatan 2020
        FeeStructure::create([
            'program_id' => 5,
            'entry_year' => 2021,
            'fee_component_id' => 1, // SPP Tetap
            'amount' => 500000,
        ]);
        FeeStructure::create([
            'program_id' => 5,
            'entry_year' => 2021,
            'fee_component_id' => 2, // SPP Variabel per SKS
            'amount' => 100000,
        ]);
        FeeStructure::create([
            'program_id' => 5,
            'entry_year' => 2021,
            'fee_component_id' => 3, // SPP Variabel per Makuliah
            'amount' => 150000,
        ]);

        
    }
}