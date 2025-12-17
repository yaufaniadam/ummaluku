<?php

namespace Database\Seeders;

use App\Imports\CoursesImport;
use App\Imports\LecturersImport;
use App\Imports\StaffImport;
use App\Imports\OldStudentsImport;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class DataImportSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Memulai proses import data dari file Excel...');

        // 1. Import Dosen
        $dosenFile = database_path('seeders/data/dosen.xlsx');
        if (file_exists($dosenFile)) {
            Excel::import(new LecturersImport, $dosenFile);
            $this->command->line('  -> Data Dosen berhasil diimpor.');
        }

        // 2. Import Mahasiswa Lama
        $mahasiswaFile = database_path('seeders/data/mahasiswa.csv');
        if (file_exists($mahasiswaFile)) {
            Excel::import(new OldStudentsImport, $mahasiswaFile);
            $this->command->line('  -> Data Mahasiswa Lama berhasil diimpor.');
        }

        // 3. Import Mata Kuliah Universitas
        $mkUniversitasFile = database_path('seeders/data/mk_0.xlsx');
        if (file_exists($mkUniversitasFile)) {
            // program_id = 0 untuk tingkat Universitas
            Excel::import(new CoursesImport(0), $mkUniversitasFile);
            $this->command->line('  -> Data Mata Kuliah Universitas berhasil diimpor.');
        }

        // 4. Import Mata Kuliah per Program Studi
        $programs = Program::all();
        foreach ($programs as $program) {
            $mkProdiFile = database_path("seeders/data/mk_{$program->id}.xlsx");
            if (file_exists($mkProdiFile)) {
                Excel::import(new CoursesImport($program->id), $mkProdiFile);
                $this->command->line("  -> Data Mata Kuliah untuk prodi '{$program->name_id}' berhasil diimpor.");
            }
        }


        // 5. Import Tendik
        $tendikFile = database_path('seeders/data/tendik.xlsx');
        if (file_exists($tendikFile)) {
            Excel::import(new StaffImport, $tendikFile);
            $this->command->line('  -> Data Tendik berhasil diimpor.');
        }

        $this->command->info('Proses import data selesai.');
    }
}