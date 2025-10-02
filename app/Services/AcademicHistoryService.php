<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;

class AcademicHistoryService
{
    public function getSemesterSummary(AcademicYear $academicYear)
    {
        // Ambil semua data pendaftaran (enrollment) yang sudah disetujui & dinilai di semester ini
        $enrollments = ClassEnrollment::where('academic_year_id', $academicYear->id)
            ->where('status', 'approved')
            ->whereNotNull('grade_index')
            ->with(['student.user', 'courseClass.course'])
            ->get();

        // 1. Hitung jumlah mahasiswa aktif
        $activeStudentsCount = $enrollments->pluck('student_id')->unique()->count();

        // 2. Hitung total SKS yang diambil
        $totalSksTaken = $enrollments->sum('courseClass.course.sks');

        // 3. Hitung Rata-rata IPS
        $ipsData = $enrollments->groupBy('student_id')->map(function ($studentEnrollments) {
            $totalWeight = $studentEnrollments->sum(function ($enrollment) {
                return $enrollment->grade_index * $enrollment->courseClass->course->sks;
            });
            $totalSks = $studentEnrollments->sum('courseClass.course.sks');
            return $totalSks > 0 ? $totalWeight / $totalSks : 0;
        });
        $averageIps = $ipsData->avg();

        // 4. Ambil mahasiswa berprestasi (Top 5)
        $topStudents = $ipsData->sortDesc()->take(5)->map(function ($ips, $studentId) {
            $student = \App\Models\Student::find($studentId);
            return [
                'name' => $student->user->name,
                'nim' => $student->nim,
                'ips' => $ips
            ];
        });

        return [
            'activeStudentsCount' => $activeStudentsCount,
            'totalSksTaken' => $totalSksTaken,
            'averageIps' => $averageIps,
            'topStudents' => $topStudents,
        ];
    }
}