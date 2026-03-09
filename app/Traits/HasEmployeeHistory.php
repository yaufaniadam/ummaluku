<?php

namespace App\Traits;

use App\Models\EmploymentStatus;
use App\Models\EmployeeStructuralHistory;
use App\Models\EmployeeFunctionalHistory;
use App\Models\EmployeeRankHistory;
use App\Models\EmployeeDocument;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasEmployeeHistory
{
    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function structuralHistories(): MorphMany
    {
        return $this->morphMany(EmployeeStructuralHistory::class, 'employee');
    }

    public function functionalHistories(): MorphMany
    {
        return $this->morphMany(EmployeeFunctionalHistory::class, 'employee');
    }

    public function rankHistories(): MorphMany
    {
        return $this->morphMany(EmployeeRankHistory::class, 'employee');
    }

    public function employeeDocuments(): MorphMany
    {
        return $this->morphMany(EmployeeDocument::class, 'employee');
    }
}
