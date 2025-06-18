<?php

namespace App\Livewire\Pendaftar;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads; // <-- 1. Import trait untuk file upload

class Dashboard extends Component
{
    use WithFileUploads; // <-- 2. Gunakan trait

    public Application $application;
    public $requiredDocuments;

    // Properti untuk menampung file yang akan diupload
    // Key-nya adalah ID dari document_requirement
    public $uploads = [];

    public function mount()
    {
        // Ambil data pendaftaran milik user yang sedang login
        $user = Auth::user();
        $this->application = Application::whereHas('prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with([
            'admissionCategory.documentRequirements',
            'documents'
        ])->firstOrFail();

        $this->requiredDocuments = $this->application->admissionCategory->documentRequirements;
    }

    public function uploadDocument($requirementId)
    {
        // Validasi file yang diupload
        $this->validate([
            'uploads.'.$requirementId => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        // Simpan file
        $file = $this->uploads[$requirementId];
        $filePath = $file->store('public/documents'); // Simpan di storage/app/public/documents

        // Buat atau perbarui record di database
        ApplicationDocument::updateOrCreate(
            [
                'application_id' => $this->application->id,
                'document_requirement_id' => $requirementId,
            ],
            [
                'file_path' => $filePath,
                'status' => 'pending', // Status awal setelah upload
            ]
        );

        // Reset properti upload dan tampilkan pesan sukses
        unset($this->uploads[$requirementId]);
        session()->flash('success', 'Dokumen berhasil diunggah!');
    }


    public function render()
    {
        // Refresh data setiap kali ada perubahan
        $this->application->refresh();
        return view('livewire.pendaftar.dashboard');
    }
}