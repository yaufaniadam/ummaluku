<?php

namespace App\Services\Dashboard;

use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Lecturer;
use App\Models\Student;

class AkademikDashboardService
{
    public function getDashboardData()
    {
        $activeSemester = AcademicYear::where('is_active', true)->first();

        $jumlahMahasiswaAktif = Student::where('status', 'active')->count();
        $jumlahDosenAktif = Lecturer::where('status', 'active')->count();
        $jumlahKelasDibuka = 0;
        $jumlahKrsPending = 0;

        if ($activeSemester) {
            $jumlahKelasDibuka = CourseClass::where('academic_year_id', $activeSemester->id)->count();
            $jumlahKrsPending = Student::whereHas('enrollments', function ($q) use ($activeSemester) {
                $q->where('academic_year_id', $activeSemester->id)
                  ->where('status', 'pending');
            })->distinct()->count();
        }

        return [
            'jumlahMahasiswaAktif' => $jumlahMahasiswaAktif,
            'jumlahDosenAktif' => $jumlahDosenAktif,
            'jumlahKelasDibuka' => $jumlahKelasDibuka,
            'jumlahKrsPending' => $jumlahKrsPending,
        ];
    }
}