<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassEnrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'course_class_id',
        'academic_year_id',
        'status',
        'approved_by',
        'grade_letter',
        'grade_index',
    ];

    /**
     * Get the student associated with the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class associated with the enrollment.
     */
    public function courseClass(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class);
    }

    /**
     * Get the academic year of the enrollment.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the lecturer who approved this enrollment.
     */
    public function approver(): BelongsTo
    {
        // Kita perlu spesifikasikan foreign key karena nama method 'approver'
        // tidak cocok dengan nama kolom 'approved_by'
        return $this->belongsTo(Lecturer::class, 'approved_by');
    }
}