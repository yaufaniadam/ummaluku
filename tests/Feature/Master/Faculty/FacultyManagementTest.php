<?php

namespace Tests\Feature\Master\Faculty;

use App\Models\Faculty;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\FacultyOfficial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Master\Faculty\Index;
use App\Livewire\Master\Faculty\OfficialManager;

class FacultyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_faculties_list()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin']);
        $admin->assignRole($role);

        $faculty = Faculty::create(['name_id' => 'Fakultas Teknik', 'name_en' => 'Faculty of Engineering']);

        $this->actingAs($admin)
            ->get(route('master.faculties.index'))
            ->assertStatus(200)
            ->assertSee('Fakultas Teknik');

        Livewire::test(Index::class)
            ->assertSee('Fakultas Teknik');
    }

    public function test_can_assign_official()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin']);
        $admin->assignRole($role);

        $faculty = Faculty::create(['name_id' => 'Fakultas Teknik']);

        $lecturerUser = User::factory()->create(['name' => 'Dr. Budi']);
        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'nidn' => '12345',
            'full_name_with_degree' => 'Dr. Budi, M.T.',
        ]);

        $this->actingAs($admin);

        Livewire::test(OfficialManager::class, ['faculty' => $faculty])
            ->set('position', 'Dekan')
            ->set('employee_id', $lecturer->id)
            ->set('start_date', '2025-01-01')
            ->set('sk_number', 'SK/001')
            ->call('assignOfficial')
            ->assertDispatched('alert');

        $this->assertDatabaseHas('faculty_officials', [
            'faculty_id' => $faculty->id,
            'position' => 'Dekan',
            'employee_type' => Lecturer::class,
            'employee_id' => $lecturer->id,
            'is_active' => true,
        ]);
    }
}
