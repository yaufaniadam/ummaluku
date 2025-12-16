<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['name_id', 'name_en'];

    /**
     * Mendapatkan semua program studi yang ada di bawah fakultas ini.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function officials(): HasMany
    {
        return $this->hasMany(FacultyOfficial::class);
    }

    public function currentDean(): HasOne
    {
        return $this->hasOne(FacultyOfficial::class)
            ->where('position', 'Dekan')
            ->where('is_active', true)
            ->latest('start_date');
    }
}
