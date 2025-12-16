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

    // Edit Modal Properties
    public $editProgramId;
    public $editNameId;
    public $editNameEn;
    public $editCode;
    public $editDegree;
    public $editFacultyId;
    public $showEditModal = false;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {
        $programs = Program::with(['faculty', 'currentHead.lecturer', 'currentSecretary.lecturer'])
            ->where('name_id', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.master.program.index', [
            'programs' => $programs,
            'faculties' => Faculty::orderBy('name')->get()
        ])->extends('adminlte::page')->section('content');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        $this->editProgramId = $program->id;
        $this->editNameId = $program->name_id;
        $this->editNameEn = $program->name_en;
        $this->editCode = $program->code;
        $this->editDegree = $program->degree;
        $this->editFacultyId = $program->faculty_id;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'editNameId' => 'required|string|max:255',
            'editCode' => 'nullable|string|max:4|unique:programs,code,' . $this->editProgramId,
            'editDegree' => 'required|string',
            'editFacultyId' => 'required|exists:faculties,id',
        ]);

        $program = Program::findOrFail($this->editProgramId);
        $program->update([
            'name_id' => $this->editNameId,
            'name_en' => $this->editNameEn,
            'code' => $this->editCode,
            'degree' => $this->editDegree,
            'faculty_id' => $this->editFacultyId,
        ]);

        $this->showEditModal = false;
        $this->reset(['editProgramId', 'editNameId', 'editNameEn', 'editCode', 'editDegree', 'editFacultyId']);

        // Ensure modal closes in UI
        $this->dispatch('close-modal');

        session()->flash('success', 'Program Studi berhasil diperbarui.');
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->reset(['editProgramId', 'editNameId', 'editNameEn', 'editCode', 'editDegree', 'editFacultyId']);
    }
}
