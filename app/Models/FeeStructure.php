<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fee_component_id',
        'tuition_fee_id',
        'amount',
    ];

    public function feeComponent(): BelongsTo
    {
        return $this->belongsTo(FeeComponent::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}