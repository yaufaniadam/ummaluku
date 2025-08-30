<?php

namespace App\Http\Controllers\Dosen;

use App\DataTables\KrsApprovalDataTable;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KrsApprovalController extends Controller
{
    public function index(KrsApprovalDataTable $dataTable)
    {
        return $dataTable->render('dosen.krs-approval.index');
    }

    public function show(Student $student)
    {
        // Pastikan dosen yang login adalah DPA dari mahasiswa ini (keamanan)
        if ($student->academic_advisor_id !== auth()->user()->lecturer->id) {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        return view('dosen.krs-approval.show', compact('student'));
    }
}