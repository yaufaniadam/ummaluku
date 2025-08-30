<?php

namespace App\Livewire\Admin\FeeComponent;

use App\Models\FeeComponent;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FeeComponentForm extends Component
{
    public ?FeeComponent $feeComponent = null;

    // Form properties
    public $name;
    public $type;

    public function mount(FeeComponent $feeComponent = null)
    {
        if ($feeComponent->exists) {
            $this->feeComponent = $feeComponent;
            $this->name = $feeComponent->name;
            $this->type = $feeComponent->type;
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('fee_components', 'name')->ignore($this->feeComponent?->id)],
            'type' => 'required|in:fixed,per_sks,per_course',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->feeComponent) {
            $this->feeComponent->update($validatedData);
            session()->flash('success', 'Komponen biaya berhasil diperbarui.');
        } else {
            FeeComponent::create($validatedData);
            session()->flash('success', 'Komponen biaya berhasil ditambahkan.');
        }

        $this->dispatch('fee-component-updated');
        return $this->redirect(route('admin.fee-components.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.fee-component.fee-component-form');
    }
}