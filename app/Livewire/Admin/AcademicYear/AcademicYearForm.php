<?php

namespace App\Livewire\Admin\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AcademicYearForm extends Component
{
    public ?AcademicYear $academicYear = null;

    // Properti Form
    public $year_code;
    public $name;
    public $semester_type;
    public $start_date;
    public $end_date;
    public $krs_start_date;
    public $krs_end_date;
    public $is_active = false;

    public function mount(AcademicYear $academicYear = null)
    {
        if ($academicYear->exists) {
            $this->academicYear = $academicYear;
            $this->year_code = $academicYear->year_code;
            $this->name = $academicYear->name;
            $this->semester_type = $academicYear->semester_type;
            $this->start_date = $academicYear->start_date->format('Y-m-d');
            $this->end_date = $academicYear->end_date->format('Y-m-d');
            $this->krs_start_date = $academicYear->krs_start_date->format('Y-m-d');
            $this->krs_end_date = $academicYear->krs_end_date->format('Y-m-d');
            $this->is_active = $academicYear->is_active;
        }
    }

    protected function rules()
    {
        $yearCodeUniqueRule = 'required|numeric|digits:5|unique:academic_years,year_code';
        if ($this->academicYear) {
            $yearCodeUniqueRule .= ',' . $this->academicYear->id;
        }

        return [
            'year_code' => $yearCodeUniqueRule,
            'name' => 'required|string',
            'semester_type' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'krs_start_date' => 'required|date',
            'krs_end_date' => 'required|date|after_or_equal:krs_start_date',
            'is_active' => 'required|boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // Logika penting: Jika ini di set 'Aktif', non-aktifkan yang lain
            if ($this->is_active) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }

            $data = [
                'year_code' => $this->year_code,
                'name' => $this->name,
                'semester_type' => $this->semester_type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'krs_start_date' => $this->krs_start_date,
                'krs_end_date' => $this->krs_end_date,
                'is_active' => $this->is_active,
            ];

            if ($this->academicYear) {
                $this->academicYear->update($data);
                session()->flash('success', 'Tahun Ajaran berhasil diperbarui.');
            } else {
                AcademicYear::create($data);
                session()->flash('success', 'Tahun Ajaran berhasil ditambahkan.');
            }
        });

        $this->dispatch('academic-year-updated');
        return redirect(route('admin.akademik.academic-years.index'));
    }

    public function render()
    {
        return view('livewire.admin.academic-year.academic-year-form');
    }
}