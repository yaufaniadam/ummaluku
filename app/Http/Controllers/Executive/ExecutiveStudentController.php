<?php

namespace App\Http\Controllers\Executive;

use App\DataTables\ExecutiveStudentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Student;

class ExecutiveStudentController extends Controller
{
    public function index(ExecutiveStudentDataTable $dataTable)
    {
        return $dataTable->render('executive.students.index');
    }

    public function show(Student $student)
    {
        // Eager load relationships needed for display
        $student->load(['user.prospective', 'program', 'academicAdvisor.user', 'enrollments.courseClass.course']);
        
        // Get enrollment history
        $allEnrollments = $student->enrollments()
            ->where('status', 'approved')
            ->with(['academicYear', 'courseClass.course'])
            ->orderBy('academic_year_id', 'asc')
            ->get();
        
        $enrollmentsBySemester = $allEnrollments->groupBy('academicYear.name');
        
        // Calculate GPA
        $ipk = $student->getCumulativeGpa();
        
        return view('executive.students.show', compact('student', 'enrollmentsBySemester', 'ipk'));
    }
}
