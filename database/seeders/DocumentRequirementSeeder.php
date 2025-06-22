<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['name' => 'Ijazah SMA/SMK/Sederajat', 'description' => 'Scan ijazah asli atau legalisir.', 'is_mandatory' => true],
            ['name' => 'SKHUN', 'description' => 'Scan Surat Keterangan Hasil Ujian Nasional.', 'is_mandatory' => true],
            ['name' => 'Pas Foto 3x4', 'description' => 'Pas foto berwarna dengan latar belakang merah atau biru.', 'is_mandatory' => true],
            ['name' => 'Kartu Keluarga', 'description' => 'Scan Kartu Keluarga yang masih berlaku.', 'is_mandatory' => true],
            ['name' => 'KTP / Kartu Identitas', 'description' => 'Scan KTP calon mahasiswa atau kartu identitas lain yang valid.', 'is_mandatory' => true],
            ['name' => 'Nilai Rapor Semester 1-5', 'description' => 'Scan rapor semester 1-5 yang sudah dilegalisir.', 'is_mandatory' => true],
            ['name' => 'Sertifikat Prestasi', 'description' => 'Sertifikat juara/prestasi bidang akademik atau non-akademik.', 'is_mandatory' => true],
            ['name' => 'Sertifikat Aktif Organisasi', 'description' => 'organisasi otonom dalam lingkup persyarikatan Muhammadiyah seperti Ikatan Pelajar Muhammadiyah (IPM), Tapak Suci, atau Hizbul Wathan dalam 3 tahun terakhir.', 'is_mandatory' => true],
            ['name' => 'Surat Keterangan Tidak Mampu', 'description' => 'Surat keterangan dari kelurahan/desa untuk jalur beasiswa KIP.', 'is_mandatory' => true],
            ['name' => 'Surat Keterangan Lulus', 'description' => 'Surat Keterangan Lulus atau Nilai Ijazah.', 'is_mandatory' => true],
            ['name' => 'Sertifkat Hafidz', 'description' => 'Surat keterangan Syahadah Hafidz Quran.', 'is_mandatory' => true],
        ];

        // Memasukkan data ke database sambil membuat slug secara otomatis
        foreach ($documents as $doc) {
            DB::table('document_requirements')->insert([
                'name' => $doc['name'],
                'slug' => Str::slug($doc['name']),
                'description' => $doc['description'],
                'is_mandatory' => $doc['is_mandatory'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}