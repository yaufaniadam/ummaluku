<?php

namespace App\Livewire\Master\Program;

use App\Models\Program;
use App\Models\Faculty;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal Properties
    public $showModal = false;
    public $isEdit = false;

    // Form Properties
    public $programId;
    public $nameId;
    public $nameEn;
    public $code;
    public $degree;
    public $facultyId;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {
        $programs = Program::with(['faculty', 'currentHead.lecturer', 'currentSecretary.lecturer'])
            ->where('name_id', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.master.program.index', [
            'programs' => $programs,
            'faculties' => Faculty::orderBy('name_id')->get()
        ])->extends('adminlte::page')->section('content');
    }

    public function create()
    {
        $this->reset(['programId', 'nameId', 'nameEn', 'code', 'degree', 'facultyId']);
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        $this->programId = $program->id;
        $this->nameId = $program->name_id;
        $this->nameEn = $program->name_en;
        $this->code = $program->code;
        $this->degree = $program->degree;
        $this->facultyId = $program->faculty_id;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'nameId' => 'required|string|max:255',
            'nameEn' => 'nullable|string|max:255',
            'code' => 'required|string|max:10|unique:programs,code,' . ($this->programId ?? 'NULL'),
            'degree' => 'required|string',
            'facultyId' => 'required|exists:faculties,id',
        ];

        $this->validate($rules);

        if ($this->isEdit) {
            $program = Program::findOrFail($this->programId);
            $program->update([
                'name_id' => $this->nameId,
                'name_en' => $this->nameEn,
                'code' => $this->code,
                'degree' => $this->degree,
                'faculty_id' => $this->facultyId,
            ]);
            session()->flash('success', 'Program Studi berhasil diperbarui.');
        } else {
            Program::create([
                'name_id' => $this->nameId,
                'name_en' => $this->nameEn,
                'code' => $this->code,
                'degree' => $this->degree,
                'faculty_id' => $this->facultyId,
            ]);
            session()->flash('success', 'Program Studi berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['programId', 'nameId', 'nameEn', 'code', 'degree', 'facultyId', 'isEdit']);
        $this->dispatch('close-modal');
    }
}
