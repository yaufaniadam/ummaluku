<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRankHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

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
