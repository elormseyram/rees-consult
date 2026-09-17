<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'bg_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(CustomFormQuestion::class)->orderBy('order_index');
    }

    public function submissions()
    {
        return $this->hasMany(CustomFormSubmission::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
