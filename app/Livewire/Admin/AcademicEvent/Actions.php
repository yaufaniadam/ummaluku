<?php

namespace App\Livewire\Admin\AcademicEvent;

use App\Models\AcademicEvent;
use Livewire\Attributes\On;
use Livewire\Component;

class Actions extends Component
{
    public ?AcademicEvent $academicEvent;
    public $showDetailModal = false;
    public $confirmingDelete = false;

    // Listener baru, menangkap sinyal dari FullCalendar
    #[On('show-event-detail')]
    public function showDetail($eventId)
    {
        $this->academicEvent = AcademicEvent::find($eventId);
        $this->showDetailModal = true;
    }

    // Method ini sekarang dipanggil dari tombol di modal detail
    public function confirmDelete()
    {
        $this->showDetailModal = false; // Tutup modal detail
        $this->confirmingDelete = true; // Buka modal konfirmasi hapus
    }

    public function delete()
    {
        if ($this->academicEvent) {
            $this->academicEvent->delete(); // Soft delete
            $this->dispatch('academic-event-updated'); // Kirim sinyal agar kalender me-refresh
            session()->flash('success', 'Event berhasil dihapus.');
        }
        $this->closeModals();
    }

    public function closeModals()
    {
        $this->showDetailModal = false;
        $this->confirmingDelete = false;
    }

    public function render()
    {
        return view('livewire.admin.academic-event.actions');
    }
}