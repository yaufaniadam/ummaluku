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
use App\Services\CourseClassService;

class CourseClassController extends Controller
{
    protected $courseClassService;

    public function __construct(CourseClassService $courseClassService)
    {
        $this->courseClassService = $courseClassService;
    }

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

        // // Query untuk kelas yang sudah dibuat (logika ini tetap sama)
        // $createdClasses = CourseClass::where('academic_year_id', $academicYear->id)
        //     ->whereHas('course', function ($q) use ($program) {
        //         $q->where('program_id', $program->id);
        //     })
        //     ->with(['course', 'lecturer'])
        //     ->get();

        $createdClassesQuery = CourseClass::where('academic_year_id', $academicYear->id)
            ->whereHas('course', function ($q) use ($program) {
                $q->where('program_id', $program->id);
            })
            ->with(['course.curriculums', 'lecturer']) // Eager load relasi yang diperlukan
            ->get();

        // Kelompokkan kelas yang sudah dibuat berdasarkan semester penempatannya di kurikulum
        $createdClassesBySemester = $createdClassesQuery->groupBy(function ($class) {
            // Cari data pivot dari kurikulum yang aktif
            $curriculum = $class->course->curriculums->where('program_id', $class->course->program_id)->where('is_active', true)->first();
            return $curriculum->pivot->semester ?? 0; // Kelompokkan berdasarkan semester, 0 jika belum diatur
        })->sortKeys();

        // Ambil daftar dosen dari prodi ini (logika ini tetap sama)
        $lecturers = Lecturer::where('program_id', 'like', "%{$program->id}%")->orderBy('full_name_with_degree')->get();

        // --- DATA UNTUK FITUR COPY --
        // Ambil tahun ajaran lain untuk opsi copy (kecuali tahun ini)
        $previousAcademicYears = AcademicYear::where('id', '!=', $academicYear->id)
            ->orderBy('year_code', 'desc')
            ->get();

        return view('admin.course-classes.index', compact(
            'academicYear',
            'program',
            'availableCourses',
            'createdClassesBySemester',
            'lecturers',
            'activeCurriculum', // Kirim data kurikulum aktif ke view untuk info
            'previousAcademicYears'
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

    public function autoGenerate(AcademicYear $academicYear, Program $program)
    {
        $count = $this->courseClassService->autoGenerateClasses($academicYear, $program);

        if ($count > 0) {
            return back()->with('success', $count . ' kelas berhasil dibuat secara otomatis dari kurikulum.');
        } else {
            return back()->with('warning', 'Tidak ada kelas baru yang dibuat. Mungkin semua kelas sudah ada atau kurikulum tidak aktif.');
        }
    }

    public function copyFromPrevious(Request $request, AcademicYear $academicYear, Program $program)
    {
        $request->validate([
            'source_academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $sourceYear = AcademicYear::findOrFail($request->source_academic_year_id);

        $count = $this->courseClassService->copyClassesFromPreviousYear($academicYear, $sourceYear, $program);

        if ($count > 0) {
            return back()->with('success', $count . ' kelas berhasil disalin dari ' . $sourceYear->name . '.');
        } else {
            return back()->with('warning', 'Tidak ada kelas yang disalin. Mungkin kelas sudah ada di tahun ajaran ini.');
        }
    }
}
