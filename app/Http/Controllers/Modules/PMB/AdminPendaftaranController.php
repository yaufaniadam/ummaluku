<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ApplicationsDataTable;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\Batch;

class AdminPendaftaranController extends Controller
{
    /**
     * Menampilkan halaman utama untuk daftar pendaftar.
     */
    public function index(ApplicationsDataTable $dataTable)
    {
        // Siapkan data untuk dropdown filter
        $categories = AdmissionCategory::all(); 
        $batches = Batch::all();
        // Daftar status yang mungkin ada
        $statuses = ['menunggu_pembayaran','menunggu_data_lengkap','menunggu_upload_dokumen', 'menunggu_verifikasi', 'lolos_verifikasi', 'diterima', 'ditolak'];

        // Render DataTable dan kirim data filter ke view
        return $dataTable->render('admin.pendaftaran.index', compact('categories', 'batches', 'statuses'));
    }

    public function show(Application $application)
    {
        
        return view('admin.pendaftaran.show'); // <-- livewire dipanggil di blade ini
    }
}
