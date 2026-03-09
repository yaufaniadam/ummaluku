<?php

namespace Tests\Feature\System\User;

use App\Livewire\System\User\Index;
use App\Livewire\System\User\Form;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Livewire\Livewire;

class UserManagementTest extends TestCase
{
    public function test_can_view_user_index()
    {
        $adminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($adminRole);

        $this->actingAs($user)
            ->get(route('admin.system.users.index'))
            ->assertStatus(200)
            ->assertSeeLivewire(Index::class);
    }

    public function test_can_create_user_with_role()
    {
        $adminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $targetRole = Role::create(['name' => 'Staf Admisi', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('name', 'New Staff')
            ->set('email', 'staff@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('selectedRoles', [$targetRole->name])
            ->call('save')
            ->assertRedirect(route('admin.system.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'staff@example.com']);
        $newUser = User::where('email', 'staff@example.com')->first();
        $this->assertTrue($newUser->hasRole('Staf Admisi'));
    }

    public function test_can_edit_user_and_update_role()
    {
        $adminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role1 = Role::create(['name' => 'Role A', 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'Role B', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $userToEdit = User::factory()->create(['name' => 'Old Name']);
        $userToEdit->assignRole($role1);

        Livewire::actingAs($admin)
            ->test(Form::class, ['user' => $userToEdit->id])
            ->set('name', 'New Name')
            ->set('selectedRoles', [$role2->name])
            ->call('save')
            ->assertRedirect(route('admin.system.users.index'));

        $this->assertDatabaseHas('users', ['name' => 'New Name']);

        $userToEdit->refresh();
        $this->assertFalse($userToEdit->hasRole('Role A'));
        $this->assertTrue($userToEdit->hasRole('Role B'));
    }

    public function test_can_delete_user()
    {
        $adminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $userToDelete = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('delete', $userToDelete->id);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }
}
