<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    private array $statuses = ['new', 'contacted', 'qualified', 'won', 'lost'];

    /**
     * List & filter Apply Now leads.
     */
    public function index(Request $request)
    {
        $query = $this->filtered($request);

        $leads = $query->latestFirst()->paginate(15)->withQueryString();

        $counts = [
            'total' => Lead::count(),
            'hot'   => Lead::where('rating', 'HOT')->count(),
            'warm'  => Lead::where('rating', 'WARM')->count(),
            'cold'  => Lead::where('rating', 'COLD')->count(),
            'new'   => Lead::where('status', 'new')->count(),
        ];

        return view('admin.leads.index', compact('leads', 'counts'));
    }

    /**
     * Show a single lead with full transcript and assessment.
     */
    public function show(Lead $lead)
    {
        return view('admin.leads.show', [
            'lead'     => $lead,
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Update the follow-up status (and optional admin note).
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', $this->statuses),
            'notes'  => 'nullable|string|max:2000',
        ]);

        $lead->update([
            'status' => $validated['status'],
            'notes'  => $request->filled('notes') ? $validated['notes'] : $lead->notes,
        ]);

        return back()->with('success', 'Lead marked as "' . ucfirst($validated['status']) . '".');
    }

    /**
     * Delete a lead.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    /**
     * Export the (filtered) leads to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $leads = $this->filtered($request)->latestFirst()->get();

        $columns = [
            'ID', 'Created', 'Rating', 'Score', 'Willing', 'Able', 'Status', 'Goal',
            'First Name', 'Last Name', 'Email', 'Phone', 'Preferred Contact',
            'Timeline', 'Budget', 'Funding', 'Commitment',
            'Test', 'Course', 'Target Countries', 'Education Level', 'Occupation', 'Notes',
        ];

        $filename = 'leads-' . now()->format('Y-m-d-His') . '.csv';

        return response()->stream(function () use ($leads, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);

            foreach ($leads as $l) {
                fputcsv($out, [
                    $l->id,
                    optional($l->created_at)->format('Y-m-d H:i'),
                    $l->rating,
                    $l->score,
                    $l->willing ? 'Yes' : 'No',
                    $l->able ? 'Yes' : 'No',
                    $l->status,
                    $l->goal_label,
                    $l->first_name,
                    $l->last_name,
                    $l->email,
                    $l->phone,
                    $l->contact_method,
                    $l->timeline,
                    $l->budget_status,
                    $l->funding_source,
                    $l->commitment,
                    $l->test,
                    $l->course_of_interest,
                    $l->target_countries,
                    $l->education_level,
                    $l->current_occupation,
                    $l->notes,
                ]);
            }

            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Apply shared list filters (rating, status, goal, search).
     */
    private function filtered(Request $request)
    {
        $query = Lead::query();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('goal')) {
            $query->where('goal', $request->goal);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                    ->orWhere('last_name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        return $query;
    }
}
