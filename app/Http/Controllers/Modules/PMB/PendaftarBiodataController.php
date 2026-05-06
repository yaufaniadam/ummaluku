<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarBiodataController extends Controller
{
    public function showDashboard()
    {
        // Kita beri nama view 'pendaftar.dashboard-page' agar tidak bingung
        // dengan view komponen livewire
        return view('pendaftar.biodata-page');
    }

    public function showDocumentUploadForm()
    {
        // Ambil data aplikasi milik user yang sedang login
        $application = Auth::user()->prospective->applications()->with([
            'admissionCategory.documentRequirements',
            'documents'
        ])->firstOrFail();

        // Penjaga Gerbang: Upload dokumen hanya boleh diakses jika data diri sudah dilengkapi
        // Status 'lengkapi_data' berarti user belum submit form biodata
        $allowedStatuses = ['upload_dokumen', 'proses_verifikasi', 'lolos_verifikasi_data', 'diterima', 'sudah_registrasi', 'document_rejected'];
        if (!in_array($application->status, $allowedStatuses)) {
            return redirect()->route('pendaftar.biodata')
                ->with('error', 'Harap lengkapi Data Diri terlebih dahulu sebelum mengupload dokumen.');
        }

        // Tampilkan view baru dan kirim data aplikasi
        return view('pendaftar.document-upload', ['application' => $application]);
    }
}