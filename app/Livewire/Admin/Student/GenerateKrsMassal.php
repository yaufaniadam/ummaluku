<?php

namespace App\Livewire\Admin\Student;

use App\Models\Program;
use App\Models\Student;
use App\Services\AutoEnrollmentService;
use Livewire\Component;

class GenerateKrsMassal extends Component
{
    public $programs;
    public $entryYears;

    public $selectedProgram = '';
    public $selectedYear = '';

    public $results = null;

    public function mount()
    {
        $this->programs = Program::orderBy('name_id')->get();
        $this->entryYears = Student::select('entry_year')->distinct()->orderBy('entry_year', 'desc')->pluck('entry_year');
    }

    public function generate()
    {
        $this->validate([
            'selectedProgram' => 'required',
            'selectedYear' => 'required',
        ], [
            'selectedProgram.required' => 'Program Studi harus dipilih.',
            'selectedYear.required' => 'Angkatan harus dipilih.',
        ]);

        $students = Student::where('program_id', $this->selectedProgram)
            ->where('entry_year', $this->selectedYear)
            ->where('status', 'active') // Hanya proses mahasiswa aktif
            ->get();

        if ($students->isEmpty()) {
            $this->results = [
                'status' => 'warning',
                'message' => 'Tidak ditemukan mahasiswa aktif untuk kriteria tersebut.'
            ];
            return;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($students as $student) {
            $result = AutoEnrollmentService::enrollStudent($student);
            if ($result['status'] === 'success') {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $this->results = [
            'status' => 'success',
            'message' => "Proses Selesai! Berhasil: {$successCount}, Gagal/Sudah Ada: {$failCount}"
        ];
    }

    public function render()
    {
        return view('livewire.admin.student.generate-krs-massal');
    }
}
