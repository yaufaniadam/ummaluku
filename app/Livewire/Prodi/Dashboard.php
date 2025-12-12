<?php

namespace App\Livewire\Prodi;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Course;
use App\Models\CourseClass;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendingKrsCount = 0;
    public $totalActiveStudents = 0;
    public $activeClassesCount = 0;
    public $totalCoursesCount = 0;
    public $programName = '';

    public function mount()
    {
        $user = auth()->user();

        // Eager load staff relationship to ensure it's available
        $user->load('staff.program');

        if ($user->staff && $user->staff->program_id) {
            $programId = $user->staff->program_id;
            $this->programName = $user->staff->program->name ?? '';

            $activeSemester = AcademicYear::where('is_active', true)->first();

            // Count distinct students waiting for approval
            $this->pendingKrsCount = Student::where('program_id', $programId)
                ->whereHas('enrollments', function ($query) use ($activeSemester) {
                    $query->where('status', 'approved_advisor')
                          ->where('academic_year_id', $activeSemester?->id);
                })
                ->count();

            // Total Active Students
            $this->totalActiveStudents = Student::where('program_id', $programId)
                ->where('status', 'Aktif')
                ->count();

            // Active Classes (in current semester)
            if ($activeSemester) {
                $this->activeClassesCount = CourseClass::where('academic_year_id', $activeSemester->id)
                    ->whereHas('course', function ($q) use ($programId) {
                        $q->where('program_id', $programId);
                    })
                    ->count();
            }

            // Total Courses
            $this->totalCoursesCount = Course::where('program_id', $programId)->count();
        }
    }

    public function render()
    {
        return view('livewire.prodi.dashboard')->extends('adminlte::page')->section('content');
    }
}
