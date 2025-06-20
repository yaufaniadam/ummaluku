<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ApplicationsDataTable;
use App\Models\AdmissionCategory;
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
        $statuses = ['awaiting_payment', 'awaiting_verification', 'ready_for_selection', 'accepted', 'rejected'];

        // Render DataTable dan kirim data filter ke view
        return $dataTable->render('admin.pendaftaran.index', compact('categories', 'batches', 'statuses'));
    }
}
