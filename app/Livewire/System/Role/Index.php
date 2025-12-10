<?php

namespace App\Livewire\System\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Title;

#[Title('Manajemen Role')]
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
        $role = Role::find($id);

        if ($role) {
            // Prevent deleting Super Admin if needed, though Spatie doesn't block it by default.
            if ($role->name === 'Super Admin') {
                session()->flash('error', 'Role Super Admin tidak dapat dihapus.');
                return;
            }

            $role->delete();
            session()->flash('success', 'Role berhasil dihapus.');
        }
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.system.role.index', [
            'roles' => $roles
        ])->layout('adminlte::page');
    }
}
