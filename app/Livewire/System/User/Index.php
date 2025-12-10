<?php

namespace App\Livewire\System\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Title;

#[Title('Manajemen User')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            // Prevent deleting self or Super Admin if strictly needed, but let's just do basic check
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
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.system.user.index', [
            'users' => $users
        ])->extends('adminlte::page')->section('content');
    }
}
