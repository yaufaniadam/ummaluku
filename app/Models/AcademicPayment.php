<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'academic_invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'proof_path',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    /**
     * Mendapatkan invoice yang terhubung dengan pembayaran ini.
     */
    public function academicInvoice(): BelongsTo
    {
        return $this->belongsTo(AcademicInvoice::class);
    }


}
