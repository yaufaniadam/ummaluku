<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Prospective extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_place',
        'birth_date',
        'gender',
        'phone',
        'address',
        'nisn',
        'id_number',
        'citizenship',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'father_income',
        'mother_income',
        'parent_phone',
        'guardian_name',
        'guardian_phone',
        'guardian_occupation',
        'with_guardian',
        'religion_id',
        'high_school_id',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'postal_code',
        'npwp',
        'is_kps_recipient',
        'father_nik',
        'mother_nik',
        'father_income',
        'mother_income',
        'guardian_income',
        'high_school_major_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Mendapatkan data agama dari prospective.
     */
    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    /**
     * Mendapatkan data sekolah dari prospective.
     */
    public function highSchool(): BelongsTo
    {
        return $this->belongsTo(HighSchool::class);
    }

    public function highSchoolMajor(): BelongsTo
    {
        return $this->belongsTo(HighSchoolMajor::class);
    }

     /**
     * Mendapatkan data provinsi dari prospective.
     */
    public function province(): BelongsTo
    {
        // Relasi ke Province, dihubungkan oleh 'province_code' kita ke kolom 'code' di tabel provinces.
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    /**
     * Mendapatkan data kota/kabupaten dari prospective.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    /**
     * Mendapatkan data kecamatan dari prospective.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    /**
     * Mendapatkan data desa/kelurahan dari prospective.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }
}
