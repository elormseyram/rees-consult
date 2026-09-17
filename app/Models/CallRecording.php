<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CallRecording extends Model
{
    protected $fillable = [
        'lead_id', 'user_id', 'recorded_by', 'disk', 'path', 'mime_type',
        'duration_seconds', 'size_bytes', 'summary',
        'consent_confirmed', 'consent_confirmed_at',
    ];

    protected $casts = [
        'consent_confirmed'    => 'boolean',
        'consent_confirmed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Never leave orphaned audio behind on the disk.
        static::deleting(function (CallRecording $recording) {
            Storage::disk($recording->disk)->delete($recording->path);
        });
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRecordedByLabelAttribute(): string
    {
        return $this->user->name ?? $this->recorded_by ?? 'Unknown';
    }

    /** "4:07" style duration. */
    public function getDurationLabelAttribute(): string
    {
        $seconds = (int) $this->duration_seconds;

        return sprintf('%d:%02d', intdiv($seconds, 60), $seconds % 60);
    }

    public function getSizeLabelAttribute(): string
    {
        $bytes = (int) $this->size_bytes;

        return $bytes >= 1048576
            ? round($bytes / 1048576, 1) . ' MB'
            : max(1, round($bytes / 1024)) . ' KB';
    }
}
