<?php

namespace App\Livewire\Admin\Course;

use App\Models\Course;

use Illuminate\Validation\Rule;
use Livewire\Component;

class CourseForm extends Component
{

    public ?Course $course = null;

    // Properti untuk form fields
    public $code;
    public $name;
    public $sks;
    public $semester_recommendation;
    public $type;
    public $activity_type;

    public function mount( Course $course = null)
    {
 
        if ($course->exists) {
            $this->course = $course;
            // Isi properti form dengan data yang ada
            $this->code = $course->code;
            $this->name = $course->name;
            $this->sks = $course->sks;
            $this->semester_recommendation = $course->semester_recommendation;
            $this->type = $course->type;
            $this->activity_type = $course->activity_type; 
        } else {
            // Set default value untuk form create
            $this->type = 'Wajib';
            $this->activity_type = 'kuliah';
        }
    }

    protected function rules()
    {
        return [
            // Rule unique diubah agar mengabaikan data saat ini ketika edit
            'code' => ['required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($this->course?->id)],
            'name' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:10',
            'semester_recommendation' => 'required|integer|min:1|max:8',
            'type' => 'required|in:Wajib,Pilihan,Wajib Peminatan',
            'activity_type' => 'required|in:kuliah,praktikum,mandiri',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->course) {
            $this->course->update($validatedData);
            session()->flash('success', 'Mata kuliah berhasil diperbarui.');
        } else {
            Course::create($validatedData);
            session()->flash('success', 'Mata kuliah berhasil ditambahkan.');
        }

        $this->dispatch('course-updated');
        return redirect(route('admin.akademik.courses.index'));
    }

    public function render()
    {
        return view('livewire.admin.course.course-form');
    }
}