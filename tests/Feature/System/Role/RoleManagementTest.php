<?php

namespace Tests\Feature\System\Role;

use App\Livewire\System\Role\Index;
use App\Livewire\System\Role\Form;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Livewire\Livewire;

class RoleManagementTest extends TestCase
{
    public function test_can_view_role_index()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('admin.system.roles.index'))
            ->assertStatus(200)
            ->assertSeeLivewire(Index::class);
    }

    public function test_can_create_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('name', 'Test Role')
            ->set('guard_name', 'web')
            ->call('save')
            ->assertRedirect(route('admin.system.roles.index'));

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }

    public function test_can_edit_role_and_sync_permissions()
    {
        $user = User::factory()->create();
        $superAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user->assignRole($superAdmin);

        $roleToEdit = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'edit articles', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(Form::class, ['role' => $roleToEdit->id])
            ->set('name', 'Senior Editor')
            ->set('selectedPermissions', [$permission->name])
            ->call('save')
            ->assertRedirect(route('admin.system.roles.index'));

        $this->assertDatabaseHas('roles', ['name' => 'Senior Editor']);
        $this->assertTrue($roleToEdit->refresh()->hasPermissionTo('edit articles'));
    }

    public function test_can_delete_role()
    {
        $user = User::factory()->create();
        $superAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user->assignRole($superAdmin);

        $roleToDelete = Role::create(['name' => 'Temporary Role', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $roleToDelete->id);

        $this->assertDatabaseMissing('roles', ['id' => $roleToDelete->id]);
    }
}
