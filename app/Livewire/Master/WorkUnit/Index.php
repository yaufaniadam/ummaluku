<?php

namespace App\Livewire\Master\WorkUnit;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkUnit;
use Livewire\Attributes\Title;

#[Title('Master Unit Kerja')]
class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEditMode = false;
    public $workUnitId;
    public $name;
    public $code;
    public $type;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50|unique:work_units,code',
        'type' => 'nullable|string|max:100',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['workUnitId', 'name', 'code', 'type', 'isEditMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $workUnit = WorkUnit::findOrFail($id);
        $this->workUnitId = $workUnit->id;
        $this->name = $workUnit->name;
        $this->code = $workUnit->code;
        $this->type = $workUnit->type;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        WorkUnit::create([
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:work_units,code,' . $this->workUnitId,
            'type' => 'nullable|string|max:100',
        ]);

        $workUnit = WorkUnit::findOrFail($this->workUnitId);
        $workUnit->update([
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Unit Kerja berhasil diperbarui.');
    }

    public function delete($id)
    {
        $workUnit = WorkUnit::findOrFail($id);

        if ($workUnit->staffs()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus Unit Kerja yang memiliki staf.');
            return;
        }

        $workUnit->delete();
        session()->flash('success', 'Unit Kerja berhasil dihapus.');
    }

    public function render()
    {
        $workUnits = WorkUnit::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.master.work-unit.index', [
            'workUnits' => $workUnits
        ])->extends('adminlte::page')->section('content');
    }
}
