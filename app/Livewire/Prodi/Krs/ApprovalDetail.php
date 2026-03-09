<?php

namespace App\Livewire\Prodi\Krs;

use App\Events\KrsApproved;
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

        // Check if student belongs to Kaprodi's program
        $user = auth()->user();
        if ($student->program_id !== $user->staff->program_id) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }

        if ($this->activeSemester) {
            $this->enrollments = ClassEnrollment::where('student_id', $this->student->id)
                ->where('academic_year_id', $this->activeSemester->id)
                ->where('status', 'approved_advisor') // Only show those approved by advisor
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

        // Kaprodi might not have a lecturer_id if they are purely staff (though unlikely).
        // We'll use lecturer_id if available, else null, or maybe we should use user_id if DB supported it.
        // DB `approved_by` references `lecturers`.
        $lecturerId = Auth::user()->lecturer?->id;

        foreach ($this->enrollments as $enrollment) {
            $enrollment->update([
                'status' => 'approved',
                'approved_by' => $lecturerId,
            ]);
        }

        // Dispatch final approval event
        KrsApproved::dispatch($this->student, $this->activeSemester);

        session()->flash('success', 'KRS untuk mahasiswa ' . $this->student->user->name . ' berhasil disetujui Kaprodi.');
        return $this->redirect(route('prodi.krs-approval.index'), navigate: true);
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
        return $this->redirect(route('prodi.krs-approval.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.prodi.krs.approval-detail')->extends('adminlte::page')->section('content');
    }
}
