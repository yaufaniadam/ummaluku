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


         // 3. Penjaga Gerbang: Jika tidak ada tagihan, atau statusnya bukan 'accepted'
        if ($application->status !== 'upload_dokumen') {
            // Arahkan ke dashboard dengan pesan error
            return redirect()->route('pendaftar')->with('error', 'Anda belum bisa mengakses halaman upload dokumen.');
        }

        // Tampilkan view baru dan kirim data aplikasi
        return view('pendaftar.document-upload', ['application' => $application]);
    }
}