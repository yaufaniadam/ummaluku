<?php

namespace App\Livewire\Admin\Curriculum;

use App\Models\Curriculum;
use App\Models\Program;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CurriculumForm extends Component
{
    public ?Curriculum $curriculum = null;
    public $programs;

    // Properti form
    public $name;
    public $program_id;
    public $start_year;
    public $is_active = true; // Default value untuk kurikulum baru

    public function mount(Curriculum $curriculum = null)
    {
        $this->programs = Program::orderBy('name_id')->get();
        if ($curriculum->exists) {
            $this->curriculum = $curriculum;
            $this->name = $curriculum->name;
            $this->program_id = $curriculum->program_id;
            $this->start_year = $curriculum->start_year;
            $this->is_active = $curriculum->is_active;
        }
    }

    protected function rules()
    {
        return [
            // Nama kurikulum harus unik per program studi
            'name' => ['required', 'string', 'max:255',
                Rule::unique('curriculums')->where(function ($query) {
                    return $query->where('program_id', $this->program_id);
                })->ignore($this->curriculum?->id)
            ],
            'program_id' => 'required|exists:programs,id',
            'start_year' => 'required|digits:4|integer|min:2000',
            'is_active' => 'required|boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'program_id' => $this->program_id,
            'start_year' => $this->start_year,
            'is_active' => $this->is_active,
        ];

        if ($this->curriculum) {
            $this->curriculum->update($data);
            session()->flash('success', 'Kurikulum berhasil diperbarui.');
        } else {
            Curriculum::create($data);
            session()->flash('success', 'Kurikulum berhasil ditambahkan.');
        }

        return redirect()->route('admin.curriculums.index');
    }

    public function render()
    {
        return view('livewire.admin.curriculum.curriculum-form');
    }
}