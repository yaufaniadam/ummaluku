<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DocumentRequirement extends Model
{
    use HasFactory;

    public function admissionCategories(): BelongsToMany
    {
        return $this->belongsToMany(AdmissionCategory::class, 'admission_category_document_requirement');
    }
}