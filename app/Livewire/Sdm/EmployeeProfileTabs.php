<?php

namespace App\Livewire\Sdm;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\StructuralPosition;
use App\Models\FunctionalPosition;
use App\Models\EmployeeRank;
use App\Models\WorkUnit;
use App\Models\EmployeeDocumentType;

class EmployeeProfileTabs extends Component
{
    use WithFileUploads;

    public $employee; // Can be Lecturer or Staff (Polymorphic)
    public $isSelfService = false; // Flag to indicate if user is editing their own profile
    public $activeTab = 'structural'; // structural, functional, rank, documents, education, inpassing

    // Modal State
    public $isOpen = false;
    public $editId = null;
    public $confirmingDeletion = false;
    public $deleteId = null;

    // Form Data
    public $formData = [];
    public $uploadFile = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($employee, $isSelfService = false)
    {
        $this->employee = $employee;
        $this->isSelfService = $isSelfService;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.sdm.employee-profile-tabs', [
            'histories' => $this->getHistories(),
            'structuralPositions' => StructuralPosition::orderBy('name')->get(),
            'functionalPositions' => FunctionalPosition::orderBy('name')->get(),
            'ranks' => EmployeeRank::orderBy('grade')->get(),
            'workUnits' => WorkUnit::orderBy('name')->get(),
            'documentTypes' => EmployeeDocumentType::orderBy('name')->get(),
        ]);
    }

    private function getHistories()
    {
        switch ($this->activeTab) {
            case 'structural':
                return $this->employee->structuralHistories()->with(['structuralPosition', 'workUnit'])->latest('start_date')->get();
            case 'functional':
                return $this->employee->functionalHistories()->with('functionalPosition')->latest('tmt')->get();
            case 'rank':
                return $this->employee->rankHistories()->with('employeeRank')->latest('tmt')->get();
            case 'documents':
                return $this->employee->employeeDocuments()->with('documentType')->latest()->get();
            case 'education':
                return $this->employee->educationHistories()->latest('graduation_year')->get();
            case 'inpassing':
                return $this->employee->inpassingHistories()->with('employeeRank')->latest('tmt')->get();
            default:
                return collect();
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->isOpen = false;
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->editId = null;
    }

    public function edit($id)
    {
        $this->editId = $id;
        $relation = $this->getRelationName();
        $model = $this->employee->$relation()->find($id);

        if ($model) {
            $this->formData = $model->toArray();

            // Format dates for input
            if (isset($this->formData['start_date'])) $this->formData['start_date'] = substr($this->formData['start_date'], 0, 10);
            if (isset($this->formData['end_date'])) $this->formData['end_date'] = substr($this->formData['end_date'], 0, 10);
            if (isset($this->formData['tmt'])) $this->formData['tmt'] = substr($this->formData['tmt'], 0, 10);

            $this->isOpen = true;
        }
    }

    public function save()
    {
        if ($this->isSelfService && $this->activeTab !== 'documents') {
            abort(403, 'Unauthorized action.');
        }

        $this->validateData();

        $relation = $this->getRelationName();
        $data = $this->formData;

        // Handle File Upload
        if ($this->activeTab === 'documents') {
            if ($this->uploadFile) {
                $path = $this->uploadFile->store('employee-documents', 'public');
                $data['file_path'] = $path;
                $data['file_name'] = $this->uploadFile->getClientOriginalName();
            }
        } elseif ($this->activeTab === 'education') {
            if ($this->uploadFile) {
                // Delete old certificate if updating
                if ($this->editId) {
                    $old = $this->employee->$relation()->find($this->editId);
                    if ($old && $old->certificate_path) {
                        \Illuminate\Support\Facades\Storage::delete($old->certificate_path);
                    }
                }
                $path = $this->uploadFile->store('certificates', 'public');
                $data['certificate_path'] = $path;
            }
        } elseif ($this->activeTab === 'inpassing') {
            if ($this->uploadFile) {
                // Delete old document if updating
                if ($this->editId) {
                    $old = $this->employee->$relation()->find($this->editId);
                    if ($old && $old->document_path) {
                        \Illuminate\Support\Facades\Storage::delete($old->document_path);
                    }
                }
                $path = $this->uploadFile->store('inpassing-documents', 'public');
                $data['document_path'] = $path;
            }
        }

        if ($this->editId) {
            $this->employee->$relation()->find($this->editId)->update($data);
            session()->flash('message', 'Data updated successfully.');
        } else {
            $this->employee->$relation()->create($data);
            session()->flash('message', 'Data added successfully.');
        }

        $this->isOpen = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        if ($this->isSelfService && $this->activeTab !== 'documents') {
            abort(403, 'Unauthorized action.');
        }
        $this->deleteId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->isSelfService && $this->activeTab !== 'documents') {
            abort(403, 'Unauthorized action.');
        }

        $relation = $this->getRelationName();
        $this->employee->$relation()->find($this->deleteId)?->delete();

        $this->confirmingDeletion = false;
        $this->deleteId = null;
        session()->flash('message', 'Data deleted successfully.');
    }

    private function getRelationName()
    {
        return match ($this->activeTab) {
            'structural' => 'structuralHistories',
            'functional' => 'functionalHistories',
            'rank' => 'rankHistories',
            'documents' => 'employeeDocuments',
            'education' => 'educationHistories',
            'inpassing' => 'inpassingHistories',
        };
    }

    private function resetForm()
    {
        $this->uploadFile = null;
        $this->formData = [
            'is_active' => true,
        ];

        if ($this->activeTab === 'structural') {
            $this->formData += ['structural_position_id' => '', 'work_unit_id' => '', 'sk_number' => '', 'start_date' => '', 'end_date' => ''];
        } elseif ($this->activeTab === 'functional') {
            $this->formData += ['functional_position_id' => '', 'sk_number' => '', 'tmt' => ''];
        } elseif ($this->activeTab === 'rank') {
            $this->formData += ['employee_rank_id' => '', 'sk_number' => '', 'tmt' => '', 'years_of_service' => 0, 'months_of_service' => 0];
        } elseif ($this->activeTab === 'documents') {
            $this->formData += ['employee_document_type_id' => '', 'description' => ''];
        } elseif ($this->activeTab === 'education') {
            $this->formData += ['education_level' => '', 'institution_name' => '', 'graduation_year' => '', 'major' => ''];
        } elseif ($this->activeTab === 'inpassing') {
            $this->formData += ['employee_rank_id' => '', 'sk_number' => '', 'sk_date' => '', 'tmt' => ''];
        }
    }

    private function validateData()
    {
        $rules = [];

        if ($this->activeTab === 'structural') {
            $rules = [
                'formData.structural_position_id' => 'required|exists:structural_positions,id',
                'formData.work_unit_id' => 'nullable|exists:work_units,id',
                'formData.sk_number' => 'nullable|string',
                'formData.start_date' => 'required|date',
                'formData.end_date' => 'nullable|date|after_or_equal:formData.start_date',
                'formData.is_active' => 'boolean',
            ];
        } elseif ($this->activeTab === 'functional') {
            $rules = [
                'formData.functional_position_id' => 'required|exists:functional_positions,id',
                'formData.sk_number' => 'nullable|string',
                'formData.tmt' => 'required|date',
                'formData.is_active' => 'boolean',
            ];
        } elseif ($this->activeTab === 'rank') {
            $rules = [
                'formData.employee_rank_id' => 'required|exists:employee_ranks,id',
                'formData.sk_number' => 'nullable|string',
                'formData.tmt' => 'required|date',
                'formData.years_of_service' => 'nullable|integer',
                'formData.months_of_service' => 'nullable|integer',
                'formData.is_active' => 'boolean',
            ];
        } elseif ($this->activeTab === 'documents') {
            $rules = [
                'formData.employee_document_type_id' => 'required|exists:employee_document_types,id',
                'formData.description' => 'nullable|string',
            ];
            if (!$this->editId) {
                $rules['uploadFile'] = 'required|file|max:10240'; // 10MB
            }
        } elseif ($this->activeTab === 'education') {
            $rules = [
                'formData.education_level' => 'required|in:SD,SMP,SMA,D3,D4,S1,S2,S3',
                'formData.institution_name' => 'required|string|max:255',
                'formData.graduation_year' => 'required|integer|min:1950|max:' . (date('Y') + 10),
                'formData.major' => 'nullable|string|max:255',
            ];
            if (!$this->editId) {
                $rules['uploadFile'] = 'nullable|file|mimes:pdf|max:5120'; // 5MB, PDF only
            } else {
                $rules['uploadFile'] = 'nullable|file|mimes:pdf|max:5120';
            }
        } elseif ($this->activeTab === 'inpassing') {
            $rules = [
                'formData.employee_rank_id' => 'required|exists:employee_ranks,id',
                'formData.sk_number' => 'nullable|string|max:255',
                'formData.sk_date' => 'nullable|date',
                'formData.tmt' => 'required|date',
                'formData.is_active' => 'boolean',
            ];
            $rules['uploadFile'] = 'nullable|file|mimes:pdf|max:5120'; // 5MB, PDF only
        }

        $this->validate($rules);
    }
}
