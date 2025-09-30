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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Mendapatkan invoice yang terhubung dengan pembayaran ini.
     */
    public function academicInvoice(): BelongsTo
    {
        return $this->belongsTo(AcademicInvoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(AcademicPayment::class);
    }
}
