<?php

namespace App\Livewire\Admin\Curriculum;

use App\Models\Course;
use App\Models\Curriculum;
use Livewire\Attributes\On;
use Livewire\Component;

class AddCourseModal extends Component
{
    public ?Curriculum $curriculum;
    public $showModal = false;
    public $allCourses;
    public $selectedCourses = [];

    #[On('show-add-course-modal')]
public function showModal($data)
{
    // Ambil ID dari data array yang dikirim
    $curriculumId = $data['curriculum'];

    // Cari data kurikulum di database menggunakan ID tersebut
    $this->curriculum = Curriculum::find($curriculumId);
    
    // Jika kurikulum ditemukan, lanjutkan logika
    if ($this->curriculum) {
        $existingCourseIds = $this->curriculum->courses()->pluck('courses.id')->toArray();
        $this->allCourses = Course::whereNotIn('id', $existingCourseIds)->orderBy('name')->get();
        $this->showModal = true;
    }
}

    public function addCoursesToCurriculum()
    {
        if ($this->curriculum && !empty($this->selectedCourses)) {
            // 'attach' adalah method untuk menambahkan relasi many-to-many
            $this->curriculum->courses()->attach($this->selectedCourses);

            // Reset pilihan dan tutup modal
            $this->reset(['selectedCourses', 'showModal']);

            // Kirim event untuk me-refresh halaman utama
            $this->dispatch('curriculum-updated');
            session()->flash('success', 'Mata kuliah berhasil ditambahkan ke kurikulum.');
        }
    }

    public function render()
    {
        return view('livewire.admin.curriculum.add-course-modal');
    }
}