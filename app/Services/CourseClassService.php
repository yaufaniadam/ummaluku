<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class CourseClassService
{
    /**
     * Auto-generate classes for a program based on its active curriculum and the academic year's semester type.
     *
     * @param AcademicYear $academicYear
     * @param Program $program
     * @return int Number of classes created
     */
    public function autoGenerateClasses(AcademicYear $academicYear, Program $program): int
    {
        $activeCurriculum = $program->curriculums()->where('is_active', true)->first();

        if (!$activeCurriculum) {
            return 0;
        }

        // Determine target semesters based on Academic Year type
        $targetSemesters = ($academicYear->semester_type === 'Ganjil')
            ? [1, 3, 5, 7]
            : [2, 4, 6, 8];

        // Get courses from the active curriculum for these semesters
        $courses = $activeCurriculum->courses()
            ->whereIn('curriculum_course.semester', $targetSemesters)
            ->get();

        $count = 0;

        DB::transaction(function () use ($courses, $academicYear, &$count) {
            foreach ($courses as $course) {
                // Check if class already exists for this course in this academic year
                $exists = CourseClass::where('academic_year_id', $academicYear->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if (!$exists) {
                    CourseClass::create([
                        'academic_year_id' => $academicYear->id,
                        'course_id' => $course->id,
                        'name' => 'A', // Default class name
                        'capacity' => 40, // Default capacity
                        'lecturer_id' => null, // Allow null lecturer initially
                    ]);
                    $count++;
                }
            }
        });

        return $count;
    }

    /**
     * Copy classes from a source academic year to a target academic year for a specific program.
     *
     * @param AcademicYear $targetYear
     * @param AcademicYear $sourceYear
     * @param Program $program
     * @return int Number of classes copied
     */
    public function copyClassesFromPreviousYear(AcademicYear $targetYear, AcademicYear $sourceYear, Program $program): int
    {
        // Get classes from the source year for this program
        // We filter by program via the course relationship
        $sourceClasses = CourseClass::where('academic_year_id', $sourceYear->id)
            ->whereHas('course', function ($q) use ($program) {
                $q->where('program_id', $program->id);
            })
            ->get();

        $count = 0;

        DB::transaction(function () use ($sourceClasses, $targetYear, &$count) {
            foreach ($sourceClasses as $sourceClass) {
                // Check if a similar class already exists in the target year
                // (Same course and same class name)
                $exists = CourseClass::where('academic_year_id', $targetYear->id)
                    ->where('course_id', $sourceClass->course_id)
                    ->where('name', $sourceClass->name)
                    ->exists();

                if (!$exists) {
                    CourseClass::create([
                        'academic_year_id' => $targetYear->id,
                        'course_id' => $sourceClass->course_id,
                        'lecturer_id' => $sourceClass->lecturer_id, // Copy the lecturer
                        'name' => $sourceClass->name,
                        'capacity' => $sourceClass->capacity,
                        'room' => $sourceClass->room,
                        'day' => $sourceClass->day,
                        'start_time' => $sourceClass->start_time,
                        'end_time' => $sourceClass->end_time,
                    ]);
                    $count++;
                }
            }
        });

        return $count;
    }
}
