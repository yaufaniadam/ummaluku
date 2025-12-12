<?php

namespace App\Livewire\Prodi\Course;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function render()
    {
        $user = auth()->user();
        if (!$user->staff || !$user->staff->program_id) {
            abort(403, 'Akses ditolak. Anda bukan staf prodi.');
        }

        $programId = $user->staff->program_id;

        $courses = Course::whereHas('curriculums', function ($query) use ($programId) {
            $query->where('program_id', $programId);
        })
        ->where(function($q) {
             $q->where('name', 'like', '%' . $this->search . '%')
               ->orWhere('code', 'like', '%' . $this->search . '%');
        })
        ->with(['curriculums'])
        ->paginate(10);

        return view('livewire.prodi.course.index', [
            'courses' => $courses
        ])->extends('adminlte::page')->section('content');
    }
}
