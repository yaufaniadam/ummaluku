<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassEnrollment;
use Barryvdh\DomPDF\Facade\Pdf;

class KrsController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $activeSemester = AcademicYear::where('is_active', true)->first();

        $enrollments = collect();
        $invoice = null;

        if ($activeSemester) {
            // Ambil data KRS yang sudah disetujui (approved)
            $enrollments = ClassEnrollment::where('student_id', $student->id)
                ->where('academic_year_id', $activeSemester->id)
                ->where('status', 'approved')
                ->with(['courseClass.course', 'courseClass.lecturer'])
                ->get();

            // Cari tagihan (invoice) untuk semester ini
            $invoice = $student->academicInvoices()
                ->where('academic_year_id', $activeSemester->id)
                ->first();
        }

        return view('mahasiswa.krs.index', compact('enrollments', 'activeSemester', 'invoice'));
    }

    public function prosesKrs()
    {
        return view('mahasiswa.krs.proses');
    }

    public function printPdf()
    {
        $student = Auth::user()->student;
        $activeSemester = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester) {
            return back()->with('error', 'Tidak ada semester aktif.');
        }

        // Ambil data KRS yang sudah disetujui
        $enrollments = ClassEnrollment::where('student_id', $student->id)
            ->where('academic_year_id', $activeSemester->id)
            ->where('status', 'approved')
            ->with(['courseClass.course', 'courseClass.lecturer'])
            ->get();

        // Siapkan data untuk dikirim ke view PDF
        $data = [
            'student' => $student,
            'enrollments' => $enrollments,
            'activeSemester' => $activeSemester,
            'totalSks' => $enrollments->sum('courseClass.course.sks')
        ];

        // Buat PDF dari view
        $pdf = Pdf::loadView('mahasiswa.krs.pdf', $data);

        // Tampilkan PDF di browser
        $safeFilename = str_replace('/', '-', $activeSemester->name);

        return $pdf->stream('KRS-' . $student->nim . '-' . $safeFilename . '.pdf');
    }
}
