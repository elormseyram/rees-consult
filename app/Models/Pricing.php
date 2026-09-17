<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_name',
        'group_in_person',
        'group_online',
        'one_on_one_in_person',
        'one_on_one_online',
        'order',
        'is_active',
    ];

    protected $casts = [
        'group_in_person' => 'integer',
        'group_online' => 'integer',
        'one_on_one_in_person' => 'integer',
        'one_on_one_online' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
