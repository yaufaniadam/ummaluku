<?php

namespace App\Livewire\Keuangan\Category;

use App\Models\TransactionCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $typeFilter = '';

    protected $listeners = ['categorySaved' => '$refresh'];

    public function render()
    {
        $categories = TransactionCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.keuangan.category.index', [
            'categories' => $categories
        ])->extends('adminlte::page')->section('content');
    }

    public function delete($id)
    {
        $category = TransactionCategory::find($id);
        if ($category) {
            // With SoftDeletes, we can safely delete the category.
            // Existing transactions will still reference the record (which is just marked deleted).
            $category->delete();
            $this->dispatch('success', 'Kategori berhasil dihapus (diarsipkan).');
        }
    }
}
