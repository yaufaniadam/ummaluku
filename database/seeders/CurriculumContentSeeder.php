<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculumContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai proses pengisian mata kuliah ke dalam kurikulum...');

        // Ambil semua kurikulum yang ada, beserta relasi program studinya
        $curriculums = Curriculum::with('program')->get();
        
        // Ambil semua mata kuliah umum (yang program_id nya null)
        $universityCourses = Course::whereNull('program_id')->get();

        if ($curriculums->isEmpty()) {
            $this->command->warn('Tidak ada kurikulum yang ditemukan. Proses dilewati.');
            return;
        }

        foreach ($curriculums as $curriculum) {
            $this->command->line("-> Memproses kurikulum: {$curriculum->name}");

            // Ambil semua mata kuliah yang spesifik untuk prodi dari kurikulum ini
            $programCourses = Course::where('program_id', $curriculum->program_id)->get();

            // Gabungkan mata kuliah prodi dengan mata kuliah universitas
            $allApplicableCourses = $programCourses;//->merge($universityCourses);
            
            if ($allApplicableCourses->isEmpty()) {
                $this->command->line('   - Tidak ada mata kuliah yang cocok untuk ditambahkan.');
                continue;
            }

            // Siapkan data untuk dimasukkan ke tabel pivot (curriculum_course)
            $dataToAttach = [];
            foreach ($allApplicableCourses as $course) {
                $dataToAttach[$course->id] = [
                    'semester' => $course->semester_recommendation, // Gunakan rekomendasi semester
                    'type' => $course->type, // Gunakan tipe (Wajib/Pilihan) dari master
                ];
            }

            // 'sync' akan melampirkan semua mata kuliah ke kurikulum
            // Ini akan menghapus relasi lama dan menggantinya dengan yang baru (aman untuk seeder)
            $curriculum->courses()->sync($dataToAttach);
            
            $this->command->line('   - ' . count($dataToAttach) . ' mata kuliah berhasil ditambahkan.');
        }

        $this->command->info('Proses pengisian kurikulum selesai.');
    }
}