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
use App\Models\Program; 
use App\Models\ClassEnrollment;

class StudentController extends Controller
{
    public function index(StudentDataTable $dataTable)
    {
        // Ambil semua prodi untuk dropdown filter
        $programs = Program::orderBy('name_id')->get(); //

        // Ambil semua tahun angkatan yang unik dari tabel students
        $entryYears = Student::select('entry_year')->distinct()->orderBy('entry_year', 'desc')->pluck('entry_year');

        // Kirim data ke view
        return $dataTable->render('admin.students.index', compact('programs', 'entryYears'));
    }

    public function showImportForm()
    {
        return view('admin.students.import');
    }

    public function show(Student $student)
    {
        // Eager load semua relasi yang dibutuhkan untuk ditampilkan
        $student->load(['user.prospective', 'program', 'academicAdvisor.user', 'enrollments.courseClass.course']);
        
        // Ambil semua riwayat pendaftaran kelas yang sudah ada nilainya
        $allEnrollments = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'approved') // Hanya yang disetujui
            ->with(['academicYear', 'courseClass.course'])
            ->orderBy('academic_year_id', 'asc')
            ->get();
        
            // Kelompokkan riwayat tersebut berdasarkan semester (Tahun Ajaran)
        $enrollmentsBySemester = $allEnrollments->groupBy('academicYear.name');

        // Hitung IPK
       $ipk = $student->getCumulativeGpa();

        // --- AKHIR LOGIKA BARU ---
        
        return view('admin.students.show', compact('student', 'enrollmentsBySemester', 'ipk'));
   
    }

    public function importOld(Request $request)
    {
        $request->validate(['import_file' => 'required|mimes:csv']);

        try {
            Excel::import(new OldStudentsImport, $request->file('import_file'));

             return back()->with('success', 'Data mahasiswa lama berhasil diimpor!');
             
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->with('import_errors', $errorMessages);
        }

       
    }
    
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }
}