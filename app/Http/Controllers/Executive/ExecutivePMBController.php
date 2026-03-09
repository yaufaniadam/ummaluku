<?php

namespace App\Http\Controllers\Executive;

use App\DataTables\ExecutivePMBDataTable;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Batch;
use App\Models\Program;

class ExecutivePMBController extends Controller
{
    public function index(ExecutivePMBDataTable $dataTable)
    {
        $categories = AdmissionCategory::all();
        $batches = Batch::orderBy('year', 'desc')->orderBy('name')->get();
        $programs = Program::orderBy('name_id')->get();
        
        $statuses = [
            'pending' => 'Pending',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'sudah_registrasi' => 'Sudah Registrasi',
        ];

        return $dataTable->render('executive.pmb.index', compact('categories', 'batches', 'programs', 'statuses'));
    }
}
