<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoursesImport implements ToModel, WithHeadingRow, WithValidation
{
    private $programId;

    public function __construct($programId)
    {
        // Jika yang dipilih adalah "Universitas" (value 0), simpan sebagai null
        $this->programId = ($programId > 0) ? $programId : null;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Course([
            'program_id'              => $this->programId,
            'code'                    => strtoupper($row['kode_mk']),
            'name'                    => $row['nama_mata_kuliah'],
            'sks'                     => $row['sks'],
            'semester_recommendation' => $row['semester'],
            'type'                    => $row['jenis'],
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_mk' => 'required|string|unique:courses,code',
            'nama_mata_kuliah' => 'required|string',
            'sks' => 'required|integer',
            'semester' => 'required|integer|min:1|max:8',
            'jenis' => 'required|in:Wajib,Pilihan,Wajib Peminatan',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        // Pesan error kustom untuk setiap aturan validasi.
        return [
            '*.kode_mk.required' => 'Kolom kode_mk wajib diisi.',
            '*.kode_mk.unique' => 'Salah satu Kode MK di dalam file Anda sudah ada di dalam sistem.',
            '*.nama_mata_kuliah.required' => 'Kolom nama_mata_kuliah wajib diisi.',
            '*.sks.required' => 'Kolom sks wajib diisi.',
            '*.sks.integer' => 'Kolom sks harus berupa angka.',
            '*.semester.required' => 'Kolom semester wajib diisi.',
            '*.semester.integer' => 'Kolom semester harus berupa angka.',
            '*.semester.min' => 'Semester minimal adalah 1.',
            '*.semester.max' => 'Semester maksimal adalah 8.',
            '*.jenis.required' => 'Kolom jenis wajib diisi.',
            '*.jenis.in' => 'Kolom jenis harus berisi salah satu dari: Wajib, Pilihan, Wajib Peminatan.',
        ];
    }
}