<?php

namespace App\Livewire\Dosen\Grade;

use App\Models\CourseClass;
use App\Models\ClassEnrollment;
use Livewire\Component;

class GradeInputForm extends Component
{
    public CourseClass $courseClass;
    public $enrollments;

    // Properti untuk menampung semua nilai
    public $grades = [];

    // Daftar nilai yang valid
    public $gradeOptions = [
        'A' => 4.00,
        'A-' => 3.75,
        'B+' => 3.50,
        'B' => 3.00,
        'B-' => 2.75,
        'C+' => 2.50,
        'C' => 2.00,
        'D' => 1.00,
        'E' => 0.00
    ];

    public function mount(CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
        $activeSemesterId = $this->courseClass->academic_year_id;

        // Logika BARU: Mengecek status KRS DAN status pembayaran
        $this->enrollments = ClassEnrollment::where('course_class_id', $this->courseClass->id)
            ->where('status', 'approved')
            // Tambahkan filter 'whereHas' untuk mengecek ke tabel lain
            ->whereHas('student', function ($query) use ($activeSemesterId) {
                // Mahasiswa tersebut harus memiliki relasi academicInvoices...
                $query->whereHas('academicInvoices', function ($q) use ($activeSemesterId) {
                    // ...di mana academic_year_id-nya cocok DAN statusnya 'paid' (lunas)
                    $q->where('academic_year_id', $activeSemesterId)
                        ->where('status', 'paid');
                });
            })
            ->with('student.user')
            ->get();

        // Isi properti $grades dengan nilai yang sudah ada di database
        foreach ($this->enrollments as $enrollment) {
            $this->grades[$enrollment->id] = $enrollment->grade_letter;
        }
    }

    public function saveGrades()
    {
        foreach ($this->grades as $enrollmentId => $gradeLetter) {
            if ($gradeLetter) {
                $enrollment = ClassEnrollment::find($enrollmentId);
                if ($enrollment) {
                    $enrollment->update([
                        'grade_letter' => $gradeLetter,
                        'grade_index' => $this->gradeOptions[$gradeLetter] ?? null,
                        // Di sini bisa ditambahkan logika untuk 'grade_score' jika perlu
                    ]);
                }
            }
        }

        session()->flash('success', 'Nilai berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.dosen.grade.grade-input-form');
    }
}
