<?php

namespace App\Livewire\Master\WorkUnit;

use App\Models\EmployeeStructuralHistory;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\StructuralPosition;
use App\Models\WorkUnit;
use App\Models\WorkUnitOfficial;
use Livewire\Component;

class OfficialManager extends Component
{
    public WorkUnit $workUnit;
    public $officials;

    // Form variables
    public $position = 'Kepala'; // Internal value
    public $employee_type = 'lecturer'; // 'lecturer' or 'staff'
    public $employee_id;
    public $start_date;
    public $sk_number;

    // Editing State
    public $editingId = null;
    public $isEditing = false;
    public $edit_end_date;
    public $edit_is_active;

    protected $listeners = ['deleteOfficial' => 'delete'];

    public function mount(WorkUnit $workUnit)
    {
        $this->workUnit = $workUnit;
        $this->refreshOfficials();
    }

    public function refreshOfficials()
    {
        $this->officials = $this->workUnit->officials()
            ->with('employee')
            ->orderBy('is_active', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function updatedEmployeeType()
    {
        $this->reset(['employee_id']);
    }

    public function edit($id)
    {
        $official = WorkUnitOfficial::find($id);
        if (!$official) return;

        $this->isEditing = true;
        $this->editingId = $official->id;
        $this->position = $official->position;
        $this->employee_type = $official->employee_type === Lecturer::class ? 'lecturer' : 'staff';
        $this->employee_id = $official->employee_id;
        $this->start_date = $official->start_date->format('Y-m-d');
        $this->sk_number = $official->sk_number;

        $this->edit_end_date = $official->end_date ? $official->end_date->format('Y-m-d') : null;
        $this->edit_is_active = $official->is_active;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->reset(['employee_id', 'start_date', 'sk_number', 'edit_end_date', 'edit_is_active']);
    }

    public function updateOfficial()
    {
        $this->validate([
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
            'edit_end_date' => 'nullable|date|after_or_equal:start_date',
            'edit_is_active' => 'boolean'
        ]);

        $official = WorkUnitOfficial::find($this->editingId);
        if (!$official) return;

        $oldStartDate = $official->start_date;

        $official->update([
            'start_date' => $this->start_date,
            'end_date' => $this->edit_end_date,
            'sk_number' => $this->sk_number,
            'is_active' => $this->edit_is_active,
        ]);

        // Sync History
        $this->syncHistory($official, $oldStartDate);

        session()->flash('success', 'Data pejabat berhasil diperbarui.');
        $this->cancelEdit();
        $this->refreshOfficials();
    }

    public function delete($id)
    {
        $official = WorkUnitOfficial::find($id);
        if (!$official) return;

        // Sync Delete from History
        $structuralPositionName = $this->getStructuralPositionName();
        $structuralPosition = StructuralPosition::where('name', $structuralPositionName)->first();

        if ($structuralPosition && $official->employee) {
            $history = EmployeeStructuralHistory::where('employee_type', $official->employee_type)
                ->where('employee_id', $official->employee_id)
                ->where('structural_position_id', $structuralPosition->id)
                ->where('start_date', $official->start_date)
                ->first();

            if ($history) {
                $history->delete();
            }
        }

        $official->delete();
        session()->flash('success', 'Data pejabat berhasil dihapus.');
        $this->refreshOfficials();
    }

    public function assignOfficial()
    {
        if ($this->isEditing) {
            $this->updateOfficial();
            return;
        }

        $this->validate([
            'employee_type' => 'required|in:lecturer,staff',
            'employee_id' => 'required',
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
        ]);

        $modelClass = $this->employee_type === 'lecturer' ? Lecturer::class : Staff::class;

        // 1. Deactivate current active official
        $currentOfficial = $this->workUnit->officials()
            ->where('position', 'Kepala')
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        if ($currentOfficial) {
            $endDate = date('Y-m-d', strtotime($this->start_date . ' -1 day'));
            $currentOfficial->update([
                'is_active' => false,
                'end_date' => $endDate,
            ]);

            // Sync Deactivation
            $this->syncHistory($currentOfficial, $currentOfficial->start_date);
        }

        // 2. Create new official
        $newOfficial = WorkUnitOfficial::create([
            'work_unit_id' => $this->workUnit->id,
            'employee_type' => $modelClass,
            'employee_id' => $this->employee_id,
            'position' => 'Kepala',
            'start_date' => $this->start_date,
            'is_active' => true,
            'sk_number' => $this->sk_number,
        ]);

        // 3. Sync Creation
        $this->syncHistory($newOfficial, $newOfficial->start_date);

        $this->reset(['employee_id', 'start_date', 'sk_number']);
        $this->refreshOfficials();
        session()->flash('success', 'Pejabat berhasil ditetapkan.');
    }

    protected function getStructuralPositionName()
    {
        // Dynamically name the position based on the Unit
        // e.g. "Kepala Lembaga Penjaminan Mutu" or "Kepala Divisi Mutu"
        return 'Kepala ' . $this->workUnit->name;
    }

    protected function syncHistory(WorkUnitOfficial $official, $originalStartDate)
    {
        $structuralPositionName = $this->getStructuralPositionName();
        $structuralPosition = StructuralPosition::firstOrCreate(['name' => $structuralPositionName]);

        $history = EmployeeStructuralHistory::where('employee_type', $official->employee_type)
            ->where('employee_id', $official->employee_id)
            ->where('structural_position_id', $structuralPosition->id)
            ->where('start_date', $originalStartDate)
            ->first();

        if (!$history) {
            // Create if not exists (and is currently attempting to create/update)
             EmployeeStructuralHistory::create([
                'employee_type' => $official->employee_type,
                'employee_id' => $official->employee_id,
                'structural_position_id' => $structuralPosition->id,
                'work_unit_id' => $this->workUnit->id,
                'sk_number' => $official->sk_number,
                'start_date' => $official->start_date,
                'end_date' => $official->end_date,
                'is_active' => $official->is_active,
            ]);
        } else {
            // Update existing
            $history->update([
                'start_date' => $official->start_date,
                'end_date' => $official->end_date,
                'sk_number' => $official->sk_number,
                'is_active' => $official->is_active,
            ]);
        }
    }

    public function render()
    {
        $employees = collect();
        if ($this->employee_type === 'lecturer') {
            // Get lecturers, check if we can sort by name
            $employees = Lecturer::with('user')->get()->sortBy(fn($l) => $l->full_name_with_degree ?? $l->user->name);
        } else {
            $employees = Staff::with('user')->get()->sortBy(fn($s) => $s->user->name);
        }

        return view('livewire.master.work-unit.official-manager', [
            'employees' => $employees
        ])->extends('adminlte::page')->section('content');
    }
}
