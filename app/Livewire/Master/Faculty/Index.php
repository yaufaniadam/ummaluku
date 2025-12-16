<?php

namespace App\Livewire\Master\Faculty;

use App\Models\Faculty;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    // For Edit Modal
    public $faculty_id;
    public $name_id;
    public $name_en;

    protected $listeners = ['refresh' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(Faculty $faculty)
    {
        $this->faculty_id = $faculty->id;
        $this->name_id = $faculty->name_id;
        $this->name_en = $faculty->name_en;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate([
            'name_id' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $faculty = Faculty::find($this->faculty_id);
        $faculty->update([
            'name_id' => $this->name_id,
            'name_en' => $this->name_en,
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('alert', type: 'success', message: 'Fakultas berhasil diperbarui.');
    }

    public function render()
    {
        $faculties = Faculty::query()
            ->when($this->search, function ($query) {
                $query->where('name_id', 'like', '%' . $this->search . '%')
                      ->orWhere('name_en', 'like', '%' . $this->search . '%');
            })
            ->withCount('programs')
            ->paginate(10);

        return view('livewire.master.faculty.index', [
            'faculties' => $faculties
        ])->extends('adminlte::page')->section('content');
    }
}
