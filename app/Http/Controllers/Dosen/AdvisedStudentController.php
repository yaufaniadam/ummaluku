<?php

namespace App\Http\Controllers\Dosen;

use App\DataTables\AdvisedStudentDataTable; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvisedStudentController extends Controller
{
    public function index(AdvisedStudentDataTable $dataTable)
    {
        return $dataTable->render('dosen.advised-students.index');
    }
}