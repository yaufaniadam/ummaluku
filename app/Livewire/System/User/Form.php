<?php

namespace App\Livewire\System\User;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;

#[Title('Form User')]
class Form extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoles = [];

    public function mount($user = null)
    {
        if ($user) {
            $this->userId = $user;
            $userModel = User::findOrFail($user);
            $this->name = $userModel->name;
            $this->email = $userModel->email;
            $this->selectedRoles = $userModel->roles->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'selectedRoles' => 'array',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $this->validate($rules);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);
            $message = 'User berhasil diperbarui.';
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $message = 'User berhasil dibuat.';
        }

        $user->syncRoles($this->selectedRoles);

        session()->flash('success', $message);
        return redirect()->route('admin.system.users.index');
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        return view('livewire.system.user.form', [
            'roles' => $roles
        ])->extends('adminlte::page')->section('content');
    }
}
