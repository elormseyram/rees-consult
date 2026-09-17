<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'experience_years',
        'current_occupation',
        'highest_education',
        'resume_path',
        'status',
        'notes',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
