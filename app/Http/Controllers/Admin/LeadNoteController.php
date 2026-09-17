<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LeadNoteController extends Controller
{
    /**
     * Attach a follow-up note to a lead so the rest of the team can see
     * what was discussed.
     */
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $lead->noteEntries()->create([
            'user_id'     => $request->user()->id,
            'author_name' => $request->user()->name,
            'body'        => $validated['body'],
        ]);

        return back()->with('success', 'Note added.');
    }

    /**
     * Remove a note. Staff can delete their own; admins can delete any.
     */
    public function destroy(Request $request, Lead $lead, LeadNote $note)
    {
        $this->assertBelongsToLead($lead, $note);

        if (! $request->user()->isAdmin() && $note->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('You can only delete your own notes.');
        }

        $note->delete();

        return back()->with('success', 'Note deleted.');
    }

    private function assertBelongsToLead(Lead $lead, LeadNote $note): void
    {
        abort_unless($note->lead_id === $lead->id, 404);
    }
}
