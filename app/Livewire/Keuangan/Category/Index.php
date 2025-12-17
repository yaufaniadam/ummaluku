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
            if ($category->transactions()->exists()) {
                $this->dispatch('error', 'Kategori tidak dapat dihapus karena sudah memiliki transaksi.');
                return;
            }
            $category->delete();
            $this->dispatch('success', 'Kategori berhasil dihapus.');
        }
    }
}
