<?php

namespace App\Livewire\Admin\CourseClass;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Lecturer;
use App\Models\Program;

use Livewire\Component;

class CourseClassForm extends Component
{
    public AcademicYear $academicYear;
    public ?CourseClass $courseClass = null;
    public Program $program;

    // Properti untuk dropdown
    public $courses;
    public $lecturers;

    // Properti untuk form fields
    public $course_id;
    public $lecturer_id;
    public $name;
    public $capacity;
    public $day;
    public $start_time;
    public $end_time;
    public $room;


    public function mount(AcademicYear $academicYear, Program $program, CourseClass $courseClass = null)
    {
        $this->academicYear = $academicYear;
        $this->program = $program;
        $this->courses = Course::orderBy('name')->get();
        $this->lecturers = Lecturer::orderBy('full_name_with_degree')->get();

        // Dropdown sekarang hanya menampilkan MK dari prodi yang relevan
        $this->courses = Course::whereHas('curriculum', function ($query) {
            $query->where('program_id', $this->program->id);
        })->orderBy('name')->get();

        if ($courseClass->exists) {
            $this->courseClass = $courseClass;
            // Isi properti form dengan data yang ada
            $this->course_id = $courseClass->course_id;
            $this->lecturer_id = $courseClass->lecturer_id;
            $this->name = $courseClass->name;
            $this->capacity = $courseClass->capacity;
            $this->day = $courseClass->day;
            $this->start_time = $courseClass->start_time;
            $this->end_time = $courseClass->end_time;
            $this->room = $courseClass->room;
        }
    }

    protected function rules()
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'day' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'room' => 'nullable|string|max:50',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $this->course_id,
            'lecturer_id' => $this->lecturer_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room' => $this->room,
        ];

        if ($this->courseClass) {
            $this->courseClass->update($data);
            session()->flash('success', 'Kelas berhasil diperbarui.');
        } else {
            CourseClass::create($data);
            session()->flash('success', 'Kelas berhasil ditambahkan.');
        }

        // Kirim sinyal agar tabel me-refresh
        $this->dispatch('course-class-updated');

        return $this->redirect(route('admin.academic-years.programs.course-classes.index', [
            'academic_year' => $this->academicYear,
            'program' => $this->program
        ]), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.course-class.course-class-form');
    }
}
