<?php

namespace App\Livewire\Admin\TuitionFee;

use App\Models\FeeComponent;
use App\Models\FeeStructure; // Ganti dari TuitionFee
use App\Models\Program;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TuitionFeeForm extends Component
{
    public ?FeeStructure $tuitionFee = null; // Ganti dari TuitionFee
    public $programs;
    public $feeComponents;

    // Form properties
    public $program_id;
    public $entry_year;
    public $fee_component_id;
    public $amount;

    public function mount(FeeStructure $tuitionFee = null) // Ganti dari TuitionFee
    {
        $this->programs = Program::orderBy('name_id')->get();
        $this->feeComponents = FeeComponent::orderBy('name')->get();

        if ($tuitionFee->exists) {
            $this->tuitionFee = $tuitionFee;
            $this->program_id = $tuitionFee->program_id;
            $this->entry_year = $tuitionFee->entry_year;
            $this->fee_component_id = $tuitionFee->fee_component_id;
            $this->amount = $tuitionFee->amount;
        }
    }

    protected function rules()
    {
        return [
            'program_id' => 'required|exists:programs,id',
            'entry_year' => 'required|digits:4|integer|min:2020',
            'fee_component_id' => [
                'required',
                'exists:fee_components,id',
                Rule::unique('fee_structures')->where(function ($query) {
                    return $query->where('program_id', $this->program_id)
                                 ->where('entry_year', $this->entry_year);
                })->ignore($this->tuitionFee?->id)
            ],
            'amount' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->tuitionFee) {
            $this->tuitionFee->update($validatedData);
            session()->flash('success', 'Biaya kuliah berhasil diperbarui.');
        } else {
            FeeStructure::create($validatedData); // Ganti modelnya
            session()->flash('success', 'Biaya kuliah berhasil ditambahkan.');
        }

        $this->dispatch('tuition-fee-updated');
        return $this->redirect(route('admin.tuition-fees.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.tuition-fee.tuition-fee-form');
    }
}