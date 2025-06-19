<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;

class DocumentUploadController extends Controller
{
    public function store(Request $request, Application $application)
    {
        $request->validate([
            'document_id' => 'required|exists:document_requirements,id',
            'file_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Maks 2MB
        ]);

        $requirementId = $request->input('document_id');

        // Simpan file
        $filePath = $request->file('file_upload')->store('documents/' . $application->id, 'public');

        // Buat atau perbarui record di database
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

        // Redirect kembali ke halaman dashboard dengan pesan sukses
        return back()->with('success', 'Dokumen berhasil diunggah!');
    }
}