<?php

namespace Tests\Feature\SDM;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\StructuralPosition;
use App\Models\FunctionalPosition;
use App\Models\EmployeeRank;
use App\Models\EmploymentStatus;
use App\Models\EmployeeDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SdmModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SdmSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function master_data_can_be_accessed_by_admin()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $this->actingAs($admin)
            ->get(route('admin.master.index'))
            ->assertStatus(200);

        Livewire::test(\App\Livewire\Sdm\Master\Index::class)
            ->set('activeTab', 'ranks')
            ->assertSee('Penata Muda')
            ->set('activeTab', 'structural')
            ->assertSee('Rektor');
    }

    /** @test */
    public function employee_profile_tabs_can_save_structural_history()
    {
        $lecturer = Lecturer::factory()->create();
        $user = User::factory()->create();
        $lecturer->user()->associate($user);
        $lecturer->save();

        $position = StructuralPosition::first();

        Livewire::test(\App\Livewire\Sdm\EmployeeProfileTabs::class, ['employee' => $lecturer])
            ->set('activeTab', 'structural')
            ->set('formData.structural_position_id', $position->id)
            ->set('formData.start_date', '2023-01-01')
            ->set('formData.is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee_structural_histories', [
            'employee_id' => $lecturer->id,
            'employee_type' => get_class($lecturer),
            'structural_position_id' => $position->id,
        ]);
    }

    /** @test */
    public function employee_profile_tabs_can_upload_document()
    {
        Storage::fake('public');

        $staff = Staff::factory()->create();
        $user = User::factory()->create();
        $staff->user()->associate($user);
        $staff->save();

        $docType = EmployeeDocumentType::first();
        $file = UploadedFile::fake()->create('ijazah.pdf', 100);

        Livewire::test(\App\Livewire\Sdm\EmployeeProfileTabs::class, ['employee' => $staff])
            ->set('activeTab', 'documents')
            ->set('formData.employee_document_type_id', $docType->id)
            ->set('uploadFile', $file)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee_documents', [
            'employee_id' => $staff->id,
            'employee_type' => get_class($staff),
            'file_name' => 'ijazah.pdf',
        ]);
    }
}
