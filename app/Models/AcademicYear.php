<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'year_code',
        'semester_type',
        'start_date',
        'end_date',
        'krs_start_date',
        'krs_end_date',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'krs_start_date' => 'date',
        'krs_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes offered in this academic year.
     */
    public function courseClasses(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }

    /**
     * Get all events for the academic year.
     */
    public function events(): HasMany
    {
        return $this->hasMany(AcademicEvent::class);
    }
}