<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseClassDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecturer;

class CourseClassController extends Controller
{
    // app/Http/Controllers/Admin/CourseClassController.php

    public function index(AcademicYear $academicYear, Program $program)
    {
        // --- AWAL PEROMBAKAN LOGIKA ---

        // 1. Cari kurikulum yang aktif untuk program studi ini
        $activeCurriculum = $program->curriculums()->where('is_active', true)->first();

        // Inisialisasi koleksi kosong
        $availableCourses = collect();

        if ($activeCurriculum) {
            // 2. Tentukan semester mana yang harus ditampilkan (ganjil atau genap)
            $semesterTypes = ($academicYear->semester_type === 'Ganjil')
                ? [1, 3, 5, 7]
                : [2, 4, 6, 8];

            // 3. Ambil ID dari kelas yang sudah dibuat agar tidak duplikat
            $existingCourseIds = CourseClass::where('academic_year_id', $academicYear->id)
                ->whereHas('course.curriculums', function ($q) use ($activeCurriculum) {
                    $q->where('curriculum_id', $activeCurriculum->id);
                })
                ->pluck('course_id');

            // 4. Ambil mata kuliah dari KURIKULUM AKTIF, filter berdasarkan JENIS SEMESTER, dan buang yang sudah ada kelasnya
            $availableCourses = $activeCurriculum->courses()
                ->whereIn('curriculum_course.semester', $semesterTypes) // <-- INI PERBAIKANNYA
                ->whereNotIn('courses.id', $existingCourseIds)
                ->orderBy('curriculum_course.semester', 'asc') 
                ->get();
        }

        // --- AKHIR PEROMBAKAN LOGIKA ---

        // Query untuk kelas yang sudah dibuat (logika ini tetap sama)
        $createdClasses = CourseClass::where('academic_year_id', $academicYear->id)
            ->whereHas('course', function ($q) use ($program) {
                $q->where('program_id', $program->id);
            })
            ->with(['course', 'lecturer'])
            ->get();

        // Ambil daftar dosen dari prodi ini (logika ini tetap sama)
        $lecturers = Lecturer::where('program_id', 'like', "%{$program->id}%")->orderBy('full_name_with_degree')->get();

        return view('admin.course-classes.index', compact(
            'academicYear',
            'program',
            'availableCourses',
            'createdClasses',
            'lecturers',
            'activeCurriculum' // Kirim data kurikulum aktif ke view untuk info
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AcademicYear $academicYear, Program $program)
    {
        return view('admin.course-classes.create', compact('academicYear', 'program'));
    }

    public function quickCreate(Request $request, AcademicYear $academicYear, Program $program, Course $course)
    {
        $request->validate([
            'lecturer_id' => 'required|exists:lecturers,id',
        ]);

        CourseClass::create([
            'academic_year_id' => $academicYear->id,
            'course_id' => $course->id,
            'lecturer_id' => $request->lecturer_id,
            'name' => 'A', // Nama kelas default
            'capacity' => 40, // Kapasitas default
        ]);

        return back()->with('success', 'Kelas untuk mata kuliah ' . $course->name . ' berhasil dibuat.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear, Program $program, $course_class_id)
    {
        // Cari manual model CourseClass berdasarkan ID yang didapat dari route
        $courseClass = CourseClass::findOrFail($course_class_id);

        return view('admin.course-classes.edit', compact('academicYear', 'program', 'courseClass'));
    }
}
