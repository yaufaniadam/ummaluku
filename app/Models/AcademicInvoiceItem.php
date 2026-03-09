<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicInvoiceItem extends Model
{
    use HasFactory;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'academic_invoice_id',
        'fee_component_id',
        'description',
        'amount',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(AcademicInvoice::class);
    }
}
