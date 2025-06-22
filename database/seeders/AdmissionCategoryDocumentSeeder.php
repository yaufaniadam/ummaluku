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
            // Jalur jalur-nilai-rapor
            // ['category' => 'jalur-nilai-rapor', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-nilai-rapor', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-nilai-rapor', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-nilai-rapor', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'jalur-nilai-rapor', 'document' => 'nilai-rapor-semester-1-5'],
            
            // Jalur Prestasi
            // ['category' => 'jalur-prestasi', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-prestasi', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-prestasi', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-prestasi', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-prestasi', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'jalur-prestasi', 'document' => 'sertifikat-prestasi'],

            // Jalur Persyarikatan
            // ['category' => 'jalur-prestasi', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-persyarikatan', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-persyarikatan', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-persyarikatan', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-persyarikatan', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'jalur-persyarikatan', 'document' => 'sertifikat-aktif-organisasi'],

            // Jalur CBT
            // ['category' => 'jalur-cbt', 'document' => 'ijazah-sma-smk-sederajat'],
            // ['category' => 'jalur-cbt', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-cbt', 'document' => 'surat-keterangan-lulus'],
            ['category' => 'jalur-cbt', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-cbt', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-cbt', 'document' => 'ktp-kartu-identitas'],      

            // Jalur SKL
            ['category' => 'jalur-skl', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-skl', 'document' => 'surat-keterangan-lulus'],
            // ['category' => 'jalur-skl', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-skl', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-skl', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-skl', 'document' => 'ktp-kartu-identitas'],

            // Jalur UTBK
            ['category' => 'jalur-utbk', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-utbk', 'document' => 'skhun'],
            // ['category' => 'jalur-utbk', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-utbk', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-utbk', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-utbk', 'document' => 'ktp-kartu-identitas'],

                       
            // Jalur Beasiswa
            ['category' => 'jalur-kip', 'document' => 'ijazah-sma-smk-sederajat'],
            ['category' => 'jalur-kip', 'document' => 'pas-foto-3x4'],
            ['category' => 'jalur-kip', 'document' => 'kartu-keluarga'],
            ['category' => 'jalur-kip', 'document' => 'ktp-kartu-identitas'],
            ['category' => 'jalur-kip', 'document' => 'nilai-rapor-semester-1-5'],
            ['category' => 'jalur-kip', 'document' => 'surat-keterangan-tidak-mampu'],
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