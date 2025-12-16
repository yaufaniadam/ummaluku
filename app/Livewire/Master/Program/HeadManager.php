<?php

namespace App\Livewire\Master\Program;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\ProgramHead;
use App\Models\Staff;
use App\Models\StructuralPosition;
use App\Models\WorkUnit;
use App\Models\EmployeeStructuralHistory;
use Livewire\Component;

class HeadManager extends Component
{
    public Program $program;
    public $heads;

    // Form variables
    public $lecturer_id;
    public $start_date;
    public $sk_number;

    // Additional info needed if creating staff record
    public $gender;
    public $showGenderInput = false;

    public function mount(Program $program)
    {
        $this->program = $program;
        $this->refreshHeads();
    }

    public function updatedLecturerId($value)
    {
        $this->showGenderInput = false;
        // Logic create staff dihapus, jadi gender input tidak diperlukan lagi
    }

    public function refreshHeads()
    {
        $this->heads = $this->program->heads()->with('lecturer')->orderBy('start_date', 'desc')->get();
    }

    public function assignHead()
    {
        $rules = [
            'lecturer_id' => 'required|exists:lecturers,id',
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
        ];

        $this->validate($rules);

        $structuralPosition = StructuralPosition::firstOrCreate(['name' => 'Ketua Program Studi']);

        // Find or Create WorkUnit for this Program
        // Assuming WorkUnit name matches Program name + " (Program Studi)" or similar,
        // or just Program Name. Seeder uses "Program Studi X".
        // Let's try to match loosely or create one.
        $workUnit = WorkUnit::firstOrCreate(
            ['name' => 'Program Studi ' . $this->program->name_id],
            ['type' => 'Program Studi']
        );

        // 1. Deactivate current active head
        $currentHead = $this->program->currentHead;
        if ($currentHead) {
            $endDate = date('Y-m-d', strtotime($this->start_date . ' -1 day'));

            $currentHead->update([
                'is_active' => false,
                'end_date' => $endDate,
            ]);

            // Revoke Kaprodi role from old user
            if ($currentHead->lecturer && $currentHead->lecturer->user) {
                $currentHead->lecturer->user->removeRole('Kaprodi');
            }

            // Sync: Deactivate Structural History for the OLD lecturer
            if ($currentHead->lecturer) {
                EmployeeStructuralHistory::where('employee_type', get_class($currentHead->lecturer))
                    ->where('employee_id', $currentHead->lecturer->id)
                    ->where('structural_position_id', $structuralPosition->id)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'end_date' => $endDate
                    ]);
            }
        }

        // 2. Create new head record
        $newHead = ProgramHead::create([
            'program_id' => $this->program->id,
            'lecturer_id' => $this->lecturer_id,
            'start_date' => $this->start_date,
            'is_active' => true,
            'sk_number' => $this->sk_number,
        ]);

        // 3. Assign Kaprodi role to new user
        $lecturer = Lecturer::find($this->lecturer_id);
        if ($lecturer && $lecturer->user) {
            $lecturer->user->assignRole('Kaprodi');

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

        $this->reset(['lecturer_id', 'start_date', 'sk_number', 'gender', 'showGenderInput']);
        $this->refreshHeads();
        session()->flash('success', 'Kaprodi berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.master.program.head-manager', [
            'lecturers' => Lecturer::orderBy('full_name_with_degree')->get()
        ])->extends('adminlte::page')->section('content');
    }
}
