<?php

namespace App\Livewire\Admin\Course;

use App\Models\Course;
use Livewire\Attributes\On;
use Livewire\Component;

class Actions extends Component
{
    public ?Course $course;
    public $confirmingDelete = false;

    #[On('confirm-delete-course')]
    public function confirmDelete(Course $course)
    {
        $this->course = $course;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->course) {
            $this->course->delete(); // Ini akan melakukan soft delete
            $this->dispatch('course-updated'); // Kirim sinyal agar tabel me-refresh
            session()->flash('success', 'Mata kuliah berhasil dihapus.');
        }
        $this->confirmingDelete = false;
    }

    public function render()
    {
        return view('livewire.admin.course.actions');
    }
}