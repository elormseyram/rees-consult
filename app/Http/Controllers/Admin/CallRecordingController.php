<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallRecording;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CallRecordingController extends Controller
{
    /** Browser MediaRecorder output; codecs vary by browser. */
    private const ALLOWED_MIMES = [
        'audio/webm', 'audio/ogg', 'audio/mp4', 'audio/mpeg', 'audio/wav', 'video/webm',
    ];

    /**
     * Store an audio blob recorded in the admin panel while the staff member
     * spoke to the lead on loudspeaker.
     */
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'audio'    => 'required|file|max:51200', // 50 MB ≈ 60+ min of Opus
            'duration' => 'nullable|integer|min:0|max:36000',
            'summary'  => 'nullable|string|max:2000',
            'consent'  => 'accepted',
        ], [
            'consent.accepted' => 'You must confirm the lead was told the call is being recorded.',
        ]);

        $file = $request->file('audio');
        $mime = $file->getMimeType();

        // getMimeType() can report the container without the audio/ prefix.
        if (! in_array($mime, self::ALLOWED_MIMES, true) && ! Str::startsWith($mime, ['audio/', 'video/webm'])) {
            return response()->json(['message' => 'Unsupported audio format: ' . $mime], 422);
        }

        $extension = $file->extension() ?: 'webm';
        $path = $file->storeAs(
            "call-recordings/{$lead->id}",
            now()->format('Ymd-His') . '-' . Str::random(8) . '.' . $extension,
            'local'
        );

        $recording = $lead->recordings()->create([
            'user_id'              => $request->user()->id,
            'recorded_by'          => $request->user()->name,
            'disk'                 => 'local',
            'path'                 => $path,
            'mime_type'            => $mime,
            'duration_seconds'     => (int) $request->input('duration', 0),
            'size_bytes'           => Storage::disk('local')->size($path),
            'summary'              => $request->input('summary'),
            'consent_confirmed'    => true,
            'consent_confirmed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Recording saved.',
            'id'      => $recording->id,
        ]);
    }

    /**
     * Stream the audio back. Files sit on the private disk, so this
     * authenticated route is the only way to reach them.
     */
    public function stream(Lead $lead, CallRecording $recording)
    {
        abort_unless($recording->lead_id === $lead->id, 404);

        $disk = Storage::disk($recording->disk);
        abort_unless($disk->exists($recording->path), 404);

        return $disk->response(
            $recording->path,
            null,
            ['Content-Type' => $recording->mime_type ?: 'audio/webm']
        );
    }

    /** Force a download rather than inline playback. */
    public function download(Lead $lead, CallRecording $recording)
    {
        abort_unless($recording->lead_id === $lead->id, 404);

        $disk = Storage::disk($recording->disk);
        abort_unless($disk->exists($recording->path), 404);

        $name = Str::slug($lead->full_name) . '-' . $recording->created_at->format('Y-m-d-Hi')
            . '.' . pathinfo($recording->path, PATHINFO_EXTENSION);

        return $disk->download($recording->path, $name);
    }

    /**
     * Save a summary against an existing recording.
     */
    public function update(Request $request, Lead $lead, CallRecording $recording)
    {
        abort_unless($recording->lead_id === $lead->id, 404);

        $validated = $request->validate([
            'summary' => 'nullable|string|max:2000',
        ]);

        $recording->update(['summary' => $validated['summary']]);

        return back()->with('success', 'Recording summary updated.');
    }

    /**
     * Delete a recording (and its audio file). Own recordings, or any if admin.
     */
    public function destroy(Request $request, Lead $lead, CallRecording $recording)
    {
        abort_unless($recording->lead_id === $lead->id, 404);

        if (! $request->user()->isAdmin() && $recording->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('You can only delete your own recordings.');
        }

        $recording->delete();

        return back()->with('success', 'Recording deleted.');
    }
}
