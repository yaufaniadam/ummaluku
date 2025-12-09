<?php

use App\Models\User;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('executive dashboard page can be accessed by authorized user', function () {
    // Setup Permission
    $permission = Permission::create(['name' => 'view-executive-dashboard']);
    $role = Role::create(['name' => 'Rector']);
    $role->givePermissionTo($permission);

    // Create User
    $user = User::factory()->create();
    $user->assignRole($role);

    $response = $this->actingAs($user)
        ->get(route('admin.executive.dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.executive.dashboard');
});

test('executive dashboard page cannot be accessed by unauthorized user', function () {
    $user = User::factory()->create(); // No role/permission

    $response = $this->actingAs($user)
        ->get(route('admin.executive.dashboard'));

    $response->assertStatus(403);
});

test('executive dashboard displays student statistics correctly', function () {
    // Setup Permission
    $permission = Permission::create(['name' => 'view-executive-dashboard']);
    $role = Role::create(['name' => 'Rector']);
    $role->givePermissionTo($permission);
    $user = User::factory()->create();
    $user->assignRole($role);

    // Setup Data
    $program = Program::factory()->create(['name_id' => 'Teknik Informatika']);
    Student::factory()->count(5)->create([
        'program_id' => $program->id,
        'status' => 'Aktif'
    ]);

    $response = $this->actingAs($user)
        ->get(route('admin.executive.dashboard'));

    $response->assertStatus(200);

    // Verify data passed to view
    $response->assertViewHas('totalStudents', 5);
    $response->assertViewHas('activeStudents', 5);

    // Verify program data in the collection
    $studentsPerProgram = $response->viewData('studentsPerProgram');
    $tiData = $studentsPerProgram->first(fn($item) => $item['label'] === 'Teknik Informatika');

    expect($tiData)->not->toBeNull();
    expect($tiData['value'])->toBe(5);
});
