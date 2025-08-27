<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseClassDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CourseClassDataTable $dataTable, AcademicYear $academicYear)
    {
        // 1. Set properti publiknya secara langsung
        $dataTable->academic_year_id = $academicYear->id;

        // 2. Render view
        return $dataTable->render('admin.course-classes.index', [
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AcademicYear $academicYear)
    {
        // Kirim data Tahun Ajaran ke view create
        return view('admin.course-classes.create', compact('academicYear'));
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
    public function show(CourseClass $courseClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear, CourseClass $courseClass)
    {
        return view('admin.course-classes.edit', compact('academicYear', 'courseClass'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseClass $courseClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseClass $courseClass)
    {
        //
    }
}
