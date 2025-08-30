<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AcademicYearDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear; 
use Illuminate\Http\Request;
use App\Models\Program; 

class AcademicYearController extends Controller
{
    public function index(AcademicYearDataTable $dataTable)
    {
        return $dataTable->render('admin.academic-years.index');
    }

    public function create()
    {
        return view('admin.academic-years.create');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function show(AcademicYear $academicYear)
    {
        $programs = Program::orderBy('name_id')->get();
        return view('admin.academic-years.show', compact('academicYear', 'programs'));
    }
}