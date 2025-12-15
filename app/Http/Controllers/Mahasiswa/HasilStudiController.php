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

        // Ambil SEMUA riwayat studi mahasiswa yang sudah disetujui
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

        return view('mahasiswa.hasil-studi.index', compact('student','enrollmentsBySemester', 'ipk', 'totalSksTaken', 'totalCoursesTaken'));
    }
}
