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

    // Selection lists
    public $available_lecturers = [];
    public $available_staff = [];

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
        $this->reset(['employee_id', 'start_date', 'sk_number']);
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

    public function assignOfficial()
    {
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

            // Note: Currently no roles defined for KTU/Kabag in requirements, but can be added if needed.

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
