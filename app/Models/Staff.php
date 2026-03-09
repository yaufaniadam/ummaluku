<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasEmployeeHistory;

class Staff extends Model
{
    use HasFactory, HasEmployeeHistory, SoftDeletes;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'nip',
        'gender',
        'phone',
        'address',
        'status',
        'program_id',
        'work_unit_id',
        'employment_status_id',
        'bank_name',
        'account_number',
        'birth_place',
        'birth_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }

    /**
     * Get all of the education histories for this staff.
     */
    public function educationHistories(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(EducationHistory::class, 'employee');
    }

    /**
     * Get all of the inpassing histories for this staff.
     */
    public function inpassingHistories(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(EmployeeInpassingHistory::class, 'employee');
    }
}
