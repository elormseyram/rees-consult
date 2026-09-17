<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of consultations.
     */
    public function index(Request $request)
    {
        $query = Consultation::query()->with('payment');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->where('service', $request->service);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $consultations = $query->latest()->paginate(20);

        return view('admin.consultations.index', compact('consultations'));
    }

    /**
     * Display the specified consultation.
     */
    public function show(Consultation $consultation)
    {
        $consultation->load('payment');
        return view('admin.consultations.show', compact('consultation'));
    }

    /**
     * Update the consultation status.
     */
    public function updateStatus(Request $request, Consultation $consultation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $consultation->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Consultation status updated successfully.');
    }

    /**
     * Remove the specified consultation.
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation deleted successfully.');
    }
}
