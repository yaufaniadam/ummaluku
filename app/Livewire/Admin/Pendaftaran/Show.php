<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Livewire\Component;

class Show extends Component
{
    // Properti ini akan diisi secara otomatis oleh Livewire
    // berkat Route Model Binding
    public Application $application;

    protected $listeners = ['document-status-updated' => 'refreshApplication'];

    public function refreshApplication()
    {
        $this->application->refresh();
        $this->application->load('documents');
    }

    public function getHasUnverifiedDocumentsProperty()
    {
        return $this->application->documents()->where('status', '!=', 'verified')->exists();
    }

    public function getReadyToPassProperty()
    {
        // Gunakan koleksi yang sudah diload untuk menghindari query tambahan
        $documents = $this->application->documents;

        // 1. Cek apakah ada dokumen yang statusnya bukan verified (pending, revision_needed, rejected)
        if ($documents->where('status', '!=', 'verified')->isNotEmpty()) {
            return false;
        }

        // 2. Cek apakah ada dokumen wajib yang belum diunggah sama sekali
        $requiredDocuments = $this->application->admissionCategory->documentRequirements;
        $uploadedRequirementIds = $documents->pluck('document_requirement_id');

        foreach ($requiredDocuments as $requirement) {
            if ($requirement->is_mandatory && !$uploadedRequirementIds->contains($requirement->id)) {
                return false;
            }
        }

        return true;
    }

    public function getCanRejectProperty()
    {
        $documents = $this->application->documents;

        // 1. Pastikan tidak ada dokumen pending (verifikasi harus selesai)
        if ($documents->where('status', 'pending')->isNotEmpty()) {
            return false;
        }

        // 2. Pastikan minimal ada satu dokumen yang ditolak
        if ($documents->where('status', 'rejected')->isEmpty()) {
            return false;
        }

        return true;
    }

    public function mount(Application $application)
    {
        // Kita tambahkan relasi baru untuk dimuat di sini
        $this->application->load([
            'prospective.user',
            'batch',
            'admissionCategory.documentRequirements',
            'programChoices.program',
            'documents',
            'prospective.religion',
            'prospective.highSchool',
            'prospective.highSchoolMajor',
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
 

    // Method untuk meminta revisi (akan kita lengkapi dengan modal)
    public function requireRevision($documentId, $notes)
    {
        $document = ApplicationDocument::find($documentId);
        if ($document) {
            $document->update([
                'status' => 'revision_needed',
                'notes' => $notes,
                'verified_by' => auth()->id(),
            ]);
            $this->dispatch('show-alert', ['type' => 'warning', 'message' => 'Catatan revisi telah disimpan.']);
        }
    }

    public function finalizeVerification()
    {
        // 1. Cek apakah ada dokumen yang belum terverifikasi
        if ($this->hasUnverifiedDocuments) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal! Masih ada dokumen yang belum sesuai atau belum terverifikasi.'
            ]);
            return;
        }

        $requiredDocuments = $this->application->admissionCategory->documentRequirements;
        $verifiedDocuments = $this->application->documents()->where('status', 'verified')->pluck('document_requirement_id');

        foreach ($requiredDocuments as $requirement) {
            // Jika dokumen ini wajib TAPI tidak ada di daftar yang sudah diverifikasi
            if ($requirement->is_mandatory && !$verifiedDocuments->contains($requirement->id)) {
                $this->dispatch('show-alert', [
                    'type' => 'error', 
                    'message' => 'Gagal! Masih ada dokumen wajib ("' . $requirement->name . '") yang belum diverifikasi.'
                ]);
                return; // Hentikan proses
            }
        }

        // 3. Jika semua pengecekan lolos, update status aplikasi
        $this->application->update(['status' => 'lolos_verifikasi_data']);

        // Ganti dengan perintah redirect
        return redirect()->route('admin.pmb.seleksi.index')->with('success', 'Verifikasi Selesai! Pendaftar berhasil diloloskan ke tahap seleksi.');
    }

    public function rejectApplication($reason)
    {
        // 1. Validasi untuk memastikan alasan diisi
        if (empty($reason)) {
            $this->dispatch('show-alert', [
                'type' => 'error', 
                'message' => 'Alasan penolakan wajib diisi.'
            ]);
            return;
        }

        // 2. Update status aplikasi dan simpan alasan penolakan
        $this->application->update([
            'status' => 'dokumen_ditolak',
            'rejection_reason' => $reason
        ]);

        // 3. Kirim notifikasi ke calon mahasiswa (di background)
        // $this->application->prospective->user->notify(new PendaftaranDitolak($reason));

        // 4. Beri notifikasi sukses ke admin
        $this->dispatch('show-alert', [
            'type' => 'success', 
            'message' => 'Pendaftaran telah ditolak. Notifikasi telah dikirim ke pendaftar.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.show')
            ->extends('adminlte::page')
            ->section('content');
    }
}
