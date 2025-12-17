<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * Get the category that owns the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    /**
     * Get the user who created/approved the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning reference model (e.g., AcademicPayment, ReRegistrationInstallment).
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
