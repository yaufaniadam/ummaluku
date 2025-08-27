<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AcademicYearDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear; // <-- Tambahkan ini
use Illuminate\Http\Request;

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
}