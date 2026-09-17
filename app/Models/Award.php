<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'photo',
        'awarded_by',
        'award_date',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'order'      => 'integer',
        'award_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('award_date', 'desc');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function getFormattedDateAttribute(): ?string
    {
        return $this->award_date ? $this->award_date->format('F Y') : null;
    }
}
