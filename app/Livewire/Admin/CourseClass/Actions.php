<?php

namespace App\Livewire\Admin\CourseClass;

use App\Models\CourseClass;
use Livewire\Attributes\On;
use Livewire\Component;

class Actions extends Component
{
    public ?CourseClass $courseClass;
    public $confirmingDelete = false;

    #[On('confirm-delete-course-class')]
    public function confirmDelete(CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->courseClass) {
            $this->courseClass->delete(); // Soft delete
            $this->dispatch('course-class-updated'); // Kirim sinyal refresh
            session()->flash('success', 'Kelas berhasil dihapus.');
        }
        $this->confirmingDelete = false;
    }

    public function render()
    {
        return view('livewire.admin.course-class.actions');
    }
}