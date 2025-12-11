<?php

namespace App\Livewire\Prodi\Krs;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $user = auth()->user();

        // Ensure user is staff and has a program
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Anda tidak terdaftar sebagai staf prodi.');
        }

        $programId = $user->staff->program_id;
        $activeSemester = AcademicYear::where('is_active', true)->first();

        $students = Student::query()
            ->where('program_id', $programId)
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->whereHas('classEnrollments', function ($query) use ($activeSemester) {
                $query->where('status', 'approved_advisor')
                      ->where('academic_year_id', $activeSemester?->id);
            })
            ->with(['user', 'program'])
            ->paginate(10);

        return view('livewire.prodi.krs.index', [
            'students' => $students,
            'activeSemester' => $activeSemester
        ])->extends('adminlte::page')->section('content');
    }
}
