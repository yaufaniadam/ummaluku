<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Student::with(['user', 'program', 'academicAdvisor.user'])
                          ->findOrFail(Auth::user()->student->id);
                          
        $activeSemester = AcademicYear::where('is_active', true)->first();

        // Menentukan Status KRS
        $krsStatus = 'Periode KRS Belum Dibuka';
        $krsStatusClass = 'secondary'; // Warna badge
        if ($activeSemester) {
            $enrollmentStatus = $student->enrollments()
                                        ->where('academic_year_id', $activeSemester->id)
                                        ->first()->status ?? 'belum_mengisi';
            
            switch ($enrollmentStatus) {
                case 'pending':
                    $krsStatus = 'Menunggu Persetujuan DPA';
                    $krsStatusClass = 'warning';
                    break;
                case 'approved':
                    $krsStatus = 'KRS Telah Disetujui';
                    $krsStatusClass = 'success';
                    break;
                case 'rejected':
                    $krsStatus = 'KRS Ditolak (Hubungi DPA)';
                    $krsStatusClass = 'danger';
                    break;
                default:
                    $krsStatus = 'Saatnya Mengisi KRS';
                    $krsStatusClass = 'primary';
                    break;
            }
        }

        // Ambil data dari method pintar di Model
        $currentSemester = $student->getCurrentSemester();
        $totalSks = $student->getTotalPassedSks();
        $ipk = $student->getCumulativeGpa();

        return view('mahasiswa.dashboard', compact(
            'student', 
            'activeSemester',
            'krsStatus',
            'krsStatusClass',
            'currentSemester',
            'totalSks',
            'ipk'
        ));
    }
}