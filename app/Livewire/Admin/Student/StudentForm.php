<?php

namespace App\Livewire\Admin\Student;

use App\Models\Lecturer;
use App\Models\Student;
use Livewire\Component;

class StudentForm extends Component
{
    public Student $student;
    public $lecturers;

    // Properti yang bisa diedit
    public $status;
    public $academic_advisor_id;

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->lecturers = Lecturer::where('status', 'active')->orderBy('full_name_with_degree')->get();

        // Isi properti form dengan data yang ada
        $this->status = $student->status;
        $this->academic_advisor_id = $student->academic_advisor_id;
    }

    protected function rules()
    {
        return [
            'status' => 'required|in:active,on_leave,graduated,dropped_out',
            'academic_advisor_id' => 'nullable|exists:lecturers,id',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->student->update([
            'status' => $this->status,
            'academic_advisor_id' => $this->academic_advisor_id,
        ]);

        // Kirim event agar tabel di halaman index me-refresh
        $this->dispatch('student-updated');

        session()->flash('success', 'Data mahasiswa berhasil diperbarui.');

        return redirect(route('admin.akademik.students.index'));
    }

    public function render()
    {
        return view('livewire.admin.student.student-form');
    }
}