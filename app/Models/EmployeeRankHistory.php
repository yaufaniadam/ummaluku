<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRankHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_type',
        'employee_id',
        'employee_rank_id',
        'sk_number',
        'tmt',
        'years_of_service',
        'months_of_service',
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

    public function employeeRank(): BelongsTo
    {
        return $this->belongsTo(EmployeeRank::class);
    }
}
