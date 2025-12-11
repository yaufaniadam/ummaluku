<?php

namespace App\Livewire\Prodi\CourseClass;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Lecturer;
use Livewire\Component;

class Form extends Component
{
    public ?CourseClass $courseClass = null;

    // Form Fields
    public $course_id;
    public $lecturer_id;
    public $academic_year_id;
    public $name;
    public $capacity;
    public $day;
    public $start_time;
    public $end_time;
    public $room;

    public $courses = [];
    public $academicYears = [];
    public $lecturers = [];

    protected $rules = [
        'course_id' => 'required|exists:courses,id',
        'lecturer_id' => 'nullable|exists:lecturers,id',
        'academic_year_id' => 'required|exists:academic_years,id',
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'day' => 'nullable|string|max:20',
        'start_time' => 'nullable',
        'end_time' => 'nullable|after:start_time',
        'room' => 'nullable|string|max:50',
    ];

    public function mount(CourseClass $courseClass = null)
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Akses ditolak.');
        }

        $programId = $user->staff->program_id;

        // Load reference data scoped to program
        $this->courses = Course::whereHas('curriculum', function ($q) use ($programId) {
            $q->where('program_id', $programId);
        })->orderBy('name')->get();

        $this->academicYears = AcademicYear::orderBy('start_year', 'desc')->take(5)->get();
        $this->lecturers = Lecturer::orderBy('name')->get(); // Lecturers might be shared, so get all. Or scope to program? Usually shared.

        if ($courseClass && $courseClass->exists) {
            // Verify ownership
            if ($courseClass->course->curriculum->program_id !== $programId) {
                abort(403, 'Anda tidak berhak mengedit kelas ini.');
            }

            $this->courseClass = $courseClass;
            $this->course_id = $courseClass->course_id;
            $this->lecturer_id = $courseClass->lecturer_id;
            $this->academic_year_id = $courseClass->academic_year_id;
            $this->name = $courseClass->name;
            $this->capacity = $courseClass->capacity;
            $this->day = $courseClass->day;
            $this->start_time = $courseClass->start_time;
            $this->end_time = $courseClass->end_time;
            $this->room = $courseClass->room;
        } else {
            // Defaults
            $this->academic_year_id = AcademicYear::where('is_active', true)->value('id');
            $this->capacity = 40;
        }
    }

    public function save()
    {
        $this->validate();

        // Verify course belongs to program
        $course = Course::find($this->course_id);
        $user = auth()->user();
        if ($course->curriculum->program_id !== $user->staff->program_id) {
            $this->addError('course_id', 'Matakuliah tidak valid.');
            return;
        }

        $data = [
            'course_id' => $this->course_id,
            'lecturer_id' => $this->lecturer_id,
            'academic_year_id' => $this->academic_year_id,
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

        return redirect()->route('prodi.course-class.index');
    }

    public function render()
    {
        return view('livewire.prodi.course-class.form')->extends('adminlte::page')->section('content');
    }
}
