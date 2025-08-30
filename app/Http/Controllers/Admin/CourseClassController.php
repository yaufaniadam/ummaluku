<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseClassDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CourseClassDataTable $dataTable, AcademicYear $academicYear, Program $program)
    {
        $dataTable->academic_year_id = $academicYear->id;
        $dataTable->program_id = $program->id;
        return $dataTable->render('admin.course-classes.index', compact('academicYear', 'program'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AcademicYear $academicYear, Program $program)
    {
        return view('admin.course-classes.create', compact('academicYear', 'program'));
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
    public function edit(AcademicYear $academicYear, Program $program, $course_class_id)
    {
        // Cari manual model CourseClass berdasarkan ID yang didapat dari route
        $courseClass = CourseClass::findOrFail($course_class_id);

        return view('admin.course-classes.edit', compact('academicYear', 'program', 'courseClass'));
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
