<?php

namespace App\Livewire\Prodi;

use App\Models\AcademicYear;
use App\Models\Student;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendingKrsCount = 0;

    public function mount()
    {
        $user = auth()->user();
        if ($user->staff && $user->staff->program_id) {
            $activeSemester = AcademicYear::where('is_active', true)->first();

            // Count distinct students waiting for approval
            $this->pendingKrsCount = Student::where('program_id', $user->staff->program_id)
                ->whereHas('classEnrollments', function ($query) use ($activeSemester) {
                    $query->where('status', 'approved_advisor')
                          ->where('academic_year_id', $activeSemester?->id);
                })
                ->count();
        }
    }

    public function render()
    {
        return view('prodi.dashboard')->extends('adminlte::page')->section('content');
    }
}
