<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_id',
        'type',
        'question_text',
        'is_required',
        'options',
        'order_index',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array',
        'order_index' => 'integer',
    ];

    public function customForm()
    {
        return $this->belongsTo(CustomForm::class);
    }

    public function answers()
    {
        return $this->hasMany(CustomFormAnswer::class);
    }
}
