<?php

namespace App\Livewire\Master\Program;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\ProgramHead;
use Livewire\Component;

class HeadManager extends Component
{
    public Program $program;
    public $heads;

    // Form variables
    public $lecturer_id;
    public $start_date;
    public $sk_number;

    public function mount(Program $program)
    {
        $this->program = $program;
        $this->refreshHeads();
    }

    public function refreshHeads()
    {
        $this->heads = $this->program->heads()->with('lecturer')->orderBy('start_date', 'desc')->get();
    }

    public function assignHead()
    {
        $this->validate([
            'lecturer_id' => 'required|exists:lecturers,id',
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
        ]);

        // 1. Deactivate current active head
        $currentHead = $this->program->currentHead;
        if ($currentHead) {
            $currentHead->update([
                'is_active' => false,
                'end_date' => date('Y-m-d', strtotime($this->start_date . ' -1 day')), // End date is day before new start
            ]);

            // Revoke Kaprodi role from old user
            if ($currentHead->lecturer && $currentHead->lecturer->user) {
                $currentHead->lecturer->user->removeRole('Kaprodi');
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

            // Also ensure they are Staff of this Program?
            // Ideally yes, but changing Staff record might be intrusive if they are structurally elsewhere.
            // But for Prodi Portal access, they need `user->staff->program_id` logic to work.
            // So we should check if they have a staff record.

            if (!$lecturer->user->staff) {
                 // Create staff record if missing? Or just warn?
                 // For now let's assume system admin handles staff assignment separately or we auto-create.
                 // Let's at least update program_id if staff exists.
            } else {
                // If they are staff, update their program_id to this program so they can see the dashboard
                 $lecturer->user->staff->update(['program_id' => $this->program->id]);
            }
        }

        $this->reset(['lecturer_id', 'start_date', 'sk_number']);
        $this->refreshHeads();
        session()->flash('success', 'Kaprodi berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.master.program.head-manager', [
            'lecturers' => Lecturer::orderBy('name')->get()
        ])->extends('adminlte::page')->section('content');
    }
}
