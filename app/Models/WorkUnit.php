<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WorkUnit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'parent_id'];

    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WorkUnit::class, 'parent_id');
    }

    public function officials(): HasMany
    {
        return $this->hasMany(WorkUnitOfficial::class);
    }

    public function currentHead()
    {
        return $this->officials()
            ->where('position', 'Kepala')
            ->where('is_active', true)
            ->first();
    }
}
