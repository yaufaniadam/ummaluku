<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Application;
use App\Models\Program;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveDashboardController extends Controller
{
    public function index()
    {
        // 1. Data Mahasiswa
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'Aktif')->count();

        // Mahasiswa per Prodi (Chart Data)
        $studentsPerProgram = Program::withCount('students')->get()->map(function ($program) {
            return [
                'label' => $program->name_id,
                'value' => $program->students_count
            ];
        });

        // 2. Data SDM
        $totalLecturers = Lecturer::count();
        $totalTendik = User::role('Tendik')->count();

        // 3. Data PMB (Tahun ini/Batch Aktif bisa ditambahkan filternya nanti)
        $totalApplicants = Application::count();
        $acceptedApplicants = Application::where('status', 'diterima')->orWhere('status', 'sudah_registrasi')->count();

        // 4. Data Fakultas/Prodi
        $totalFaculties = Faculty::count();
        $totalPrograms = Program::count();

        // Data untuk Chart Status Mahasiswa
        $studentStatusStats = Student::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.executive.dashboard', compact(
            'totalStudents',
            'activeStudents',
            'totalLecturers',
            'totalTendik',
            'totalApplicants',
            'acceptedApplicants',
            'studentsPerProgram',
            'totalFaculties',
            'totalPrograms',
            'studentStatusStats'
        ));
    }
}
