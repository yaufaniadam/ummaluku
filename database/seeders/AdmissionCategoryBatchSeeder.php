<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionCategoryBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil ID dari data master yang sudah ada
        $categories = DB::table('admission_categories')->pluck('id', 'slug');
        $batches = DB::table('batches')->pluck('id', 'name');

        // 2. Definisikan hubungan yang kita inginkan
        // Pastikan 'slug' dan 'name' di bawah ini cocok dengan yang ada di seeder lain
        $relations = [
            // Jalur Prestasi (slug: 'prestasi') dibuka di Gelombang 1 
            ['category_slug' => 'jalur-nilai-rapor', 'batch_name' => 'Gelombang 1'],
            ['category_slug' => 'jalur-nilai-rapor', 'batch_name' => 'Gelombang 2'],

            // Jalur Tes (slug: 'tes') hanya dibuka di Gelombang 1
            ['category_slug' => 'jalur-prestasi', 'batch_name' => 'Gelombang 1'],
            ['category_slug' => 'jalur-prestasi', 'batch_name' => 'Gelombang 2'],
            
            // Jalur Mandiri (slug: 'mandiri') hanya dibuka di Gelombang 1
            ['category_slug' => 'jalur-persyarikatan', 'batch_name' => 'Gelombang 1'],
            ['category_slug' => 'jalur-persyarikatan', 'batch_name' => 'Gelombang 2'],

            // Jalur Beasiswa (slug: 'beasiswa') hanya dibuka di Gelombang 1
            ['category_slug' => 'jalur-cbt', 'batch_name' => 'Gelombang 1'],

            ['category_slug' => 'jalur-skl', 'batch_name' => 'Gelombang 1'],

            ['category_slug' => 'jalur-nilai-utbk', 'batch_name' => 'Gelombang 1'],

            ['category_slug' => 'jalur-kip', 'batch_name' => 'Gelombang 1'],
        ];

        $insertData = [];
        foreach ($relations as $relation) {
            // Pastikan data master ada sebelum mencoba menghubungkan
            if (isset($categories[$relation['category_slug']]) && isset($batches[$relation['batch_name']])) {
                $insertData[] = [
                    'admission_category_id' => $categories[$relation['category_slug']],
                    'batch_id' => $batches[$relation['batch_name']],
                ];
            }
        }

        // 3. Masukkan data relasi ke tabel pivot
        DB::table('admission_category_batch')->insert($insertData);
    }
}