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

    public function duplicate(Request $request)
    {
        $request->validate([
            'source_year' => 'required|digits:4',
            'target_year' => 'required|digits:4|different:source_year',
        ]);

        $sourceYear = $request->source_year;
        $targetYear = $request->target_year;

        // Ambil semua struktur biaya dari tahun sumber
        $sourceFees = FeeStructure::where('entry_year', $sourceYear)->get();

        if ($sourceFees->isEmpty()) {
            return back()->with('error', 'Tidak ada data biaya yang ditemukan untuk angkatan ' . $sourceYear);
        }

        // Hapus dulu data lama di tahun tujuan untuk menghindari duplikat error
        FeeStructure::where('entry_year', $targetYear)->delete();

        $newFeesCount = 0;
        foreach ($sourceFees as $fee) {
            // Buat record baru dengan tahun angkatan yang baru
            FeeStructure::create([
                'program_id' => $fee->program_id,
                'fee_component_id' => $fee->fee_component_id,
                'entry_year' => $targetYear,
                'amount' => $fee->amount,
            ]);
            $newFeesCount++;
        }

        return redirect()->route('admin.keuangan.tuition-fees.index')
                         ->with('success', $newFeesCount . ' struktur biaya dari angkatan ' . $sourceYear . ' berhasil disalin ke angkatan ' . $targetYear . '.');
    }
}
