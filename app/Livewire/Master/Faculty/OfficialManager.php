<?php

namespace App\Livewire\Master\Faculty;

use App\Models\Faculty;
use App\Models\FacultyOfficial;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\StructuralPosition;
use App\Models\WorkUnit;
use App\Models\EmployeeStructuralHistory;
use Livewire\Component;

class OfficialManager extends Component
{
    public Faculty $faculty;

    // Lists for history
    public $deans;
    public $ktus;
    public $kabags;

    // Form variables
    public $position = 'Dekan'; // 'Dekan', 'Kepala Tata Usaha', 'Kepala Bagian Administrasi Akademik'
    public $employee_id; // Can be Lecturer ID or Staff ID
    public $employee_type; // To explicitly track what we are selecting

    public $start_date;
    public $sk_number;

    // Editing State
    public $editingId = null;
    public $isEditing = false;
    public $edit_end_date;
    public $edit_is_active;

    // Selection lists
    public $available_lecturers = [];
    public $available_staff = [];

    protected $listeners = ['deleteOfficial' => 'delete'];

    public function mount(Faculty $faculty)
    {
        $this->faculty = $faculty;
        $this->refreshOfficials();
        $this->loadCandidates();
    }

    public function refreshOfficials()
    {
        $this->deans = $this->faculty->officials()
            ->where('position', 'Dekan')
            ->with('employee')
            ->orderBy('start_date', 'desc')
            ->get();

        $this->ktus = $this->faculty->officials()
            ->where('position', 'Kepala Tata Usaha')
            ->with('employee')
            ->orderBy('start_date', 'desc')
            ->get();

        $this->kabags = $this->faculty->officials()
            ->where('position', 'Kepala Bagian Administrasi Akademik')
            ->with('employee')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function updatedPosition()
    {
        if ($this->isEditing) {
            $this->cancelEdit();
        } else {
            $this->reset(['employee_id', 'start_date', 'sk_number']);
        }
        $this->loadCandidates();
    }

    public function loadCandidates()
    {
        // Based on requirements:
        // Dekan -> Lecturer
        // KTU & Kabag -> Staff

        if ($this->position === 'Dekan') {
            $this->available_lecturers = Lecturer::orderBy('full_name_with_degree')->get();
            $this->available_staff = [];
            $this->employee_type = Lecturer::class;
        } else {
            $this->available_lecturers = [];
            // Sort Staff by their User's name
            $this->available_staff = Staff::with('user')->get()->sortBy(function($staff) {
                return $staff->user->name ?? '';
            });
            $this->employee_type = Staff::class;
        }
    }

    public function edit($id)
    {
        $official = FacultyOfficial::find($id);
        if (!$official) return;

        $this->isEditing = true;
        $this->editingId = $official->id;
        $this->position = $official->position;
        $this->loadCandidates(); // Reload candidates for the correct position type
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
        $this->loadCandidates();
    }

    public function updateOfficial()
    {
        $this->validate([
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
            'edit_end_date' => 'nullable|date|after_or_equal:start_date',
            'edit_is_active' => 'boolean'
        ]);

        $official = FacultyOfficial::find($this->editingId);
        if (!$official) return;

        $oldStartDate = $official->start_date;

        $official->update([
            'start_date' => $this->start_date,
            'end_date' => $this->edit_end_date,
            'sk_number' => $this->sk_number,
            'is_active' => $this->edit_is_active,
        ]);

        // Sync with EmployeeStructuralHistory
        $structuralPosition = StructuralPosition::where('name', $official->position)->first();

        if ($structuralPosition && $official->employee) {
            $history = EmployeeStructuralHistory::where('employee_type', $official->employee_type)
                ->where('employee_id', $official->employee_id)
                ->where('structural_position_id', $structuralPosition->id)
                ->where('start_date', $oldStartDate)
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

        $this->dispatch('alert', type: 'success', message: "Data pejabat ($this->position) berhasil diperbarui.");
        $this->cancelEdit();
        $this->refreshOfficials();
    }

    public function delete($id)
    {
        $official = FacultyOfficial::find($id);
        if (!$official) return;

        // Sync Delete: Find History before deleting
        $structuralPosition = StructuralPosition::where('name', $official->position)->first();

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

        // Remove Role if needed (Dekan only)
        if ($official->position === 'Dekan' && $official->is_active && $official->employee && $official->employee->user) {
             $official->employee->user->removeRole('Dekan');
        }

        $official->delete();

        $this->dispatch('alert', type: 'success', message: "Data pejabat berhasil dihapus.");
        $this->refreshOfficials();
    }

    public function assignOfficial()
    {
        if ($this->isEditing) {
            $this->updateOfficial();
            return;
        }

        $rules = [
            'position' => 'required|in:Dekan,Kepala Tata Usaha,Kepala Bagian Administrasi Akademik',
            'employee_id' => 'required',
            'start_date' => 'required|date',
            'sk_number' => 'nullable|string',
        ];

        $this->validate($rules);

        // Verify existence based on expected type
        if ($this->position === 'Dekan') {
            $employeeClass = Lecturer::class;
            if (!Lecturer::find($this->employee_id)) {
                $this->addError('employee_id', 'Dosen tidak ditemukan.');
                return;
            }
        } else {
            $employeeClass = Staff::class;
            if (!Staff::find($this->employee_id)) {
                $this->addError('employee_id', 'Tendik tidak ditemukan.');
                return;
            }
        }

        $structuralPosition = StructuralPosition::firstOrCreate(['name' => $this->position]);

        // Find or Create WorkUnit for this Faculty
        // Name usually matches Faculty Name
        $workUnit = WorkUnit::firstOrCreate(
            ['name' => 'Fakultas ' . $this->faculty->name_id],
            ['type' => 'Fakultas'] // Or 'Fakultas'? Need to check allowed types. Usually flexible.
        );

        // 1. Deactivate current active official for this position in this faculty
        $currentOfficial = $this->faculty->officials()
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

            // Revoke role if needed
            if ($this->position === 'Dekan' &&
                $currentOfficial->employee_type === Lecturer::class &&
                $currentOfficial->employee &&
                $currentOfficial->employee->user
            ) {
                $currentOfficial->employee->user->removeRole('Dekan');
            }

            // Sync: Deactivate Structural History for the OLD employee
            if ($currentOfficial->employee) {
                EmployeeStructuralHistory::where('employee_type', $currentOfficial->employee_type)
                    ->where('employee_id', $currentOfficial->employee_id)
                    ->where('structural_position_id', $structuralPosition->id)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'end_date' => $endDate
                    ]);
            }
        }

        // 2. Create new official record
        FacultyOfficial::create([
            'faculty_id' => $this->faculty->id,
            'employee_type' => $employeeClass,
            'employee_id' => $this->employee_id,
            'position' => $this->position,
            'start_date' => $this->start_date,
            'is_active' => true,
            'sk_number' => $this->sk_number,
        ]);

        // 3. Sync Logic
        $employee = $employeeClass::find($this->employee_id);

        if ($employee) {
            // Assign Role (Only for Dekan as per current pattern)
            if ($this->position === 'Dekan' && $employee->user) {
                $employee->user->assignRole('Dekan'); // Ensure this role exists or is created? usually roles are pre-seeded.
            }

            // Sync: Create Structural History for the NEW employee
            EmployeeStructuralHistory::create([
                'employee_type' => $employeeClass,
                'employee_id' => $employee->id,
                'structural_position_id' => $structuralPosition->id,
                'work_unit_id' => $workUnit->id,
                'sk_number' => $this->sk_number,
                'start_date' => $this->start_date,
                'is_active' => true,
            ]);
        }

        $this->reset(['employee_id', 'start_date', 'sk_number']);
        $this->refreshOfficials();

        // Use dispatch for toastr
        $this->dispatch('alert', type: 'success', message: "Pejabat ($this->position) berhasil diperbarui.");
    }

    public function render()
    {
        return view('livewire.master.faculty.official-manager')
            ->extends('adminlte::page')
            ->section('content');
    }
}
