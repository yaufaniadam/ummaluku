<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; // <-- Import Yajra
use Illuminate\Support\Facades\DB;

class AdminSeleksiController extends Controller
{
    /**
     * Menampilkan halaman utama Proses Seleksi.
     */
    public function index()
    {
        return view('admin.seleksi.index');
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function data()
    {
        // Ambil data pendaftar yang siap seleksi
        $query = Application::with(['prospective.user', 'programChoices.program'])
            ->where('status', 'lolos_verifikasi');

        return DataTables::of($query)
            // Tambah kolom nomor urut
            ->addIndexColumn()
            // Edit kolom pilihan prodi agar formatnya lebih rapi
            ->editColumn('program_choices', function ($application) {
                $choices = '';
                foreach ($application->programChoices as $choice) {
                    $choices .= '<div>Pilihan ' . $choice->choice_order . ': ' . $choice->program->name_id . '</div>';
                }
                return $choices;
            })
            // Tambah kolom baru untuk Aksi
            ->addColumn('action', function ($application) {
                // ---- Form untuk Aksi Terima ----
                $options = '<option value="">-- Pilih Prodi --</option>';
                foreach ($application->programChoices as $choice) {
                    $options .= '<option value="' . $choice->program_id . '">' . $choice->program->name_id . '</option>';
                }
                $acceptForm = '
                    <form action="' . route('admin.seleksi.accept', $application) . '" method="POST" class="d-inline-block">
                        ' . csrf_field() . '
                        <div class="input-group">
                            <select name="program_id" class="form-control form-control-sm" required>
                                ' . $options . '
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                        </div>
                    </form>
                ';

                            // ---- Form untuk Aksi Tolak ----
                            $rejectForm = '
                    <form action="' . route('admin.seleksi.reject', $application) . '" method="POST" class="d-inline-block ms-1">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin MENOLAK pendaftar ini?\')">Tolak</button>
                    </form>
                ';

                // Gabungkan kedua form
                return '<div class="d-flex">' . $acceptForm . $rejectForm . '</div>';
            })
            // Beritahu Yajra bahwa kolom 'program_choices' dan 'action' berisi HTML
            ->rawColumns(['program_choices', 'action'])
            ->make(true);
    }

    public function accept(Request $request, Application $application)
    {
        // 1. Validasi input dari form
        $request->validate([
            'program_id' => 'required|exists:programs,id'
        ]);

        $selectedProgramId = $request->input('program_id');

        // 2. Gunakan Transaction untuk keamanan data
        DB::transaction(function () use ($application, $selectedProgramId) {
            // Update status utama aplikasi
            $application->update(['status' => 'accepted']);

            // Update status di setiap pilihan prodi
            foreach ($application->programChoices as $choice) {
                $choice->update(['is_accepted' => ($choice->program_id == $selectedProgramId)]);
            }
        });

        // 3. Kembalikan ke halaman seleksi dengan pesan sukses
        return back()->with('success', 'Keputusan penerimaan untuk ' . $application->prospective->user->name . ' berhasil disimpan.');
    }
    public function reject(Application $application)
    {
        $application->update(['status' => 'rejected']);

        return back()->with('success', 'Pendaftar ' . $application->prospective->user->name . ' telah ditolak.');
    }
}
