<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    /**
     * Mendapatkan data fakultas dari program studi ini.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
    public function feeStructures(): HasMany
    {
        return $this->hasMany(FeeStructure::class);
    }
    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }
}
