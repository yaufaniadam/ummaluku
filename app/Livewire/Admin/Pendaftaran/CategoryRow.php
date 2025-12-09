<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\AdmissionCategory;
use App\Models\Batch;
use Livewire\Component;

class CategoryRow extends Component
{
    public AdmissionCategory $category;
    public $batches = [];
    public $attachedBatchIds = [];

    public function mount()
    {
        // Load initial state
        $this->refreshAttachedBatches();
    }

    public function refreshAttachedBatches()
    {
        // Reload relationships to ensure fresh data for display
        $this->category->load('batches');
        $this->attachedBatchIds = $this->category->batches()->pluck('id')->toArray();
    }

    public function openModal()
    {
        // Fetch active batches only when opening modal to save resources on load
        $this->batches = Batch::where('is_active', true)->get();
        $this->dispatch('open-modal', categoryId: $this->category->id);
    }

    public function save()
    {
        $this->category->batches()->sync($this->attachedBatchIds);

        // Refresh the component's data to update the UI
        $this->refreshAttachedBatches();

        $this->dispatch('close-modal', categoryId: $this->category->id);
        $this->dispatch('show-alert', message: 'Gelombang untuk ' . $this->category->name . ' berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.category-row');
    }
}
