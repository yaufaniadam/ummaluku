<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\DataTables\CourseDataTable;
use App\Imports\CoursesImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\Program;

class CourseController extends Controller
{
    /**     
     * Display a listing of the resource.
     */
    public function index(CourseDataTable $dataTable)
    {
        $programs = Program::orderBy('name_id')->get();
        return $dataTable->render('admin.courses.index', compact('programs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Curriculum $curriculum)
    {
        // Kirim data kurikulum ke view create
        return view('admin.courses.create', compact('curriculum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculum $curriculum, Course $course)
    {
        // Kirim data kurikulum dan mata kuliah yang akan diedit ke view
        return view('admin.courses.edit', compact('curriculum', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }

    public function import(Request $request)
    {
        $request->validate([
            'program_id' => 'required|numeric',
            'import_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new CoursesImport($request->program_id), $request->file('import_file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->with('import_errors', $errorMessages);
        }

        return redirect()->route('admin.akademik.courses.index')
            ->with('success', 'Data mata kuliah berhasil diimpor!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        Course::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }
}
