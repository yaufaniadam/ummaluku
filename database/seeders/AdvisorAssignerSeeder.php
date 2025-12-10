<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class AdvisorAssignerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Memulai proses pemetaan Dosen Pembimbing Akademik...');

        $filePath = database_path('seeders/data/mappingdpa.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error('File mappingdpa.xlsx tidak ditemukan di database/data/.');
            return;
        }

        // Baca data dari Excel
        $rows = Excel::toCollection(null, $filePath)->first();

        // Ambil baris header dan data
        $header = $rows->first()->toArray();
        $dataRows = $rows->slice(1);

        // Buat progress bar
        $progressBar = $this->command->getOutput()->createProgressBar($dataRows->count());
        $progressBar->start();

        $notFoundStudents = [];
        $notFoundLecturers = [];

        foreach ($dataRows as $row) {
            // Buat array asosiatif dari baris data
            $rowData = array_combine($header, $row->toArray());

            $student = Student::where('nim', $rowData['nim'])->first();
            $lecturer = Lecturer::where('nidn', $rowData['nidn_dosen'])->first();

            if ($student && $lecturer) {
                // Jika mahasiswa dan dosen ditemukan, update data DPA
                $student->update([
                    'academic_advisor_id' => $lecturer->id
                ]);
            } else {
                if (!$student) $notFoundStudents[] = $rowData['nim'];
                if (!$lecturer) $notFoundLecturers[] = $rowData['nidn_dosen'];
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);

        if (!empty($notFoundStudents) || !empty($notFoundLecturers)) {
            $this->command->warn('Beberapa data tidak berhasil diproses:');
            if (!empty($notFoundStudents)) $this->command->line('NIM mahasiswa tidak ditemukan: ' . implode(', ', array_unique($notFoundStudents)));
            if (!empty($notFoundLecturers)) $this->command->line('NIDN dosen tidak ditemukan: ' . implode(', ', array_unique($notFoundLecturers)));
        }

        $this->command->info('Proses pemetaan Dosen Pembimbing Akademik selesai.');
    }
}