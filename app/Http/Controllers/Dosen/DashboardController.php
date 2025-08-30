<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;
        if (!$lecturer) {
            // Jika user dosen tidak terhubung ke data lecturer, tampilkan error
            abort(403, 'Data Dosen tidak ditemukan untuk user ini.');
        }

        $activeSemester = AcademicYear::where('is_active', true)->first();

        // Data untuk Statistik Cepat
        $totalAdvisedStudents = $lecturer->advisedStudents()->count();
        $totalClassesTaught = 0;
        $pendingKrsCount = 0;

        // Daftar Cepat & Informasi
        $pendingKrsStudents = collect();
        $classesTaught = collect();

        if ($activeSemester) {
            $totalClassesTaught = $lecturer->courseClasses()->where('academic_year_id', $activeSemester->id)->count();

            $pendingKrsStudentsQuery = $lecturer->advisedStudents()
                ->whereHas('enrollments', function ($q) use ($activeSemester) {
                    $q->where('academic_year_id', $activeSemester->id)
                      ->where('status', 'pending');
                });
            
            $pendingKrsCount = $pendingKrsStudentsQuery->count();
            // Ambil 5 mahasiswa terbaru untuk ditampilkan di daftar cepat
            $pendingKrsStudents = $pendingKrsStudentsQuery->with('user')->latest()->take(5)->get();

            $classesTaught = $lecturer->courseClasses()
                ->where('academic_year_id', $activeSemester->id)
                ->with('course')->get();
        }

        return view('dosen.dashboard', compact(
            'totalAdvisedStudents',
            'totalClassesTaught',
            'pendingKrsCount',
            'pendingKrsStudents',
            'classesTaught',
            'activeSemester'
        ));
    }
}