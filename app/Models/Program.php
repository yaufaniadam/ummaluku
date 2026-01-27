<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_id',
        'name_en',
        'code',
        'degree',
        'faculty_id',
    ];

    /**
     * Mendapatkan data fakultas dari program studi ini.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function officials(): HasMany
    {
        return $this->hasMany(ProgramOfficial::class);
    }

    /**
     * Alias for backward compatibility or clarity, returns all officials.
     */
    public function heads(): HasMany
    {
        return $this->hasMany(ProgramOfficial::class)->where('position', 'Kaprodi');
    }

    public function currentHead(): HasOne
    {
        return $this->hasOne(ProgramOfficial::class)
            ->where('position', 'Kaprodi')
            ->where('is_active', true)
            ->latest('start_date');
    }

    public function currentSecretary(): HasOne
    {
        return $this->hasOne(ProgramOfficial::class)
            ->where('position', 'Sekretaris')
            ->where('is_active', true)
            ->latest('start_date');
    }

    public function feeStructures(): HasMany
    {
        return $this->hasMany(FeeStructure::class);
    }
    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
