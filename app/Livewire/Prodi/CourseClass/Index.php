<?php

namespace App\Livewire\Prodi\CourseClass;

use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Lecturer;
use App\Services\CourseClassService;
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

    public function autoGenerate(CourseClassService $service)
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Akses ditolak. Program studi tidak ditemukan.']);
            return;
        }

        $program = $user->staff->program;
        $academicYear = AcademicYear::find($this->activeYearId);

        if (!$academicYear) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Tahun akademik tidak ditemukan.']);
            return;
        }

        $count = $service->autoGenerateClasses($academicYear, $program);

        if ($count > 0) {
            session()->flash('success', $count . ' kelas berhasil dibuat secara otomatis.');
        } else {
            session()->flash('warning', 'Tidak ada kelas baru yang dibuat. Mungkin semua kelas sudah ada atau kurikulum tidak aktif.');
        }
    }

    public function copyFromPrevious($sourceYearId, CourseClassService $service)
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program) {
             $this->dispatch('alert', ['type' => 'error', 'message' => 'Akses ditolak.']);
             return;
        }

        $program = $user->staff->program;
        $targetYear = AcademicYear::find($this->activeYearId);
        $sourceYear = AcademicYear::find($sourceYearId);

        if (!$targetYear || !$sourceYear) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Tahun akademik tidak valid.']);
            return;
        }

        $count = $service->copyClassesFromPreviousYear($targetYear, $sourceYear, $program);

        if ($count > 0) {
            session()->flash('success', $count . ' kelas berhasil disalin dari ' . $sourceYear->name . '.');
        } else {
            session()->flash('warning', 'Tidak ada kelas yang disalin.');
        }

        // Close modal logic handled by frontend or simple refresh
        $this->dispatch('close-modal');
    }

    public function render()
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Akses ditolak.');
        }

        $programId = $user->staff->program_id;

        $classes = CourseClass::whereHas('course.curriculums', function ($q) use ($programId) {
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
            ->with(['course.curriculums', 'lecturer'])
            ->paginate(10);

        $previousAcademicYears = AcademicYear::where('id', '!=', $this->activeYearId)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('livewire.prodi.course-class.index', [
            'classes' => $classes,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'previousAcademicYears' => $previousAcademicYears
        ])->extends('adminlte::page')->section('content');
    }
}
