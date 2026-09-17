<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'payment_method',
        'provider',
        'amount',
        'currency',
        'reference_number',
        'screenshot',
        'status',
        'notes',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the consultation that owns the payment.
     */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
