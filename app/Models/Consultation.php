<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'service',
        'preferred_date_time',
        'message',
        'status',
    ];

    protected $casts = [
        'preferred_date_time' => 'datetime',
    ];

    /**
     * Get the payment associated with the consultation.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
