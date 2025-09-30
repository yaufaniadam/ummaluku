<?php

namespace App\Livewire\Admin\Curriculum;

use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Program;
use Livewire\Component;
use Livewire\WithPagination;

class CourseSelection extends Component
{
    use WithPagination;

    public Curriculum $curriculum;

    // Filter
    public $search = '';
    public $programId = '';
    public $perPage = 10;
    public $courseData = [];

    // Checkbox
    public $selectedCourses = [];
    public $selectAllOnPage = false;

    public function mount(Curriculum $curriculum)
    {
        $this->curriculum = $curriculum;
    }

    public function updatedSelectAllOnPage($value)
    {
        $coursesOnPage = $this->getCourses()->pluck('id')->toArray();

        foreach ($coursesOnPage as $id) {
            $this->selectedCourses[$id] = $value;
        }
    }

    public function updatingPage()
    {
        // Reset select-all setiap kali pindah halaman
        $this->selectAllOnPage = false;
    }

    public function saveSelection()
    {
        $dataToAttach = [];

        foreach ($this->selectedCourses as $courseId => $isSelected) {
            if ($isSelected) {
                $semester = $this->courseData[$courseId]['semester']
                    ?? Course::find($courseId)->semester_recommendation
                    ?? 1;

                $type = $this->courseData[$courseId]['type'] ?? 'Wajib';

                $dataToAttach[$courseId] = [
                    'semester' => $semester,
                    'type' => $type,
                ];
            }
        }

        if (!empty($dataToAttach)) {
            $this->curriculum->courses()->attach($dataToAttach);
        }

        return redirect()
            ->route('admin.akademik.curriculums.courses.index', $this->curriculum->id)
            ->with('success', count($dataToAttach) . ' mata kuliah berhasil ditambahkan ke kurikulum.');
    }

    private function getCourses()
    {
        $existingCourseIds = $this->curriculum->courses()->pluck('courses.id')->toArray();

        return Course::query()
            ->whereNotIn('id', $existingCourseIds)
            ->with('program')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->programId, function ($query) {
                if ($this->programId === 'universitas') {
                    $query->whereNull('program_id');
                } else {
                    $query->where('program_id', $this->programId);
                }
            });
    }

    public function render()
    {
        $courses = $this->getCourses()->paginate($this->perPage);

        // Inisialisasi default semester & type
        foreach ($courses as $course) {
            if (!isset($this->courseData[$course->id])) {
                $this->courseData[$course->id] = [
                    'semester' => $course->semester_recommendation ?? 1,
                    'type'     => 'Wajib',
                ];
            }
        }

        return view('livewire.admin.curriculum.course-selection', [
            'courses'  => $courses,
            'programs' => Program::orderBy('name_id')->get(),
        ]);
    }
}
