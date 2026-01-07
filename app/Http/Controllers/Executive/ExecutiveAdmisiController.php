<?php

namespace App\Http\Controllers\Executive;

use App\DataTables\ExecutiveAcceptedStudentsDataTable;
use App\Http\Controllers\Controller;

class ExecutiveAdmisiController extends Controller
{
    public function index(ExecutiveAcceptedStudentsDataTable $dataTable)
    {
        return $dataTable->render('executive.admisi.index');
    }
}
