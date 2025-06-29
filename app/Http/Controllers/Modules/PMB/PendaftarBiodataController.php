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

        // Tampilkan view baru dan kirim data aplikasi
        return view('pendaftar.document-upload', ['application' => $application]);
    }
}