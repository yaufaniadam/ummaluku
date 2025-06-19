<?php

namespace App\Livewire\Admin\Seleksi;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('adminlte::page')]
class Index extends Component
{
    use WithPagination;

    public function acceptApplicant(Application $application)
    {
        // Logika untuk menerima pendaftar
        $application->update(['status' => 'accepted']);
        $this->dispatch('show-alert', ['message' => 'Pendaftar berhasil diterima.', 'type' => 'success']);
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