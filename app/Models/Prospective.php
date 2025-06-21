<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prospective extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_place', 'birth_date', 'gender', 'phone', 'address', 'nisn', 'id_number',
        'father_name', 'mother_name', 'father_occupation', 'mother_occupation', 'parent_phone',
        'guardian_name', 'guardian_phone', 'guardian_occupation',
        'religion_id', 'high_school_id',   
        'province_code', 'city_code', 'district_code', 'village_code', 'postal_code',
        'npwp', 'is_kps_recipient', 'father_nik', 'mother_nik', 'father_income', 'mother_income','guardian_income',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}