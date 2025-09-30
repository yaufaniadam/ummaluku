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

        // ====================== LOGIKA BARU UNTUK CEK PROFIL ======================
        $isProfileComplete = true;
        $prospectiveData = $student->user->prospective;

        // Tentukan kolom apa saja yang wajib diisi.
        // Jika salah satu dari kolom ini kosong, profil dianggap belum lengkap.
        if (
            empty($prospectiveData->nik) ||
            empty($prospectiveData->birth_place) ||
            empty($prospectiveData->address) ||
            empty($prospectiveData->father_name)
        ) {
            $isProfileComplete = false;
        }
        // =======================================================================

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
            'ipk',
            'isProfileComplete' 
        ));
    }
}