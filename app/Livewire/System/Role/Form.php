<?php

namespace App\Livewire\System\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Title;

#[Title('Form Role')]
class Form extends Component
{
    public $roleId;
    public $name;
    public $guard_name = 'web';
    public $selectedPermissions = [];
    public $selectAll = false;

    public function mount($role = null)
    {
        if ($role) {
            $this->roleId = $role;
            $roleModel = Role::findOrFail($role);
            $this->name = $roleModel->name;
            $this->guard_name = $roleModel->guard_name;
            $this->selectedPermissions = $roleModel->permissions->pluck('name')->toArray();
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPermissions = Permission::pluck('name')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'guard_name' => 'required|string|max:255',
            'selectedPermissions' => 'array'
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
            $message = 'Role berhasil diperbarui.';
        } else {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
            $message = 'Role berhasil dibuat.';
        }

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success', $message);
        return redirect()->route('admin.system.roles.index');
    }

    public function render()
    {
        $permissions = Permission::orderBy('name')->get();
        // Group permissions by prefix if possible (e.g., 'user-create' -> 'user')
        // For now, let's just list them. We can improve grouping later if requested.

        return view('livewire.system.role.form', [
            'permissions' => $permissions
        ])->extends('adminlte::page')->section('content');
    }
}
