<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\StudentsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\OldStudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\DataTables\StudentDataTable;
use App\Models\Student; 

class StudentController extends Controller
{
    public function index(StudentDataTable $dataTable)
    {
        // Untuk saat ini, kita hanya menampilkan tabel.
        // Nanti bisa ditambahkan filter prodi, tahun masuk, dll.
        return $dataTable->render('admin.students.index');
    }

    public function showImportForm()
    {
        return view('admin.students.import');
    }

    public function importOld(Request $request)
    {
        $request->validate(['import_file' => 'required|file|mimes:xlsx,xls']);

        try {
            Excel::import(new OldStudentsImport, $request->file('import_file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->with('import_errors', $errorMessages);
        }

        return back()->with('success', 'Data mahasiswa lama berhasil diimpor!');
    }
    
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }
}