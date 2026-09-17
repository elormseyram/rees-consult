<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'course_of_interest',
        'level_of_education',
        'target_countries',
        'highest_qualification',
        'has_passport',
        'budget',
        'resume_path',
        'transcript_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'has_passport' => 'boolean',
    ];
}
