<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_submission_id',
        'custom_form_question_id',
        'answer',
    ];

    public function submission()
    {
        return $this->belongsTo(CustomFormSubmission::class, 'custom_form_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(CustomFormQuestion::class, 'custom_form_question_id');
    }
}
