<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EmployeeInpassingHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_type',
        'employee_id',
        'employee_rank_id',
        'sk_number',
        'sk_date',
        'tmt',
        'document_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sk_date' => 'date',
        'tmt' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent employee model (Staff or Lecturer).
     */
    public function employee(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the employee rank for this inpassing.
     */
    public function employeeRank(): BelongsTo
    {
        return $this->belongsTo(EmployeeRank::class);
    }

    /**
     * Delete the document file when the model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function ($inpassing) {
            if ($inpassing->document_path) {
                Storage::delete($inpassing->document_path);
            }
        });
    }
}
