<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuitionFee extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}