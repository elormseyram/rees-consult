<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'goal',
        'sex', 'age_range', 'country', 'city', 'nationality',
        'test', 'target_score', 'exam_deadline',
        'education_level', 'course_of_interest', 'target_countries',
        'highest_qualification', 'has_passport',
        'experience_years', 'current_occupation', 'target_sector',
        'timeline', 'budget_status', 'funding_source', 'commitment',
        'first_name', 'last_name', 'email', 'phone', 'contact_method', 'notes',
        'rating', 'score', 'willing', 'able', 'willing_score', 'able_score', 'verdict',
        'transcript', 'status',
    ];

    protected $casts = [
        'willing'    => 'boolean',
        'able'       => 'boolean',
        'transcript' => 'array',
    ];

    /**
     * Follow-up notes written by staff. Deliberately NOT named notes() —
     * `notes` is an existing text column on the table and the attribute
     * would shadow the relation.
     */
    public function noteEntries()
    {
        return $this->hasMany(LeadNote::class)->orderByDesc('created_at');
    }

    /** Call audio captured from the admin panel. */
    public function recordings()
    {
        return $this->hasMany(CallRecording::class)->orderByDesc('created_at');
    }

    /** Full name helper for admin display. */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /** Goal as a readable label. */
    public function getGoalLabelAttribute(): string
    {
        return [
            'test_prep'    => 'Test Prep',
            'study_abroad' => 'Study Abroad',
            'work_abroad'  => 'Work Abroad',
        ][$this->goal] ?? ucfirst((string) $this->goal);
    }

    /** Newest first. */
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }
}
