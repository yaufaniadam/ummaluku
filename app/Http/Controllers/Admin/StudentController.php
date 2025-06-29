<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\StudentsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(StudentsDataTable $dataTable)
    {
        // Untuk saat ini, kita hanya menampilkan tabel.
        // Nanti bisa ditambahkan filter prodi, tahun masuk, dll.
        return $dataTable->render('admin.students.index');
    }
}