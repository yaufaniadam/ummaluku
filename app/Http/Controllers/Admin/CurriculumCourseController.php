<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Curriculum;
use Illuminate\Http\Request;

class CurriculumCourseController extends Controller
{
    public function index(Curriculum $curriculum)
    {
        // Eager load relasi 'courses' dan urutkan berdasarkan kolom 'semester' di tabel pivot
        $curriculum->load(['courses' => function ($query) {
            $query->orderBy('pivot_semester');
        }]);

        // Kelompokkan mata kuliah berdasarkan semester penempatannya
        $coursesBySemester = $curriculum->courses->groupBy('pivot.semester');

        return view('admin.curriculums.manage-courses', compact('curriculum', 'coursesBySemester'));
    }
    public function add(Curriculum $curriculum)
    {
        // Ambil ID mata kuliah yang sudah ada di kurikulum ini
        $existingCourseIds = $curriculum->courses()->pluck('courses.id')->toArray();

        // Ambil semua mata kuliah dari master, KECUALI yang sudah ada
        $allCourses = Course::whereNotIn('id', $existingCourseIds)->orderBy('name')->get();

        return view('admin.curriculums.add-courses', compact('curriculum', 'allCourses'));
    }

    public function store(Request $request, Curriculum $curriculum)
    {
        $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
            'courses.*.semester' => 'required|numeric|min:1|max:8',
            'courses.*.type' => 'required|in:Wajib,Pilihan',
        ]);

        $dataToSync = [];
        foreach ($request->courses as $course) {
            // Siapkan data untuk tabel pivot
            $dataToSync[$course['id']] = [
                'semester' => $course['semester'],
                'type' => $course['type'],
            ];
        }

        // 'attach' adalah method untuk menambahkan relasi many-to-many
        $curriculum->courses()->syncWithoutDetaching($dataToSync);

        return redirect()->route('admin.akademik.curriculums.courses.index', $curriculum->id)
            ->with('success', 'Mata kuliah berhasil ditambahkan ke kurikulum.');
    }

    public function destroy(Curriculum $curriculum, Course $course)
    {
        // 'detach' adalah method untuk menghapus relasi many-to-many
        $curriculum->courses()->detach($course->id);

        return redirect()->route('admin.akademik.curriculums.courses.index', $curriculum->id)
            ->with('success', 'Mata kuliah berhasil dihapus dari kurikulum.');
    }

    // public function bulkDestroy(Request $request, Curriculum $curriculum)
    // {
    //     // Validasi bahwa 'course_ids' yang dikirim adalah array
    //     $request->validate([
    //         'course_ids'   => 'required|array',
    //         'course_ids.*' => 'exists:courses,id', // Pastikan semua ID valid
    //     ]);

    //     // 'detach' bisa menerima array ID untuk menghapus banyak relasi sekaligus
    //     $curriculum->courses()->detach($request->course_ids);

    //     return redirect()->route('admin.akademik.curriculums.courses.index', $curriculum->id)
    //                      ->with('success', 'Mata kuliah yang dipilih berhasil dihapus dari kurikulum.');
    // }
}
