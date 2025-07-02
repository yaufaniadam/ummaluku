<?php

namespace App\Http\Controllers\Modules\PMB;

use App\DataTables\AcceptedStudentsDataTable;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Batch;
use App\Models\Program;

class AcceptedStudentController extends Controller
{
    public function index(AcceptedStudentsDataTable $dataTable)
    {
        $categories = AdmissionCategory::all();
        $batches = Batch::all();
        $programs = Program::all();

        // Data baru untuk filter status pembayaran
        $payment_statuses = [
            'unpaid' => 'Belum Dibayar',
            'partially_paid' => 'Dibayar Sebagian',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
        ];


        return $dataTable->render('admin.accepted.index', compact('categories', 'batches', 'programs', 'payment_statuses'));
    }
    
}