<?php

namespace Tests\Feature\Master\Program;

use App\Models\Program;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\ProgramOfficial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Master\Program\OfficialManager;

class OfficialManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_program_official()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin']);
        $admin->assignRole($role);

        $program = Program::create(['name_id' => 'Teknik Informatika', 'code' => 'TI']);

        $lecturerUser = User::factory()->create(['name' => 'Dr. Budi']);
        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'nidn' => '12345',
            'full_name_with_degree' => 'Dr. Budi, M.T.',
        ]);

        $official = ProgramOfficial::create([
            'program_id' => $program->id,
            'lecturer_id' => $lecturer->id,
            'position' => 'Kaprodi',
            'start_date' => '2024-01-01',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(OfficialManager::class, ['program' => $program])
            ->call('edit', $official->id)
            ->set('start_date', '2024-02-01')
            ->call('assignOfficial');

        $this->assertDatabaseHas('program_officials', [
            'id' => $official->id,
            'start_date' => '2024-02-01',
        ]);
    }

    public function test_can_delete_program_official()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'Super Admin']);
        $admin->assignRole($role);

        $program = Program::create(['name_id' => 'Teknik Informatika', 'code' => 'TI']);

        $lecturerUser = User::factory()->create(['name' => 'Dr. Budi']);
        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'nidn' => '12345',
            'full_name_with_degree' => 'Dr. Budi, M.T.',
        ]);

        $official = ProgramOfficial::create([
            'program_id' => $program->id,
            'lecturer_id' => $lecturer->id,
            'position' => 'Kaprodi',
            'start_date' => '2024-01-01',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(OfficialManager::class, ['program' => $program])
            ->call('delete', $official->id);

        $this->assertDatabaseMissing('program_officials', ['id' => $official->id]);
    }
}
