<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeFunctionalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_type',
        'employee_id',
        'functional_position_id',
        'sk_number',
        'tmt',
        'is_active'
    ];

    protected $casts = [
        'tmt' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee(): MorphTo
    {
        return $this->morphTo();
    }

    public function functionalPosition(): BelongsTo
    {
        return $this->belongsTo(FunctionalPosition::class);
    }
}
