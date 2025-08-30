<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\TuitionFeeDataTable;
use App\Models\FeeStructure;
use App\Models\Student;

class TuitionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TuitionFeeDataTable $dataTable)
    {
        return $dataTable->render('admin.tuition-fees.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tuition-fees.create');
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeStructure $tuitionFee)
    {
        return view('admin.tuition-fees.edit', compact('tuitionFee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        // Logika akan ditangani oleh Livewire
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeStructure $tuitionFee)
    {
        // Cek apakah ada mahasiswa yang cocok dengan struktur biaya ini
        $studentExists = Student::where('program_id', $tuitionFee->program_id)
                                ->where('entry_year', $tuitionFee->entry_year)
                                ->exists();

        if ($studentExists) {
            // Jika ada mahasiswa, JANGAN HAPUS, kembalikan dengan pesan error
            return back()->with('error', 'Struktur biaya ini tidak dapat dihapus karena sudah ada mahasiswa yang terikat padanya.');
        }

        // Jika tidak ada mahasiswa, lanjutkan dengan soft delete
        $tuitionFee->delete();
        return back()->with('success', 'Struktur biaya berhasil dihapus.');
    }
}
