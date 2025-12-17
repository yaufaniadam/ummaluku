<?php

namespace Database\Seeders;

use App\Models\EmployeeDocumentType;
use App\Models\EmployeeRank;
use App\Models\EmploymentStatus;
use App\Models\FunctionalPosition;
use App\Models\StructuralPosition;
use Illuminate\Database\Seeder;

class SdmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Employee Ranks (Golongan)
        $ranks = [
            ['grade' => 'I/a', 'name' => 'Juru Muda'],
            ['grade' => 'I/b', 'name' => 'Juru Muda Tingkat I'],
            ['grade' => 'I/c', 'name' => 'Juru'],
            ['grade' => 'I/d', 'name' => 'Juru Tingkat I'],
            ['grade' => 'II/a', 'name' => 'Pengatur Muda'],
            ['grade' => 'II/b', 'name' => 'Pengatur Muda Tingkat I'],
            ['grade' => 'II/c', 'name' => 'Pengatur'],
            ['grade' => 'II/d', 'name' => 'Pengatur Tingkat I'],
            ['grade' => 'III/a', 'name' => 'Penata Muda'],
            ['grade' => 'III/b', 'name' => 'Penata Muda Tingkat I'],
            ['grade' => 'III/c', 'name' => 'Penata'],
            ['grade' => 'III/d', 'name' => 'Penata Tingkat I'],
            ['grade' => 'IV/a', 'name' => 'Pembina'],
            ['grade' => 'IV/b', 'name' => 'Pembina Tingkat I'],
            ['grade' => 'IV/c', 'name' => 'Pembina Utama Muda'],
            ['grade' => 'IV/d', 'name' => 'Pembina Utama Madya'],
            ['grade' => 'IV/e', 'name' => 'Pembina Utama'],
        ];

        foreach ($ranks as $rank) {
            EmployeeRank::firstOrCreate(['grade' => $rank['grade']], $rank);
        }

        // 2. Structural Positions (Generic)
        $structuralPositions = [
            'Rektor',
            'Wakil Rektor Bidang AIK, Akademik, dan Kerja Sama',
            'Wakil Rektor Bidang Kemahasiswaan, SDM, dan Keuangan',
            'Dekan',
            'Wakil Dekan',
            'Kepala TU Fakultas',
            'Kepala Bagian Administrasi Akademik',
            'Ketua Program Studi',
            'Sekretaris Program Studi',
            'Kepala Divisi',
            'Ketua Senat Universitas',
            'Sekretaris Senat Universitas',
        ];

        foreach ($structuralPositions as $name) {
            StructuralPosition::firstOrCreate(['name' => $name]);
        }

        // 3. Functional Positions
        $academicFunctional = [
            'Asisten Ahli',
            'Lektor',
            'Lektor Kepala',
            'Guru Besar',
        ];

        foreach ($academicFunctional as $name) {
            FunctionalPosition::firstOrCreate(
                ['name' => $name],
                ['type' => 'academic']
            );
        }

        $nonAcademicFunctional = [
            'Arsiparis',
            'Pranata Komputer',
            'Pustakawan',
            'Laboran',
            'Teknisi',
            'Analis Kepegawaian',
        ];

        foreach ($nonAcademicFunctional as $name) {
            FunctionalPosition::firstOrCreate(
                ['name' => $name],
                ['type' => 'non-academic']
            );
        }

        // 4. Employment Statuses
        $statuses = [
            'Pegawai Tetap Yayasan',
            'Pegawai Negeri Sipil (PNS) DPK',
            'Pegawai Kontrak',
            'Dosen Luar Biasa',
            'Honorer',
            'Magang',
        ];

        foreach ($statuses as $name) {
            EmploymentStatus::firstOrCreate(['name' => $name]);
        }

        // 5. Document Types
        $docs = [
            ['name' => 'Ijazah S1', 'is_mandatory' => true],
            ['name' => 'Transkrip Nilai S1', 'is_mandatory' => true],
            ['name' => 'Ijazah S2', 'is_mandatory' => false],
            ['name' => 'Transkrip Nilai S2', 'is_mandatory' => false],
            ['name' => 'Ijazah S3', 'is_mandatory' => false],
            ['name' => 'Transkrip Nilai S3', 'is_mandatory' => false],
            ['name' => 'KTP', 'is_mandatory' => true],
            ['name' => 'Kartu Keluarga', 'is_mandatory' => true],
            ['name' => 'NPWP', 'is_mandatory' => false],
            ['name' => 'SK Pengangkatan', 'is_mandatory' => true],
            ['name' => 'Sertifikat Pendidik (Serdos)', 'is_mandatory' => false],
            ['name' => 'Sertifikat Kompetensi', 'is_mandatory' => false],
        ];

        foreach ($docs as $doc) {
            EmployeeDocumentType::firstOrCreate(
                ['name' => $doc['name']],
                ['is_mandatory' => $doc['is_mandatory']]
            );
        }
    }
}
