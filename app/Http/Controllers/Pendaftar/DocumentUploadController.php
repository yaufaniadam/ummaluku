<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentUploadController extends Controller
{
    public function store(Request $request, Application $application)
    {
        // 1. Otorisasi: Pastikan pendaftar ini milik user yang sedang login
        if (Auth::id() !== $application->prospective->user_id) {
            abort(403);
        }

        // 2. Validasi file
        $request->validate([
            'document_id' => 'required|exists:document_requirements,id',
            'file_upload' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $requirementId = $request->input('document_id');

        // 3. Simpan file
        $filePath = $request->file('file_upload')->store('documents/' . $application->id, 'public');

        // 4. Update atau buat record di database
        ApplicationDocument::updateOrCreate(
            [
                'application_id' => $application->id,
                'document_requirement_id' => $requirementId,
            ],
            [
                'file_path' => $filePath,
                'status' => 'pending',
            ]
        );
        
        // 5. --- LOGIKA PENGECEKAN OTOMATIS DIMULAI DI SINI ---
        $this->checkAndUpdateApplicationStatus($application);

        return back()->with('success', 'Dokumen berhasil diunggah!');
    }

    /**
     * Method baru untuk memeriksa kelengkapan dokumen dan mengupdate status aplikasi.
     */
    private function checkAndUpdateApplicationStatus(Application $application)
    {
        // Muat ulang relasi untuk mendapatkan data terbaru
        $application->load('admissionCategory.documentRequirements', 'documents');

        // Ambil daftar ID dokumen yang wajib untuk jalur pendaftaran ini
        $requiredDocIds = $application->admissionCategory
                                      ->documentRequirements()
                                      ->where('is_mandatory', true)
                                      ->pluck('id');
        
        // Ambil daftar ID dokumen yang sudah diunggah oleh pendaftar
        $uploadedDocIds = $application->documents->pluck('document_requirement_id');

        // Cek apakah semua ID dokumen wajib ada di dalam daftar yang sudah diunggah
        $allRequiredDocsUploaded = $requiredDocIds->diff($uploadedDocIds)->isEmpty();

        if ($allRequiredDocsUploaded) {
            // Jika semua sudah lengkap, update status aplikasi utama
            $application->update(['status' => 'menunggu_verifikasi_dokumen']);
        }
    }
}