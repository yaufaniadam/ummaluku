<?php

namespace Tests\Feature\Services;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Curriculum;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use App\Services\AutoEnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoEnrollmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_enroll_student_to_semester_package()
    {
        // 1. Setup Data
        $program = Program::factory()->create();

        // Active Curriculum
        $curriculum = Curriculum::factory()->create(['program_id' => $program->id, 'is_active' => true]);

        // Courses for Semester 1 (e.g. 2 courses)
        $course1 = Course::factory()->create(['code' => 'MK1', 'name' => 'Matkul 1', 'sks' => 3]);
        $course2 = Course::factory()->create(['code' => 'MK2', 'name' => 'Matkul 2', 'sks' => 2]);

        // Attach to curriculum semester 1
        $curriculum->courses()->attach([
            $course1->id => ['semester' => 1, 'type' => 'Wajib'],
            $course2->id => ['semester' => 1, 'type' => 'Wajib'],
        ]);

        // Active Academic Year (20251 = Ganjil)
        $academicYear = AcademicYear::create([
            'year_code' => '20251',
            'name' => 'Ganjil 2025/2026',
            'semester_type' => 'Ganjil',
            'start_date' => '2025-09-01',
            'end_date' => '2026-01-31',
            'krs_start_date' => '2025-08-01',
            'krs_end_date' => '2025-08-31',
            'is_active' => true,
        ]);

        // Student (Entry Year 2025 -> Semester 1 now)
        $user = User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'program_id' => $program->id,
            'nim' => '123456789',
            'entry_year' => 2025,
            'status' => 'active',
        ]);

        // 2. Run Service
        $result = AutoEnrollmentService::enrollStudent($student);

        // 3. Assertions
        $this->assertEquals('success', $result['status']);

        // Check Course Classes created
        $this->assertDatabaseHas('course_classes', [
            'course_id' => $course1->id,
            'academic_year_id' => $academicYear->id,
            'name' => 'A',
        ]);

        // Check Enrollments
        $this->assertDatabaseHas('class_enrollments', [
            'student_id' => $student->id,
            'status' => 'approved',
        ]);

        $this->assertEquals(2, $student->enrollments()->count());
    }
}
