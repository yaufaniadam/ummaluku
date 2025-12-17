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

    public function officials(): MorphMany
    {
        // Actually this is a direct HasMany if we had a non-polymorphic table,
        // but wait, `work_unit_officials` has `work_unit_id`, so it is a HasMany.
        // It's the `employee` that is polymorphic.
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
