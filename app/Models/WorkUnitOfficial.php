<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkUnitOfficial extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_unit_id',
        'employee_type',
        'employee_id',
        'position',
        'start_date',
        'end_date',
        'is_active',
        'sk_number',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function employee(): MorphTo
    {
        return $this->morphTo();
    }
}
