<?php

namespace App\Livewire\Dosen\Krs;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApprovalDetail extends Component
{
    public Student $student;
    public $enrollments;
    public $totalSks = 0;
    public ?AcademicYear $activeSemester;

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->activeSemester = AcademicYear::where('is_active', true)->first();

        if ($this->activeSemester) {
            $this->enrollments = ClassEnrollment::where('student_id', $this->student->id)
                ->where('academic_year_id', $this->activeSemester->id)
                ->where('status', 'pending')
                ->with(['courseClass.course', 'courseClass.lecturer'])
                ->get();

            $this->totalSks = $this->enrollments->sum('courseClass.course.sks');
        }
    }

    public function approveKrs()
    {
        if ($this->enrollments->isEmpty()) {
            return;
        }

        $lecturerId = Auth::user()->lecturer->id;

        foreach ($this->enrollments as $enrollment) {
            $enrollment->update([
                'status' => 'approved',
                'approved_by' => $lecturerId,
            ]);
        }

        session()->flash('success', 'KRS untuk mahasiswa ' . $this->student->user->name . ' berhasil disetujui.');
        return $this->redirect(route('dosen.krs-approval.index'), navigate: true);
    }

    public function rejectKrs()
    {
        if ($this->enrollments->isEmpty()) {
            return;
        }

        foreach ($this->enrollments as $enrollment) {
            $enrollment->update(['status' => 'rejected']);
        }

        session()->flash('success', 'KRS untuk mahasiswa ' . $this->student->user->name . ' telah ditolak.');
        return $this->redirect(route('dosen.krs-approval.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.dosen.krs.approval-detail');
    }
}