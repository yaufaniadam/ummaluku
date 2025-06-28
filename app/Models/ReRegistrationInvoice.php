<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReRegistrationInvoice extends Model
{    
    protected $fillable = [
        'application_id',
        'invoice_number',
        'total_amount',
        'due_date',
        'status',
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(ReRegistrationInstallment::class);
    }
}
