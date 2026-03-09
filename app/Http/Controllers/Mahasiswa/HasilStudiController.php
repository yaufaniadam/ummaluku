<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilStudiController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        // Ambil SEMUA riwayat studi mahasiswa yang sudah disetujui
        $allEnrollments = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'approved') // Hanya yang statusnya approved
            ->with(['academicYear', 'courseClass.course'])
            ->orderBy('academic_year_id', 'desc')
            ->get();

        // Ambil daftar semester unik untuk dropdown filter
        // Menggunakan unique('academic_year_id') dan pluck relation
        $semesters = $allEnrollments->pluck('academicYear')->unique('id')->values();

        // Tangkap input filter
        $selectedSemesterId = $request->input('semester_id');

        // Jika ada filter, persempit collection enrollments
        $filteredEnrollments = $allEnrollments;
        if ($selectedSemesterId) {
            $filteredEnrollments = $allEnrollments->where('academic_year_id', $selectedSemesterId);
        }

        // Kelompokkan riwayat tersebut berdasarkan nama Tahun Ajaran (Semester)
        $enrollmentsBySemester = $filteredEnrollments->groupBy(function ($enrollment) {
            return $enrollment->academicYear->name;
        });

        // Hitung IPK dan ringkasan lainnya (Global, bukan berdasarkan filter)
        // IPK tetap kumulatif
        $ipk = $student->getCumulativeGpa();

        // Total SKS dan Courses ditampilkan berdasarkan filter (atau global jika tidak difilter)
        // UX: Biasanya ringkasan atas adalah kumulatif, tapi jika difilter mungkin user ingin lihat ringkasan filter?
        // Namun labelnya "IP Kumulatif", jadi IPK tetap global.
        // "Jumlah SKS Diambil" bisa ambigu. Mari kita buat SKS Total Kumulatif tetap global agar konsisten dengan IPK.
        // Tidak lagi difilter berdasarkan kurikulum aktif atau nilai yang sudah ada
        $allEnrollments = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'approved') // Hanya yang statusnya approved
            ->with(['academicYear', 'courseClass.course'])
            ->orderBy('academic_year_id', 'desc') // Urutkan semester terbaru di atas (sesuai UX umum)
            ->get();

        // Kelompokkan riwayat tersebut berdasarkan nama Tahun Ajaran (Semester)
        // Gunakan nama akademik tahun sebagai key
        $enrollmentsBySemester = $allEnrollments->groupBy(function ($enrollment) {
            return $enrollment->academicYear->name;
        });

        // Hitung IPK dan ringkasan lainnya
        // Method getCumulativeGpa di model Student sudah menangani null values (hanya menghitung yang ada nilainya)
        $ipk = $student->getCumulativeGpa();
        
        $totalSksTaken = $allEnrollments->sum('courseClass.course.sks');
        $totalCoursesTaken = $allEnrollments->count();

        return view('mahasiswa.hasil-studi.index', compact(
            'student',
            'enrollmentsBySemester',
            'ipk',
            'totalSksTaken',
            'totalCoursesTaken',
            'semesters',
            'selectedSemesterId'
        ));
        return view('mahasiswa.hasil-studi.index', compact('student','enrollmentsBySemester', 'ipk', 'totalSksTaken', 'totalCoursesTaken'));
    }
}
