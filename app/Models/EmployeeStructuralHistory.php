<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeStructuralHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_type',
        'employee_id',
        'structural_position_id',
        'work_unit_id',
        'sk_number',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee(): MorphTo
    {
        return $this->morphTo();
    }

    public function structuralPosition(): BelongsTo
    {
        return $this->belongsTo(StructuralPosition::class);
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }
}
