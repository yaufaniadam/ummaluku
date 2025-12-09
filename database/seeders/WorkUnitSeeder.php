<?php

namespace Database\Seeders;

use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

class WorkUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Rektorat',
            'Senat Universitas',
            'Lembaga Penjaminan Mutu (LPM)',
            'Lembaga Perencanaan dan Pengembangan (LPP)',
            'Lembaga Pengkajian dan Pengamalan Islam (LPPI)',
            'Lembaga Pendidikan dan Akademik (LPA)',
            'Lembaga Penelitian dan Pengabdian Masyarakat (LP2M)',
            'Lembaga Kerjasama dan Hubungan Internasional (LKI)',
            'Lembaga Pengembangan Kemahasiswaan dan Alumni (LPKA)',
            'Lembaga Keuangan dan Aset (LKA)',
            'Lembaga Sumber Daya Manusia dan Hukum (LSDM)',
            'Lembaga Admisi/PMB (Admisi)',
            'Lembaga Sistem Informasi (LSI)',
            'Lembaga Administrasi Umum (LAU)',
            'Fakultas Perikanan dan Kehutanan (FPK)',
            'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)',
            'Program Studi Ilmu Kelautan',
            'Program Studi Perikanan Tangkap',
            'Program Studi Kehutanan',
            'Program Studi Pendidikan Matematika',
            'Program Studi Pendidikan Biologi',
        ];

        foreach ($units as $rawName) {
            $name = $rawName;
            $code = null;
            $type = 'Lainnya';

            // Extract code if available in parentheses, e.g. "Name (CODE)"
            if (preg_match('/^(.*)\s\((.*)\)$/', $rawName, $matches)) {
                $name = trim($matches[1]);
                $code = trim($matches[2]);
            }

            // Determine Type based on prefix or known keywords
            if (stripos($name, 'Rektorat') !== false) {
                $type = 'Rektorat';
            } elseif (stripos($name, 'Senat') !== false) {
                $type = 'Senat';
            } elseif (stripos($name, 'Lembaga') !== false) {
                $type = 'Lembaga';
            } elseif (stripos($name, 'Fakultas') !== false) {
                $type = 'Fakultas';
            } elseif (stripos($name, 'Program Studi') !== false) {
                $type = 'Program Studi';
            } elseif (stripos($name, 'Biro') !== false) {
                $type = 'Biro';
            } elseif (stripos($name, 'UPT') !== false) {
                $type = 'UPT';
            }

            // Create or Update
            WorkUnit::firstOrCreate(
                ['name' => $name], // Check by name
                [
                    'code' => $code,
                    'type' => $type,
                ]
            );
        }
    }
}
