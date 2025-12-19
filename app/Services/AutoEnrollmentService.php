<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\CourseClass;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AutoEnrollmentService
{
    /**
     * Auto-enroll a student into courses based on their semester and active curriculum.
     *
     * @param Student $student
     * @return array Result message and status
     */
    public static function enrollStudent(Student $student): array
    {
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeAcademicYear) {
            return ['status' => 'error', 'message' => 'Tidak ada tahun ajaran aktif.'];
        }

        $semester = $student->getCurrentSemester();

        // Ambil kurikulum aktif untuk prodi mahasiswa
        $activeCurriculum = $student->program->curriculums()
            ->where('is_active', true)
            ->first();

        if (!$activeCurriculum) {
            return ['status' => 'error', 'message' => 'Tidak ada kurikulum aktif untuk program studi ini.'];
        }

        // Ambil mata kuliah untuk semester mahasiswa saat ini
        $courses = $activeCurriculum->courses()
            ->wherePivot('semester', $semester)
            ->get();

        if ($courses->isEmpty()) {
            return ['status' => 'error', 'message' => "Tidak ada mata kuliah ditemukan untuk semester {$semester} di kurikulum aktif."];
        }

        $enrolledCount = 0;

        DB::transaction(function () use ($student, $activeAcademicYear, $courses, &$enrolledCount) {
            foreach ($courses as $course) {
                // 1. Cari atau Buat Kelas
                // Kita coba cari kelas "A" dulu, atau kelas apapun yang available untuk matkul ini
                // Strategi sederhananya: Find or Create 'Kelas A'

                $courseClass = CourseClass::firstOrCreate(
                    [
                        'academic_year_id' => $activeAcademicYear->id,
                        'course_id' => $course->id,
                        'name' => 'A', // Default nama kelas
                    ],
                    [
                        'capacity' => 50,
                        'lecturer_id' => null, // Dosen bisa diassign belakangan
                    ]
                );

                // 2. Daftarkan Mahasiswa (Enroll)
                // Cek dulu apakah sudah terdaftar (supaya tidak duplikat)
                $enrollment = ClassEnrollment::where('student_id', $student->id)
                    ->where('academic_year_id', $activeAcademicYear->id)
                    ->whereHas('courseClass', function($q) use ($course) {
                        $q->where('course_id', $course->id);
                    })
                    ->first();

                if (!$enrollment) {
                    ClassEnrollment::create([
                        'student_id' => $student->id,
                        'course_class_id' => $courseClass->id,
                        'academic_year_id' => $activeAcademicYear->id,
                        'status' => 'approved', // Langsung Approved karena ini "Paket"
                    ]);
                    $enrolledCount++;
                }
            }
        });

        return [
            'status' => 'success',
            'message' => "Berhasil mendaftarkan mahasiswa ke {$enrolledCount} mata kuliah untuk semester {$semester}."
        ];
    }
}
