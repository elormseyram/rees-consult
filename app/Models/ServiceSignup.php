<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSignup extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'preferred_class_type',
        'status',
        'notes',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
