<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'prospective_id',
        'batch_id',
        'admission_category_id',
        'registration_number',
        'status',
        'is_fee_free',
        'rejection_reason',
        'revision_notes',
    ];

    public function prospective(): BelongsTo
    {
        return $this->belongsTo(Prospective::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function admissionCategory(): BelongsTo
    {
        return $this->belongsTo(AdmissionCategory::class);
    }

    public function programChoices(): HasMany
    {
        return $this->hasMany(ApplicationProgramChoice::class);
    }
    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }
    public function reRegistrationInvoice(): HasOne
    {
        return $this->hasOne(ReRegistrationInvoice::class);
    }
}
