<?php

namespace Tests\Feature\PMB;

use App\Models\AcademicYear;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\ApplicationProgramChoice;
use App\Models\Batch;
use App\Models\ClassEnrollment;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Curriculum;
use App\Models\Program;
use App\Models\Prospective;
use App\Models\ReRegistrationInvoice;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinalizeRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_auto_enrolls_mandatory_courses_on_finalization()
    {
        // Create roles
        Role::create(['name' => 'Camaru']);
        Role::create(['name' => 'Mahasiswa']);
        Role::create(['name' => 'Super Admin']);

        // 1. Setup Data
        $program = Program::create(['code' => 'TI', 'name_id' => 'Teknik Informatika', 'degree' => 'S1']);

        // Year 20251 for entry year 2025
        $academicYear = AcademicYear::create([
            'year_code' => '20251',
            'name' => 'Ganjil 2025/2026',
            'semester_type' => 'Ganjil',
            'start_date' => '2025-09-01',
            'end_date' => '2026-01-31',
            'is_active' => true,
        ]);

        $curriculum = Curriculum::create([
            'program_id' => $program->id,
            'name' => 'Kurikulum 2025',
            'is_active' => true,
        ]);

        $course = Course::create([
            'code' => 'MK001',
            'name' => 'Algoritma',
            'sks' => 3,
            'semester_recommendation' => 1,
            'type' => 'Wajib',
        ]);

        // Attach course to curriculum for Semester 1
        $curriculum->courses()->attach($course->id, ['semester' => 1, 'type' => 'Wajib']);

        $batch = Batch::create([
            'year' => 2025,
            'name' => 'Gelombang 1',
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $user->assignRole('Camaru'); // Ensure role exists or use what's available

        $prospective = Prospective::create([
            'user_id' => $user->id,
            'name' => 'John Doe',
            'phone' => '08123456789',
        ]);

        $admissionCategory = AdmissionCategory::create([
            'name' => 'Reguler',
            'description' => 'Jalur Reguler',
        ]);

        $application = Application::create([
            'prospective_id' => $prospective->id,
            'batch_id' => $batch->id,
            'status' => 'diterima',
            'admission_category_id' => $admissionCategory->id,
        ]);

        // Create accepted program choice
        ApplicationProgramChoice::create([
            'application_id' => $application->id,
            'program_id' => $program->id,
            'choice_order' => 1,
            'is_accepted' => true,
        ]);

        // Create paid invoice
        $invoice = ReRegistrationInvoice::create([
            'application_id' => $application->id,
            'amount' => 1000000,
            'status' => 'paid',
            'due_date' => now()->addDays(7),
        ]);

        // Login as admin
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');
        $this->actingAs($admin);

        // 2. Action
        $response = $this->post(route('admin.pmb.accepted.finalize', $application->id));

        // 3. Assertion
        $response->assertRedirect(route('admin.pmb.diterima.index'));
        $response->assertSessionHas('success');
        $response->assertSessionDoesntHaveErrors();

        // Check Student created
        $student = Student::where('user_id', $user->id)->first();
        $this->assertNotNull($student);
        $this->assertEquals('Aktif', $student->status);

        // Check Enrollment
        $enrollment = ClassEnrollment::where('student_id', $student->id)->first();
        $this->assertNotNull($enrollment, 'Enrollment should be created');
        $this->assertEquals('approved', $enrollment->status, 'Status should be approved');
        $this->assertEquals($academicYear->id, $enrollment->academic_year_id, 'Academic Year ID should be set');

        // Check Course Class auto-created
        $class = CourseClass::find($enrollment->course_class_id);
        $this->assertNotNull($class);
        $this->assertEquals('Kelas A (Auto)', $class->name);
    }
}
