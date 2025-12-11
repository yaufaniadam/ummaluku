<?php

namespace App\Livewire\Prodi\Course;

use App\Models\Course;
use App\Models\Curriculum;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public ?Course $course = null;

    // Form fields
    public $curriculum_id;
    public $code;
    public $name;
    public $sks;
    public $semester_recommendation;
    public $type = 'Wajib';

    public $curriculums = [];

    protected function rules()
    {
        return [
            'curriculum_id' => 'required|exists:curriculums,id',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses', 'code')->ignore($this->course?->id)
            ],
            'name' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester_recommendation' => 'required|integer|min:1|max:8',
            'type' => 'required|in:Wajib,Pilihan,Wajib Peminatan',
        ];
    }

    public function mount(Course $course = null)
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Akses ditolak.');
        }

        $programId = $user->staff->program_id;

        // Fetch curriculums for this program only
        $this->curriculums = Curriculum::where('program_id', $programId)
            ->orderBy('start_year', 'desc')
            ->get();

        if ($course && $course->exists) {
            // Verify ownership
            if ($course->curriculum->program_id !== $programId) {
                abort(403, 'Anda tidak berhak mengedit matakuliah ini.');
            }

            $this->course = $course;
            $this->curriculum_id = $course->curriculum_id;
            $this->code = $course->code;
            $this->name = $course->name;
            $this->sks = $course->sks;
            $this->semester_recommendation = $course->semester_recommendation;
            $this->type = $course->type;
        } else {
            // Set default curriculum if available
            $this->curriculum_id = $this->curriculums->first()?->id;
        }
    }

    public function save()
    {
        $this->validate();

        // Verify curriculum belongs to program (double check)
        $curriculum = Curriculum::find($this->curriculum_id);
        $user = auth()->user();
        if ($curriculum->program_id !== $user->staff->program_id) {
            $this->addError('curriculum_id', 'Kurikulum tidak valid.');
            return;
        }

        if ($this->course) {
            $this->course->update([
                'curriculum_id' => $this->curriculum_id,
                'code' => $this->code,
                'name' => $this->name,
                'sks' => $this->sks,
                'semester_recommendation' => $this->semester_recommendation,
                'type' => $this->type,
            ]);
            session()->flash('success', 'Matakuliah berhasil diperbarui.');
        } else {
            Course::create([
                'curriculum_id' => $this->curriculum_id,
                'code' => $this->code,
                'name' => $this->name,
                'sks' => $this->sks,
                'semester_recommendation' => $this->semester_recommendation,
                'type' => $this->type,
            ]);
            session()->flash('success', 'Matakuliah berhasil ditambahkan.');
        }

        return redirect()->route('prodi.course.index');
    }

    public function render()
    {
        return view('livewire.prodi.course.form')->extends('adminlte::page')->section('content');
    }
}
