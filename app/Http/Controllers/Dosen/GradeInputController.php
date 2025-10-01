<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeInputController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;
        $activeSemester = AcademicYear::where('is_active', true)->first();
        
        $classes = collect(); // Default koleksi kosong

        if ($activeSemester) {
            $classes = CourseClass::where('lecturer_id', $lecturer->id)
                ->where('academic_year_id', $activeSemester->id)
                ->with('course')
                // Menghitung jumlah mahasiswa yang KRS-nya sudah di-approve
                ->withCount(['enrollments' => function ($query) {
                    $query->where('status', 'approved');
                }])
                ->get();
        }

        return view('dosen.grades.index', compact('classes', 'activeSemester'));
    }

    public function show(CourseClass $course_class)
    {
        // Keamanan: Pastikan dosen yang login adalah pengampu kelas ini
        if ($course_class->lecturer_id !== auth()->user()->lecturer->id) {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        return view('dosen.grades.show', ['courseClass' => $course_class]);
    }
}