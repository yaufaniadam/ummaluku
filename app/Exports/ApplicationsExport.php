<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\Batch;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ApplicationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        // Cari tahun aktif berdasarkan batch yang sedang aktif
        $activeBatch = Batch::where('is_active', true)->first();

        // Jika tidak ada batch aktif, gunakan tahun sekarang sebagai fallback
        $year = $activeBatch ? $activeBatch->year : date('Y');

        // Ambil semua aplikasi yang terhubung dengan batch di tahun yang sama
        // Ini memastikan kita mengambil data dari semua gelombang (batch) dalam satu tahun akademik
        return Application::query()
            ->with([
                'prospective.user',
                'prospective.religion',
                'prospective.highSchool',
                'prospective.highSchoolMajor',
                'prospective.province',
                'prospective.city',
                'prospective.district',
                'prospective.village',
                'programChoices.program',
                'admissionCategory',
                'batch'
            ])
            ->whereHas('batch', function ($q) use ($year) {
                $q->where('year', $year);
            });
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama Lengkap',
            'Email',
            'No HP',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Alamat Lengkap',
            'Provinsi',
            'Kabupaten/Kota',
            'Kecamatan',
            'Kelurahan/Desa',
            'Kode Pos',
            'Asal Sekolah',
            'Jurusan Sekolah',
            'Nama Ayah',
            'NIK Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'No HP Orang Tua',
            'Nama Wali',
            'No HP Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'Pilihan Prodi 1',
            'Pilihan Prodi 2',
            'Jalur Pendaftaran',
            'Gelombang',
            'Tahun',
            'Status',
        ];
    }

    public function map($application): array
    {
        $p = $application->prospective;

        // Ambil pilihan prodi
        $choice1 = $application->programChoices->where('choice_order', 1)->first();
        $choice2 = $application->programChoices->where('choice_order', 2)->first();

        return [
            $application->registration_number,
            $p->user->name ?? '',
            $p->user->email ?? '',
            $p->phone,
            $p->nisn,
            $p->id_number,
            $p->birth_place,
            $p->birth_date,
            $p->gender,
            $p->religion->name ?? '',
            $p->address,
            $p->province->name ?? '',
            $p->city->name ?? '',
            $p->district->name ?? '',
            $p->village->name ?? '',
            $p->postal_code,
            $p->highSchool->name ?? '',
            $p->highSchoolMajor->name ?? '',
            $p->father_name,
            $p->father_nik,
            $p->father_occupation,
            $p->father_income,
            $p->mother_name,
            $p->mother_nik,
            $p->mother_occupation,
            $p->mother_income,
            $p->parent_phone,
            $p->guardian_name,
            $p->guardian_phone,
            $p->guardian_occupation,
            $p->guardian_income,
            $choice1 ? ($choice1->program->name_id ?? '') : '',
            $choice2 ? ($choice2->program->name_id ?? '') : '',
            $application->admissionCategory->name ?? '',
            $application->batch->name ?? '',
            $application->batch->year ?? '',
            $application->status,
        ];
    }
}
