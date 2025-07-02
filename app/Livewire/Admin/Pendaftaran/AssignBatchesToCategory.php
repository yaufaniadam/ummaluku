<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\AdmissionCategory;
use App\Models\Batch;
use Livewire\Component;

class AssignBatchesToCategory extends Component
{
    public AdmissionCategory $category;
    public $batches = [];
    public $attachedBatchIds = [];

    public function mount()
    {
        $this->attachedBatchIds = $this->category->batches()->pluck('id')->toArray();
    }

    public function openModal()
    {
        $this->batches = Batch::where('is_active', true)->get();
        // KIRIM DENGAN ARGUMEN BERNAMA 'categoryId'
        $this->dispatch('open-modal', categoryId: $this->category->id);
    }

    public function save()
    {
        $this->category->batches()->sync($this->attachedBatchIds);
        // KIRIM DENGAN ARGUMEN BERNAMA 'categoryId'
        $this->dispatch('close-modal', categoryId: $this->category->id);
        $this->dispatch('show-alert', message: 'Gelombang untuk ' . $this->category->name . ' berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.assign-batches-to-category');
    }
}