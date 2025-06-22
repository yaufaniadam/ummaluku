<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admission_categories')->insert([
            [
                'name' => 'Jalur Nilai Rapor (JNR)',
                'slug' => 'jalur-nilai-rapor',
                'description' => 'Jalur Nilai Rapor (JNR) ditujukan bagi siswa yang memiliki nilai rapor semester 1 sampai 4 (JNR Periode 1 dan 2) dan semester 1 sampai 5 (JNR Periode 3 dan seterusnya) dengan nilai rata-rata minimal 7,0.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'prestasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penerimaan Siswa Berprestasi (PSB)',
                'slug' => 'jalur-prestasi',
                'description' => 'PSB ditujukan bagi siswa siswi yang memiliki prestasi di berbagai bidang seperti olahraga, seni, budaya, penalaran (misal Olimpiade, Karya Ilmiah, Debat, dll). Prestasi yang dimaksud adalah pernah menjadi juara atau finalis di berbagai  lomba di tingkat nasional atau propinsi atau kabupaten dalam 3 tahun terakhir.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'prestasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penerimaan Bibit Unggul Persyarikatan (PBUP)',
                'slug' => 'jalur-persyarikatan',
                'description' => 'PBUP ditujukan bagi siswa siswi Angkatan Muda Muhammadiyah yang aktif di organisasi otonom dalam lingkup persyarikatan Muhammadiyah seperti Ikatan Pelajar Muhammadiyah (IPM), Tapak Suci, atau Hizbul Wathan dalam 3 tahun terakhir.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'prestasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur CBT (Computer Based Test)',
                'slug' => 'jalur-cbt',
                'description' => 'Penerimaan mahasiswa baru berbasis test komputer.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'reguler',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur SKL (Surat Keterangan Lulus)',
                'slug' => 'jalur-skl',
                'description' => 'Jalur SKL (Surat Keterangan Lulus) merupakan proses seleksi calon mahasiswa baru bagi siswa/siswi SMA/SMK/MA, atau sederajat yang sudah berhasil menempuh Ujian Akhir Sekolah dengan menggunakan Nilai SKL (Surat Keterangan Lulus) atau Nilai Ijazah. Jalur ini diperuntukkan siswa siswi lulusan tahun 2023, 2024 dan 2025.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'reguler',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
            [
                'name' => 'Jalur Nilai UTBK - SNBT',
                'slug' => 'jalur-nilai-utbk',
                'description' => 'Penerimaan Jalur UTBK-SNBT ditujukan bagi siswa siswi SMA/SMK/MA, atau sederajat yang telah mengikuti UTBK-SNBT Perguruan Tinggi Negeri. Jalur ini diperuntukkan bagi peserta UTBK PTN tahun 2023, 2024 dan 2025.',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'reguler',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //jalur beasiswa KIP
            [
                'name' => 'Jalur Beasiswa Kartu Indonesia Pintar (KIP)',
                'slug' => 'jalur-kip',
                'description' => 'Penerimaan bagi calon mahasiswa pemegang Kartu Indonesia Pintar (KIP).',
                'price' => 0, 
                'is_active' => true,
                'display_group' => 'beasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}