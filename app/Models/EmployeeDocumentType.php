<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_mandatory', 'description'];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];
}
