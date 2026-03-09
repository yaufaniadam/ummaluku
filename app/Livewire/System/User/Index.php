<?php

namespace App\Livewire\System\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;

#[Title('Manajemen User')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'mahasiswa'; // Default tab
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'activeTab' => ['except' => 'mahasiswa'],
        'sortColumn' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            // Prevent deleting self
            if ($user->id === auth()->id()) {
                session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                return;
            }

            $user->delete();
            session()->flash('success', 'User berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = User::with('roles')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        // Filter based on active tab
        switch ($this->activeTab) {
            case 'mahasiswa':
                $query->role('Mahasiswa');
                break;
            case 'dosen':
                $query->role('Dosen');
                break;
            case 'camaru':
                $query->role('Camaru');
                break;
            case 'staf':
            default:
                // Staf includes everyone NOT in Mahasiswa, Dosen, or Camaru
                $query->whereDoesntHave('roles', function (Builder $q) {
                    $q->whereIn('name', ['Mahasiswa', 'Dosen', 'Camaru']);
                });
                break;
        }

        // Apply sorting
        // Note: Sorting by Role name is tricky in simple Eloquent without join,
        // so we stick to columns on users table for now (name, email, created_at)
        if (in_array($this->sortColumn, ['name', 'email', 'created_at'])) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(10);

        return view('livewire.system.user.index', [
            'users' => $users
        ])->extends('adminlte::page')->section('content');
    }
}
