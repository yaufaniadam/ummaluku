<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class AdmissionCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','is_active','price','display_group'];

    protected static function boot()
    {
        parent::boot();

        // Event ini akan berjalan SETIAP KALI data akan dibuat (creating)
        static::creating(function ($category) {
            // Membuat slug dari nama kategori
            $category->slug = Str::slug($category->name);
        });

        // (Opsional tapi direkomendasikan) Event ini berjalan saat data di-update
        static::updating(function ($category) {
            // Jika nama kategori diubah, buat ulang slug-nya
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function documentRequirements(): BelongsToMany
    {
        return $this->belongsToMany(DocumentRequirement::class, 'admission_category_document_requirement');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'admission_category_batch');
    }
}
