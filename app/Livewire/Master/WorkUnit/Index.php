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

    // Hierarchy
    public $parentId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50|unique:work_units,code',
        'type' => 'nullable|string|max:100',
        'parentId' => 'nullable|exists:work_units,id'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['workUnitId', 'name', 'code', 'type', 'isEditMode', 'parentId']);
        $this->showModal = true;
    }

    public function createChild($parentId)
    {
        $this->reset(['workUnitId', 'name', 'code', 'type', 'isEditMode']);
        $this->parentId = $parentId;
        $this->type = 'Divisi'; // Default for child
        $this->showModal = true;
    }

    public function edit($id)
    {
        $workUnit = WorkUnit::findOrFail($id);
        $this->workUnitId = $workUnit->id;
        $this->name = $workUnit->name;
        $this->code = $workUnit->code;
        $this->type = $workUnit->type;
        $this->parentId = $workUnit->parent_id;

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
            'parent_id' => $this->parentId,
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
            'parentId' => 'nullable|exists:work_units,id'
        ]);

        $workUnit = WorkUnit::findOrFail($this->workUnitId);

        // Prevent circular reference
        if ($this->parentId && $this->parentId == $workUnit->id) {
            $this->addError('parentId', 'Unit tidak bisa menjadi induk bagi dirinya sendiri.');
            return;
        }

        $workUnit->update([
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'parent_id' => $this->parentId,
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

        if ($workUnit->children()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus Unit Kerja yang memiliki subdivisi.');
            return;
        }

        $workUnit->delete();
        session()->flash('success', 'Unit Kerja berhasil dihapus.');
    }

    public function render()
    {
        // For hierarchy, we prioritize roots, then fetch children.
        // If we paginate roots, we just load children for those roots.

        $query = WorkUnit::query()
            ->with(['children', 'officials.employee', 'parent'])
            ->whereNull('parent_id'); // Only show roots

        if ($this->search) {
             // If searching, we might want to search everything, but displaying hierarchy breaks.
             // We can search for roots OR children.
             // But simpler approach: Search roots, or search if children match.
             $query->where(function($q) {
                 $q->where('name', 'like', '%' . $this->search . '%')
                   ->orWhere('code', 'like', '%' . $this->search . '%')
                   ->orWhereHas('children', function($cq) {
                       $cq->where('name', 'like', '%' . $this->search . '%');
                   });
             });
        }

        $workUnits = $query->latest()->paginate($this->perPage);

        return view('livewire.master.work-unit.index', [
            'workUnits' => $workUnits
        ])->extends('adminlte::page')->section('content');
    }
}
