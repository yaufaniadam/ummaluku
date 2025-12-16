<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Livewire\Component;

class DocumentManager extends Component
{
    // Komponen ini akan menerima data aplikasi dari komponen induknya
    public Application $application;

    public function mount()
    {
        // Kita tetap load relasi yang dibutuhkan di sini agar datanya selalu fresh
        $this->application->load('documents', 'admissionCategory.documentRequirements');
    }

    // Method untuk menyetujui dokumen
    public function verifyDocument($documentId)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update(['status' => 'verified', 'verified_by' => auth()->id()]);

            // Refresh data aplikasi agar UI terupdate
            $this->application->load('documents');

            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Dokumen berhasil diverifikasi.']);
            $this->dispatch('document-status-updated');
        }
    }

    // // Method untuk menolak dokumen
    // public function rejectDocument($documentId)
    // {
    //     $document = ApplicationDocument::find($documentId);
    //     if ($document) {
    //         $document->update(['status' => 'rejected', 'verified_by' => auth()->id()]);
    //         $this->dispatch('show-alert', ['type' => 'error', 'message' => 'Dokumen telah ditolak.']);
    //     }
    // }

    // Method untuk meminta revisi
    public function requireRevision($documentId, $notes)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update([
                'status' => 'revision_needed',
                'notes' => $notes,
                'verified_by' => auth()->id(),
            ]);

            // Refresh data aplikasi agar UI terupdate
            $this->application->load('documents');

            $this->dispatch('show-alert', ['type' => 'warning', 'message' => 'Catatan revisi telah disimpan.']);
            $this->dispatch('document-status-updated');
        }
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.document-manager');
    }
}