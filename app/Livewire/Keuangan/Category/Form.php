<?php

namespace App\Livewire\Keuangan\Category;

use App\Models\TransactionCategory;
use Livewire\Component;

class Form extends Component
{
    public $categoryId;
    public $name;
    public $type = 'income'; // Default
    public $description;

    protected $listeners = ['createCategory' => 'create', 'editCategory' => 'edit'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:income,expense',
        'description' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.keuangan.category.form');
    }

    public function create()
    {
        $this->reset();
        $this->type = 'income'; // Reset to default
        $this->dispatch('show-category-modal');
    }

    public function edit($id)
    {
        $this->reset();
        $this->categoryId = $id;
        $category = TransactionCategory::find($id);
        $this->name = $category->name;
        $this->type = $category->type;
        $this->description = $category->description;
        $this->dispatch('show-category-modal');
    }

    public function save()
    {
        $this->validate();

        TransactionCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
            ]
        );

        $this->dispatch('hide-category-modal');
        $this->dispatch('categorySaved'); // Notify parent to refresh
        $this->dispatch('success', $this->categoryId ? 'Kategori diperbarui.' : 'Kategori dibuat.');
    }
}
