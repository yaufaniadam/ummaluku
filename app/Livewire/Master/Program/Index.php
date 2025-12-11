<?php

namespace App\Livewire\Master\Program;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $programs = Program::with(['faculty', 'currentHead.lecturer'])
            ->where('name_id', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.master.program.index', [
            'programs' => $programs
        ])->extends('adminlte::page')->section('content');
    }
}
