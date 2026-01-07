<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\DataTables\ExecutiveLecturerDataTable;

class ExecutiveLecturerController extends Controller
{
    /**
     * Display a listing of lecturers (read-only for executive view)
     */
    public function index(ExecutiveLecturerDataTable $dataTable)
    {
        return $dataTable->render('executive.lecturers.index');
    }

    /**
     * Display the specified lecturer profile (read-only)
     */
    public function show(Lecturer $lecturer)
    {
        return view('executive.lecturers.show', compact('lecturer'));
    }
}
