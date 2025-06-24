<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdmissionCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','is_active','price','display_group'];

    public function documentRequirements(): BelongsToMany
    {
        return $this->belongsToMany(DocumentRequirement::class, 'admission_category_document_requirement');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'admission_category_batch');
    }
}
