<?php

namespace App\Livewire\Prodi\CourseClass;

use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Course;
use App\Models\Lecturer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $activeYearId;

    public function mount()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $this->activeYearId = $activeYear ? $activeYear->id : null;
    }

    public function render()
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Akses ditolak.');
        }

        $programId = $user->staff->program_id;

        $classes = CourseClass::whereHas('course.curriculum', function ($q) use ($programId) {
                $q->where('program_id', $programId);
            })
            ->when($this->activeYearId, function ($q) {
                $q->where('academic_year_id', $this->activeYearId);
            })
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('course', function($sq) {
                      $sq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('code', 'like', '%' . $this->search . '%');
                  });
            })
            ->with(['course', 'lecturer'])
            ->paginate(10);

        return view('livewire.prodi.course-class.index', [
            'classes' => $classes,
            'academicYears' => AcademicYear::orderBy('start_year', 'desc')->get()
        ])->extends('adminlte::page')->section('content');
    }
}
