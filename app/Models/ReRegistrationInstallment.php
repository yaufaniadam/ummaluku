<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReRegistrationInstallment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        're_registration_invoice_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'proof_of_payment',
        'verified_by',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     * @var array
     */
    protected $casts = [
        'due_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Mendapatkan data tagihan induk (invoice) dari cicilan ini.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(ReRegistrationInvoice::class, 're_registration_invoice_id');
    }

    /**
     * Mendapatkan data admin yang memverifikasi.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}