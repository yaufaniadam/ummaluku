<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    // Properti ini akan diisi secara otomatis oleh Livewire
    // berkat Route Model Binding
    public Application $application;

    public function mount(Application $application)
    {
        // Kita tambahkan relasi baru untuk dimuat di sini
        $this->application->load([
            'prospective.user',
            'batch',
            'admissionCategory.documentRequirements', // <-- Ambil syarat dokumen dari kategori
            'programChoices.program',
            'documents' // <-- Ambil dokumen yang sudah di-upload oleh pendaftar
        ]);
    }

    // Method untuk menyetujui dokumen
    public function verifyDocument($documentId)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
            ]);
            $this->dispatch('show-alert', ['message' => 'Dokumen berhasil diverifikasi.']);
        }
    }

    // Method untuk menolak dokumen
    public function rejectDocument($documentId)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
            ]);
            $this->dispatch('show-alert', ['message' => 'Dokumen telah ditolak.']);
        }
    }

    // Method untuk meminta revisi
    public function requireRevision($documentId, $notes)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update([
                'status' => 'revision_needed',
                'notes' => $notes, // Simpan catatan revisi
                'verified_by' => auth()->id(),
            ]);
            $this->dispatch('show-alert', ['message' => 'Pemberitahuan revisi telah disimpan.']);
        }
    }

    /**
     * Method untuk menyelesaikan verifikasi dan memajukan status aplikasi.
     */
    public function advanceToSelection()
    {
        // Opsional: Validasi untuk memastikan semua dokumen wajib sudah terverifikasi
        $requiredDocs = $this->application->admissionCategory->documentRequirements;
        $uploadedDocs = $this->application->documents->where('status', 'verified')->pluck('document_requirement_id');

        foreach ($requiredDocs as $req) {
            if (!$uploadedDocs->contains($req->id)) {
                // Kirim notifikasi error jika ada dokumen wajib yang belum terverifikasi
                $this->dispatch('show-alert', [
                    'message' => 'Error: Tidak bisa melanjutkan. Masih ada dokumen wajib yang belum diverifikasi.',
                    'type' => 'error' // Kita tambahkan tipe agar bisa ganti ikon
                ]);
                return;
            }
        }

        // Jika semua valid, update status aplikasi utama
        $this->application->update([
            'status' => 'ready_for_selection' // Status baru: Siap untuk diseleksi
        ]);

        // Kirim notifikasi sukses
        $this->dispatch('show-alert', [
            'message' => 'Status pendaftar berhasil diperbarui dan siap untuk tahap seleksi.',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.show');
    }
}
