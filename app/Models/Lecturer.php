<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Traits\HasEmployeeHistory;

class Lecturer extends Model
{
    use HasFactory, SoftDeletes, HasEmployeeHistory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'program_id',
        'nidn',
        'nip',
        'full_name_with_degree',
        'front_degree',
        'back_degree',
    ];

    /**
     * Get the user that owns the lecturer record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the program that the lecturer belongs to.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the classes taught by this lecturer.
     */
    public function courseClasses(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }

     public function advisedStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'academic_advisor_id');
    }

    public function programHeads(): HasMany
    {
        return $this->hasMany(ProgramHead::class);
    }

    public function workUnitOfficials(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(WorkUnitOfficial::class, 'employee');
    }

    /**
     * Get all of the education histories for this lecturer.
     */
    public function educationHistories(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(EducationHistory::class, 'employee');
    }
}
