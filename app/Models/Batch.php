<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'year',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Mendapatkan semua pendaftaran yang ada di gelombang ini.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
    public function admissionCategories()
    {
        return $this->belongsToMany(AdmissionCategory::class, 'admission_category_batch');
    }
}
