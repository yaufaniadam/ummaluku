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

    /**
     * Method untuk menyelesaikan verifikasi dan memajukan status aplikasi.
     */
    // public function advanceToSelection()
    // {
    //     // Opsional: Validasi untuk memastikan semua dokumen wajib sudah terverifikasi
    //     $requiredDocs = $this->application->admissionCategory->documentRequirements;
    //     $uploadedDocs = $this->application->documents->where('status', 'verified')->pluck('document_requirement_id');

    //     foreach ($requiredDocs as $req) {
    //         if (!$uploadedDocs->contains($req->id)) {
    //             // Kirim notifikasi error jika ada dokumen wajib yang belum terverifikasi
    //             $this->dispatch('show-alert', [
    //                 'message' => 'Error: Tidak bisa melanjutkan. Masih ada dokumen wajib yang belum diverifikasi.',
    //                 'type' => 'error' // Kita tambahkan tipe agar bisa ganti ikon
    //             ]);
    //             return;
    //         }
    //     }

    //     // Jika semua valid, update status aplikasi utama
    //     $this->application->update([
    //         'status' => 'lolos_verifikasi' // Status baru: Siap untuk diseleksi
    //     ]);

    //     // Kirim notifikasi sukses
    //     $this->dispatch('show-alert', [
    //         'message' => 'Status pendaftar berhasil diperbarui dan siap untuk tahap seleksi.',
    //         'type' => 'success'
    //     ]);
    // }

    public function finalizeVerification()
    {

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
        return redirect()->route('admin.seleksi.index')->with('success', 'Verifikasi Selesai! Pendaftar berhasil diloloskan ke tahap seleksi.');

        // $this->dispatch('show-alert', [
        //     'type' => 'success', 
        //     'message' => 'Verifikasi Selesai! Pendaftar berhasil diloloskan ke tahap seleksi.'
        // ]);
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
            'status' => 'documents_rejected',
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
        return view('livewire.admin.pendaftaran.show');
    }
}
