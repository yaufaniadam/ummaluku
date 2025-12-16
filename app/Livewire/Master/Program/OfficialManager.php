<?php

namespace App\Livewire\Master\Program;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\ProgramOfficial;
use App\Models\StructuralPosition;
use App\Models\WorkUnit;
use App\Models\EmployeeStructuralHistory;
use Livewire\Component;

class OfficialManager extends Component
{
    public Program $program;
    public $heads; // Used for Kaprodi history
    public $secretaries; // Used for Secretary history

    // Form variables
    public $position = 'Kaprodi'; // 'Kaprodi' or 'Sekretaris'
    public $lecturer_id;
    public $start_date;
    public $sk_number;

    // Editing State
    public $editingId = null;
    public $isEditing = false;
    public $edit_end_date;
    public $edit_is_active;

    protected $listeners = ['deleteOfficial' => 'delete'];

    public function mount(Program $program)
    {
        $this->program = $program;
        $this->refreshOfficials();
    }

    public function refreshOfficials()
    {
        $this->heads = $this->program->officials()
            ->where('position', 'Kaprodi')
            ->with('lecturer')
            ->orderBy('start_date', 'desc')
            ->get();

        $this->secretaries = $this->program->officials()
            ->where('position', 'Sekretaris')
            ->with('lecturer')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function updatedPosition()
    {
        if ($this->isEditing) {
            $this->cancelEdit();
        } else {
            $this->reset(['lecturer_id', 'start_date', 'sk_number']);
        }
    }

    public function edit($id)
    {
        $official = ProgramOfficial::find($id);
        if (!$official) return;

        $this->isEditing = true;
        $this->editingId = $official->id;
        $this->position = $official->position;
        $this->lecturer_id = $official->lecturer_id;
        $this->start_date = $official->start_date->format('Y-m-d');
        $this->sk_number = $official->sk_number;

        // Editable extra fields
        $this->edit_end_date = $official->end_date ? $official->end_date->format('Y-m-d') : null;
        $this->edit_is_active = $official->is_active;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->reset(['lecturer_id', 'start_date', 'sk_number', 'edit_end_date', 'edit_is_active']);
    }

    public function updateOfficial()
    {
        $this->validate([
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
            'edit_end_date' => 'nullable|date|after_or_equal:start_date',
            'edit_is_active' => 'boolean'
        ]);

        $official = ProgramOfficial::find($this->editingId);
        if (!$official) return;

        // Determine if crucial identifying info changed (like start_date) to find history
        $oldStartDate = $official->start_date;

        $official->update([
            'start_date' => $this->start_date,
            'end_date' => $this->edit_end_date,
            'sk_number' => $this->sk_number,
            'is_active' => $this->edit_is_active,
            // We do not allow changing position or lecturer_id in Edit Mode to avoid complex sync issues
        ]);

        // Sync with EmployeeStructuralHistory
        $positionName = $official->position === 'Kaprodi' ? 'Ketua Program Studi' : 'Sekretaris Program Studi';
        $structuralPosition = StructuralPosition::where('name', $positionName)->first();

        if ($structuralPosition && $official->lecturer) {
            // Find specific history record.
            // We use oldStartDate to find the record that matched before update
            $history = EmployeeStructuralHistory::where('employee_type', get_class($official->lecturer))
                ->where('employee_id', $official->lecturer->id)
                ->where('structural_position_id', $structuralPosition->id)
                ->where('start_date', $oldStartDate) // Match by original start date
                ->first();

            if ($history) {
                $history->update([
                    'start_date' => $this->start_date,
                    'end_date' => $this->edit_end_date,
                    'sk_number' => $this->sk_number,
                    'is_active' => $this->edit_is_active,
                ]);
            }
        }

        session()->flash('success', 'Data pejabat berhasil diperbarui.');
        $this->cancelEdit();
        $this->refreshOfficials();
    }

    public function delete($id)
    {
        $official = ProgramOfficial::find($id);
        if (!$official) return;

        // Sync Delete: Find History before deleting
        $positionName = $official->position === 'Kaprodi' ? 'Ketua Program Studi' : 'Sekretaris Program Studi';
        $structuralPosition = StructuralPosition::where('name', $positionName)->first();

        if ($structuralPosition && $official->lecturer) {
            $history = EmployeeStructuralHistory::where('employee_type', get_class($official->lecturer))
                ->where('employee_id', $official->lecturer->id)
                ->where('structural_position_id', $structuralPosition->id)
                ->where('start_date', $official->start_date)
                ->first();

            if ($history) {
                $history->delete();
            }
        }

        // Remove Role if it was Active Kaprodi
        if ($official->position === 'Kaprodi' && $official->is_active && $official->lecturer && $official->lecturer->user) {
             $official->lecturer->user->removeRole('Kaprodi');
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

        $rules = [
            'position' => 'required|in:Kaprodi,Sekretaris',
            'lecturer_id' => 'required|exists:lecturers,id',
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
        ];

        $this->validate($rules);

        $positionName = $this->position === 'Kaprodi' ? 'Ketua Program Studi' : 'Sekretaris Program Studi';
        $structuralPosition = StructuralPosition::firstOrCreate(['name' => $positionName]);

        // Find or Create WorkUnit for this Program
        $workUnit = WorkUnit::firstOrCreate(
            ['name' => 'Program Studi ' . $this->program->name_id],
            ['type' => 'Program Studi']
        );

        // 1. Deactivate current active official for this position
        $currentOfficial = $this->program->officials()
            ->where('position', $this->position)
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        if ($currentOfficial) {
            $endDate = date('Y-m-d', strtotime($this->start_date . ' -1 day'));

            $currentOfficial->update([
                'is_active' => false,
                'end_date' => $endDate,
            ]);

            // Revoke role if Kaprodi (Secretary has no role per requirements)
            if ($this->position === 'Kaprodi' && $currentOfficial->lecturer && $currentOfficial->lecturer->user) {
                $currentOfficial->lecturer->user->removeRole('Kaprodi');
            }

            // Sync: Deactivate Structural History for the OLD lecturer
            if ($currentOfficial->lecturer) {
                EmployeeStructuralHistory::where('employee_type', get_class($currentOfficial->lecturer))
                    ->where('employee_id', $currentOfficial->lecturer->id)
                    ->where('structural_position_id', $structuralPosition->id)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'end_date' => $endDate
                    ]);
            }
        }

        // 2. Create new official record
        ProgramOfficial::create([
            'program_id' => $this->program->id,
            'lecturer_id' => $this->lecturer_id,
            'position' => $this->position,
            'start_date' => $this->start_date,
            'is_active' => true,
            'sk_number' => $this->sk_number,
        ]);

        // 3. Assign Kaprodi role (Only for Kaprodi) and Update History
        $lecturer = Lecturer::find($this->lecturer_id);
        if ($lecturer) {
            if ($this->position === 'Kaprodi' && $lecturer->user) {
                $lecturer->user->assignRole('Kaprodi');
            }

            // Sync: Create Structural History for the NEW lecturer
            EmployeeStructuralHistory::create([
                'employee_type' => get_class($lecturer),
                'employee_id' => $lecturer->id,
                'structural_position_id' => $structuralPosition->id,
                'work_unit_id' => $workUnit->id,
                'sk_number' => $this->sk_number,
                'start_date' => $this->start_date,
                'is_active' => true,
            ]);
        }

        $this->reset(['lecturer_id', 'start_date', 'sk_number']);
        $this->refreshOfficials();
        session()->flash('success', "Pejabat ($positionName) berhasil diperbarui.");
    }

    public function render()
    {
        return view('livewire.master.program.official-manager', [
            'lecturers' => Lecturer::orderBy('full_name_with_degree')->get()
        ])->extends('adminlte::page')->section('content');
    }
}
