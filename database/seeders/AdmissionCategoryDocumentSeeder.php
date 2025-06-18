<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionCategoryDocumentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua ID kategori dan dokumen dengan 'slug' sebagai kuncinya
        $categories = DB::table('admission_categories')->pluck('id', 'slug');
        $documents = DB::table('document_requirements')->pluck('id', 'slug');

        // 2. Definisikan relasi menggunakan slug yang sudah pasti unik
        $relations = [
            // Jalur Prestasi
            ['category' => 'prestasi', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'prestasi', 'document' => 'pas-foto-3x4'],
            ['category' => 'prestasi', 'document' => 'kartu-keluarga'],
            ['category' => 'prestasi', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'prestasi', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'prestasi', 'document' => 'sertifikat-prestasi'],

            // Jalur Tes
            ['category' => 'tes', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'tes', 'document' => 'pas-foto-3x4'],
            ['category' => 'tes', 'document' => 'kartu-keluarga'],
            ['category' => 'tes', 'document' => 'ktp-kartu-identitas'],

            // Jalur Mandiri
            ['category' => 'mandiri', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'mandiri', 'document' => 'pas-foto-3x4'],
            ['category' => 'mandiri', 'document' => 'kartu-keluarga'],
            ['category' => 'mandiri', 'document' => 'ktp-kartu-identitas'],
            
            // Jalur Beasiswa
            ['category' => 'beasiswa', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'beasiswa', 'document' => 'pas-foto-3x4'],
            ['category' => 'beasiswa', 'document' => 'kartu-keluarga'],
            ['category' => 'beasiswa', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'beasiswa', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'beasiswa', 'document' => 'surat-keterangan-tidak-mampu'],
        ];

        $insertData = [];
        foreach ($relations as $relation) {
            // Pastikan slug ada sebelum mencoba mengambil ID
            if (isset($categories[$relation['category']]) && isset($documents[$relation['document']])) {
                $insertData[] = [
                    'admission_category_id' => $categories[$relation['category']],
                    'document_requirement_id' => $documents[$relation['document']],
                ];
            }
        }
        
        // 3. Masukkan data yang sudah dipetakan dengan ID yang benar
        DB::table('admission_category_document_requirement')->insert($insertData);
    }
}