<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'category',
        'title',
        'subtitle',
        'description',
        'features',
        'why_choose_program',
        'pricing_type',
        'price',
        'processing_fee',
        'country',
        'currency',
        'duration',
        'class_size',
        'icon',
        'color',
        'image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'why_choose_program' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
        'price' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
    ];

    // Automatically generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    // Relationships
    public function serviceSignups()
    {
        return $this->hasMany(ServiceSignup::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Scope for active services
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered services
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Category Scopes
    public function scopeStandardizedTests($query)
    {
        return $query->where('category', 'standardized_test');
    }

    public function scopeSchoolApplications($query)
    {
        return $query->where('category', 'school_application');
    }

    public function scopeJobAbroad($query)
    {
        return $query->where('category', 'job_abroad');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}

