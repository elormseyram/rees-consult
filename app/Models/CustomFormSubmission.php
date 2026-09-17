<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_id',
        'ip_address',
        'user_agent',
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
