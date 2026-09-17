<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    protected $fillable = ['lead_id', 'user_id', 'author_name', 'body'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Author label that survives the staff account being deleted. */
    public function getAuthorLabelAttribute(): string
    {
        return $this->user->name ?? $this->author_name ?? 'Unknown';
    }
}
