<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    /**
     * Mendapatkan semua pendaftaran yang ada di gelombang ini.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}