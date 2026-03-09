<?php

namespace App\Livewire\Sdm\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeeRank;
use App\Models\StructuralPosition;
use App\Models\FunctionalPosition;
use App\Models\EmploymentStatus;
use App\Models\EmployeeDocumentType;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $activeTab = 'ranks';

    // Modal State
    public $isOpen = false;
    public $deleteId = null;
    public $editId = null;
    public $confirmingDeletion = false;

    // Form Data (Generic)
    public $formData = [];

    protected $queryString = ['activeTab'];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->resetForm();
        $this->isOpen = false;
    }

    public function render()
    {
        $data = $this->getData();

        return view('livewire.sdm.master.index', [
            'data' => $data
        ])->extends('adminlte::page')->section('content');
    }

    private function getData()
    {
        switch ($this->activeTab) {
            case 'ranks':
                return EmployeeRank::orderBy('grade')->paginate(10);
            case 'structural':
                return StructuralPosition::orderBy('name')->paginate(10);
            case 'functional':
                return FunctionalPosition::orderBy('name')->paginate(10);
            case 'statuses':
                return EmploymentStatus::orderBy('name')->paginate(10);
            case 'documents':
                return EmployeeDocumentType::orderBy('name')->paginate(10);
            default:
                return [];
        }
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
        $model = $this->getModelClass()::find($id);

        if ($model) {
            $this->formData = $model->toArray();
            $this->isOpen = true;
        }
    }

    public function save()
    {
        $this->validateData();

        $modelClass = $this->getModelClass();

        if ($this->editId) {
            $model = $modelClass::find($this->editId);
            $model->update($this->formData);
            session()->flash('message', 'Data successfully updated.');
        } else {
            $modelClass::create($this->formData);
            session()->flash('message', 'Data successfully created.');
        }

        $this->isOpen = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $modelClass = $this->getModelClass();
        $modelClass::find($this->deleteId)?->delete();

        $this->confirmingDeletion = false;
        $this->deleteId = null;
        session()->flash('message', 'Data successfully deleted.');
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->deleteId = null;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function getModelClass()
    {
        return match ($this->activeTab) {
            'ranks' => EmployeeRank::class,
            'structural' => StructuralPosition::class,
            'functional' => FunctionalPosition::class,
            'statuses' => EmploymentStatus::class,
            'documents' => EmployeeDocumentType::class,
        };
    }

    private function resetForm()
    {
        // Default fields for most models
        $this->formData = [
            'name' => '',
            'description' => '',
        ];

        // Specific fields
        if ($this->activeTab === 'ranks') {
            $this->formData['grade'] = '';
        } elseif ($this->activeTab === 'structural') {
            $this->formData['code'] = '';
        } elseif ($this->activeTab === 'functional') {
            $this->formData['code'] = '';
            $this->formData['type'] = 'academic';
        } elseif ($this->activeTab === 'documents') {
            $this->formData['is_mandatory'] = false;
        }
    }

    private function validateData()
    {
        $rules = [
            'formData.name' => 'required|string|max:255',
            'formData.description' => 'nullable|string',
        ];

        if ($this->activeTab === 'ranks') {
            $rules['formData.grade'] = 'required|string|max:10';
        } elseif ($this->activeTab === 'structural') {
            $rules['formData.code'] = 'nullable|string|max:50';
        } elseif ($this->activeTab === 'functional') {
            $rules['formData.code'] = 'nullable|string|max:50';
            $rules['formData.type'] = 'required|in:academic,non-academic';
        } elseif ($this->activeTab === 'documents') {
            $rules['formData.is_mandatory'] = 'boolean';
        }

        $this->validate($rules);
    }
}
