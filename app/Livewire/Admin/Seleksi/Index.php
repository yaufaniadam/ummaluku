<?php

namespace App\Livewire\Admin\Seleksi;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('adminlte::page')]
class Index extends Component
{
    use WithPagination;
    public array $selectedPrograms = [];

    public function saveAcceptanceDecision(Application $application)
    {
        // 1. Ambil ID prodi yang dipilih dari properti kita
        $selectedProgramId = $this->selectedPrograms[$application->id] ?? null;

        // 2. Validasi: pastikan admin sudah memilih prodi
        if (is_null($selectedProgramId)) {
            $this->dispatch('show-alert', [
                'message' => 'Silakan pilih program studi terlebih dahulu.', 
                'type' => 'error'
            ]);
            return;
        }

        // 3. Gunakan Transaction untuk memastikan semua update berhasil
        DB::transaction(function () use ($application, $selectedProgramId) {
            // Update status utama aplikasi menjadi 'accepted'
            $application->update(['status' => 'accepted']);

            // Loop semua pilihan prodi dari pendaftar ini
            foreach ($application->programChoices as $choice) {
                // Jika ID-nya cocok dengan yang dipilih admin, set is_accepted = true
                // Jika tidak, set is_accepted = false
                $choice->update(['is_accepted' => ($choice->program_id == $selectedProgramId)]);
            }
        });

        // Kirim notifikasi sukses
        $this->dispatch('show-alert', [
            'message' => 'Pendaftar berhasil diterima di prodi yang dipilih.', 
            'type' => 'success'
        ]);
    }

    public function rejectApplicant(Application $application)
    {
        // Logika untuk menolak pendaftar
        $application->update(['status' => 'rejected']);
        $this->dispatch('show-alert', ['message' => 'Pendaftar telah ditolak.', 'type' => 'error']);
    }

    public function render()
    {
        $applications = Application::with('prospective.user', 'programChoices.program')
            ->where('status', 'ready_for_selection') // <-- Filter utama kita
            ->paginate(10);

        return view('livewire.admin.seleksi.index', [
            'applications' => $applications,
        ]);
    }
}