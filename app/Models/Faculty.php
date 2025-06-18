<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    /**
     * Mendapatkan semua program studi yang ada di bawah fakultas ini.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}