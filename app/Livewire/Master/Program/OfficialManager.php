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
        $this->reset(['lecturer_id', 'start_date', 'sk_number']);
    }

    public function assignOfficial()
    {
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
