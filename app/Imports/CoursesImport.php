<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoursesImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $curriculumId;

    public function __construct(int $curriculumId)
    {
        $this->curriculumId = $curriculumId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Course([
            'curriculum_id'           => $this->curriculumId,
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
}