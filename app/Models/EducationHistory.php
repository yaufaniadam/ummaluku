<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class EducationHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_type',
        'employee_id',
        'education_level',
        'institution_name',
        'graduation_year',
        'major',
        'certificate_path',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'graduation_year' => 'integer',
    ];

    /**
     * Get the parent employee model (Staff or Lecturer).
     */
    public function employee(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the certificate URL.
     */
    public function getCertificateUrlAttribute(): ?string
    {
        return $this->certificate_path 
            ? Storage::url($this->certificate_path) 
            : null;
    }

    /**
     * Delete the certificate file when the model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function ($educationHistory) {
            if ($educationHistory->certificate_path) {
                Storage::delete($educationHistory->certificate_path);
            }
        });
    }
}
