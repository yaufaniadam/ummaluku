<?php

namespace App\Livewire\Master\Program;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\ProgramHead;
use App\Models\Staff;
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
        if ($value) {
            $lecturer = Lecturer::find($value);
            if ($lecturer && $lecturer->user && !$lecturer->user->staff) {
                $this->showGenderInput = true;
            }
        }
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

        if ($this->showGenderInput) {
            $rules['gender'] = 'required|in:L,P';
        }

        $this->validate($rules);

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

            // Check if staff record exists
            if (!$lecturer->user->staff) {
                 // Create staff record
                 Staff::create([
                     'user_id' => $lecturer->user->id,
                     'nip' => $lecturer->nidn, // Use NIDN as NIP
                     'gender' => $this->gender,
                     'program_id' => $this->program->id,
                 ]);
            } else {
                // If they are staff, update their program_id to this program so they can see the dashboard
                 $lecturer->user->staff->update(['program_id' => $this->program->id]);
            }
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
