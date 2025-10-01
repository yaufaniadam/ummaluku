<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilStudiController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        // 1. Cari kurikulum aktif yang berlaku untuk mahasiswa
        $activeCurriculum = $student->program->curriculums()->where('is_active', true)->first();

        // Jika tidak ada kurikulum, kirim data kosong untuk mencegah error
        if (!$activeCurriculum) {
            return view('mahasiswa.hasil-studi.index', [
                'enrollmentsBySemester' => collect(),
                'ipk' => 0,
                'totalSksTaken' => 0,
                'totalCoursesTaken' => 0,
            ]);
        }

        // 2. Ambil daftar ID mata kuliah yang ada di dalam kurikulum aktif tersebut
        $curriculumCourseIds = $activeCurriculum->courses()->pluck('courses.id')->toArray();

        // 3. Ambil SEMUA riwayat nilai mahasiswa...
        $allEnrollments = ClassEnrollment::where('student_id', $student->id)
            ->whereNotNull('grade_letter')
            // ...TAPI HANYA untuk mata kuliah yang ada di dalam kurikulum aktif
            ->whereHas('courseClass.course', function ($query) use ($curriculumCourseIds) {
                $query->whereIn('id', $curriculumCourseIds);
            })
            ->with(['academicYear', 'courseClass.course'])
            ->orderBy('academic_year_id', 'asc')
            ->get();

        // 4. Kelompokkan riwayat tersebut berdasarkan semester (Tahun Ajaran)
        $enrollmentsBySemester = $allEnrollments->groupBy('academicYear.name');
        
        // 5. Hitung IPK dan ringkasan lainnya
        $ipk = $student->getCumulativeGpa(); // Method ini sudah ada di model Student
        $totalSksTaken = $allEnrollments->sum('courseClass.course.sks');
        $totalCoursesTaken = $allEnrollments->count();

        // Kirim semua data yang sudah difilter ke view
        return view('mahasiswa.hasil-studi.index', compact('student','enrollmentsBySemester', 'ipk', 'totalSksTaken', 'totalCoursesTaken'));
    }
}