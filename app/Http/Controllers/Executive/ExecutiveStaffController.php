<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\DataTables\ExecutiveStaffDataTable;

class ExecutiveStaffController extends Controller
{
    /**
     * Display a listing of staff (read-only for executive view)
     */
    public function index(ExecutiveStaffDataTable $dataTable)
    {
        return $dataTable->render('executive.staff.index');
    }

    /**
     * Display the specified staff profile (read-only)
     */
    public function show(Staff $staff)
    {
        return view('executive.staff.show', compact('staff'));
    }
}
