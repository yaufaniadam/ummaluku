<?php

namespace App\Livewire\Admin\Lecturer;

use App\Models\Lecturer;
use Livewire\Attributes\On;
use Livewire\Component;

class Actions extends Component
{
    public ?Lecturer $lecturer;
    public $confirmingDelete = false;
    public $confirmingToggleStatus = false;

    #[On('confirm-delete')]
    public function confirmDelete(Lecturer $lecturer)
    {
        $this->lecturer = $lecturer;
        $this->confirmingDelete = true;
    }

    #[On('confirm-toggle-status')]
    public function confirmToggleStatus(Lecturer $lecturer)
    {
        $this->lecturer = $lecturer;
        $this->confirmingToggleStatus = true;
    }

    public function delete()
    {
        if ($this->lecturer) {
            $this->lecturer->delete(); // Ini akan melakukan soft delete
            $this->dispatch('lecturer-updated');
            session()->flash('success', 'Dosen berhasil dihapus.');
        }
        $this->confirmingDelete = false;
    }

    public function toggleStatus()
    {
        if ($this->lecturer) {
            // Toggle antara 'active' dan 'on_leave'
            $newStatus = $this->lecturer->status === 'active' ? 'on_leave' : 'active';
            $this->lecturer->update(['status' => $newStatus]);
            $this->dispatch('lecturer-updated');
            session()->flash('success', 'Status dosen berhasil diubah.');
        }
        $this->confirmingToggleStatus = false;
    }

    public function render()
    {
        return view('livewire.admin.lecturer.actions');
    }
}