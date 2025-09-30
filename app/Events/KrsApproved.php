<?php

namespace App\Events;

use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KrsApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Student $student;
    public AcademicYear $academicYear;

    /**
     * Create a new event instance.
     */
    public function __construct(Student $student, AcademicYear $academicYear)
    {
        $this->student = $student;
        $this->academicYear = $academicYear;
    }
}